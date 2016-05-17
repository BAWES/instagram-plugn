<?php
namespace agent\components;

use common\models\Agent;
use common\models\AgentAuth;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentification via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');
        $nickname = ArrayHelper::getValue($attributes, 'login');

        /** @var AgentAuth $auth */
        $auth = AgentAuth::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $id,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                /** @var Agent $agent */
                $agent = $auth->agent;

                Yii::$app->user->login($agent, Yii::$app->params['user.rememberMeDuration']);
            } else { // signup
                if ($email !== null && Agent::find()->where(['agent_email' => $email])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Agent with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $agent = new Agent([
                        'agent_name' => $nickname,
                        'agent_email' => $email,
                        'agent_password_hash' => $password,
                        'agent_email_verified' => Agent::EMAIL_VERIFIED,
                    ]);
                    $agent->generateAuthKey();
                    $agent->generatePasswordResetToken();

                    $transaction = Agent::getDb()->beginTransaction();

                    if ($agent->save()) {
                        $auth = new AgentAuth([
                            'agent_id' => $agent->id,
                            'source' => $this->client->getId(),
                            'source_id' => (string)$id,
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($agent, Yii::$app->params['user.rememberMeDuration']);
                        } else {
                            Yii::$app->getSession()->setFlash('error', [
                                Yii::t('app', 'Unable to save {client} account: {errors}', [
                                    'client' => $this->client->getTitle(),
                                    'errors' => json_encode($auth->getErrors()),
                                ]),
                            ]);
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', 'Unable to save agent: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($agent->getErrors()),
                            ]),
                        ]);
                    }
                }
            }
        } else { // agent already logged in
            if (!$auth) { // add auth provider
                $auth = new AgentAuth([
                    'agent_id' => Yii::$app->user->id,
                    'source' => $this->client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                if ($auth->save()) {
                    /** @var Agent $agent */
                    $agent = $auth->agent;

                    Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', 'Linked {client} account.', [
                            'client' => $this->client->getTitle()
                        ]),
                    ]);
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Unable to link {client} account: {errors}', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($auth->getErrors()),
                        ]),
                    ]);
                }
            } else { // there's existing auth
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app',
                        'Unable to link {client} account. There is another agent using it.',
                        ['client' => $this->client->getTitle()]),
                ]);
            }
        }
    }

}
