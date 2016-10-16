<?php
namespace agent\components\authhandlers;

use common\models\Agent;
use common\models\AgentAuth;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 * AuthHandler handles successful authentification via Yii auth component
 */
class GoogleAuthHandler
{
    /**
     * Specify the target environment so specify how to handle login (mobile/etc.)
     * @var string
     */
    private $targetEnvironment;

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client, $targetEnv = "browser")
    {
        $this->client = $client;

        $this->targetEnvironment = $targetEnv;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $email = ArrayHelper::getValue($attributes, 'emails')[0]['value'];
        $id = ArrayHelper::getValue($attributes, 'id');
        $nickname = ArrayHelper::getValue($attributes, 'displayName');

        /** @var AgentAuth $auth */
        $auth = AgentAuth::find()->where([
            'auth_source' => $this->client->getId(),
            'auth_source_id' => $id,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                /** @var Agent $agent */
                $agent = $auth->agent;

                Yii::$app->user->login($agent, Yii::$app->params['user.rememberMeDuration']);
            } else { // signup
                $existingAgent = Agent::find()->where(['agent_email' => $email])->one();
                if ($existingAgent) {
                    //There's already an agent with this email, update his details
                    //And create an auth record for him and log him in

                    $existingAgent->agent_name = $nickname;
                    $existingAgent->agent_email_verified = Agent::EMAIL_VERIFIED;
                    $existingAgent->generatePasswordResetToken();

                    $transaction = Agent::getDb()->beginTransaction();

                    if ($existingAgent->save()) {
                        $auth = new AgentAuth([
                            'agent_id' => $existingAgent->id,
                            'auth_source' => $this->client->getId(),
                            'auth_source_id' => (string)$id,
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($existingAgent, Yii::$app->params['user.rememberMeDuration']);
                        } else {
                            $msg = Yii::t('app', 'Unable to save {client} account: {errors}', [
                                    'client' => $this->client->getTitle(),
                                    'errors' => json_encode($auth->getErrors()),
                                ]);
                            $this->displayError($msg);
                        }
                    } else {
                        $msg = Yii::t('app', 'Unable to save agent: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($existingAgent->getErrors()),
                            ]);
                        $this->displayError($msg);
                    }

                } else {
                    //Agent Doesn't have an account, create one for him
                    $agent = new Agent([
                        'agent_name' => $nickname,
                        'agent_email' => $email,
                        'agent_email_verified' => Agent::EMAIL_VERIFIED,
                        'agent_limit_email' => new Expression('NOW()')
                    ]);
                    $agent->setPassword(Yii::$app->security->generateRandomString(6));
                    $agent->generateAuthKey();
                    $agent->generatePasswordResetToken();

                    $transaction = Agent::getDb()->beginTransaction();

                    if ($agent->save()) {
                        $auth = new AgentAuth([
                            'agent_id' => $agent->id,
                            'auth_source' => $this->client->getId(),
                            'auth_source_id' => (string)$id,
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($agent, Yii::$app->params['user.rememberMeDuration']);

                            //Log agent signup
                            Yii::info("[New Agent Signup GoogleAuth] ".$agent->agent_email, __METHOD__);
                        } else {
                            $msg = Yii::t('app', 'Unable to save {client} account: {errors}', [
                                    'client' => $this->client->getTitle(),
                                    'errors' => json_encode($auth->getErrors()),
                                ]);
                            $this->displayError($msg);
                        }
                    } else {
                        $msg = Yii::t('app', 'Unable to save agent: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($agent->getErrors()),
                            ]);
                        $this->displayError($msg);
                    }
                }
            }
        }
    }

    /**
     * Displays error to user depending on target environment
     * @param  string $message the message that will be added as error
     */
    private function displayError($message){
        Yii::$app->getSession()->setFlash('error', [
            $message
        ]);
    }

}
