<?php
namespace frontend\components;

use common\models\Auth;
use common\models\User;
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

        /**
         * Response from Instagram
         */
        $id = ArrayHelper::getValue($attributes, 'id'); //Unique Instagram User ID
        $username = ArrayHelper::getValue($attributes, 'username'); //Unique Instagram User Name
        $fullname = ArrayHelper::getValue($attributes, 'full_name'); //Full name as specified in Instagram
        $profilePhoto = ArrayHelper::getValue($attributes, 'profile_picture');
        $bio = ArrayHelper::getValue($attributes, 'bio');
        $website = ArrayHelper::getValue($attributes, 'website');
        $mediaCount = ArrayHelper::getValue($attributes, 'counts.media');
        $followsCount = ArrayHelper::getValue($attributes, 'counts.follows');
        $followersCount = ArrayHelper::getValue($attributes, 'counts.followed_by');


        // TODO
        //Get rid of Auth table. Merge it with User table. Auth source will always be Instagram so no need to store


        /** @var Auth $auth */
        $auth = Auth::find()->where([
            'auth_source' => $this->client->getId(), //useless field
            'auth_source_id' => $id, //id in instagram
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                /** @var User $user */
                $user = $auth->user;

                Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
            } else { // signup
                if ($username !== null && User::find()->where(['user_name' => $username])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same username as in {client} account already exists but isn't linked to it. Contact us to resolve the issue.", ['client' => $this->client->getTitle()]),
                    ]);
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'user_name' => $username,
                        'user_fullname' => $fullname,
                        'user_password_hash' => $password,
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();

                    $transaction = User::getDb()->beginTransaction();

                    if ($user->save()) {
                        $auth = new Auth([
                            'auth_user_id' => $user->id,
                            'auth_source' => $this->client->getId(),
                            'auth_source_id' => (string)$id,
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
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
                            Yii::t('app', 'Unable to save user: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($user->getErrors()),
                            ]),
                        ]);
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'auth_user_id' => Yii::$app->user->id,
                    'auth_source' => $this->client->getId(),
                    'auth_source_id' => (string)$attributes['id'],
                ]);
                if ($auth->save()) {
                    /** @var User $user */
                    $user = $auth->user;
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
                        'Unable to link {client} account. There is another user using it.',
                        ['client' => $this->client->getTitle()]),
                ]);
            }
        }
    }

}
