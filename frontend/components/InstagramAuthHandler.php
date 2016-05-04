<?php
namespace frontend\components;

use common\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * InstagramAuthHandler handles successful authentification via Yii auth component
 */
class InstagramAuthHandler
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
        $accessToken = $this->client->accessToken->token;

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


        /** @var User $user */
        $user = User::find()->where([
            'user_instagram_id' => $id, //id in instagram
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($user) {
                /**
                 * Login: Update his details and Login
                 */
                $user->user_name = $username;
                $user->user_fullname = $fullname;
                $user->user_profile_pic = $profilePhoto;
                $user->user_bio = $bio;
                $user->user_website = $website;
                $user->user_media_count = $mediaCount;
                $user->user_following_count = $followsCount;
                $user->user_follower_count = $followersCount;
                $user->user_ig_access_token = $accessToken;
                $user->user_status = User::STATUS_ACTIVE;


                if ($user->save()) {
                    Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
                }
            } else {
                /**
                 * Signup
                 */
                if ($username !== null && User::find()->where(['user_name' => $username])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same username as in {client} account already exists but isn't linked to it. Contact us to resolve the issue.", ['client' => $this->client->getTitle()]),
                    ]);
                } else {
                    $user = new User([
                        'user_name' => $username,
                        'user_fullname' => $fullname,
                        'user_instagram_id' => $id,
                        'user_profile_pic' => $profilePhoto,
                        'user_bio' => $bio,
                        'user_website' => $website,
                        'user_media_count' => $mediaCount,
                        'user_following_count' => $followsCount,
                        'user_follower_count' => $followersCount,
                        'user_ig_access_token' => $accessToken,

                    ]);
                    $user->generateAuthKey();

                    if (!$user->save()) {
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', 'Unable to save user: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($user->getErrors()),
                            ]),
                        ]);
                    }else{
                        Yii::$app->user->login($user, Yii::$app->params['user.rememberMeDuration']);
                    }
                }
            }
        }
    }

}
