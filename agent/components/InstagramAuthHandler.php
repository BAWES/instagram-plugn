<?php
namespace agent\components;

use common\models\InstagramUser;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

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

        // Die if Agent isn't logged in
        if(Yii::$app->user->isGuest){
            die("Must be logged in to add an Instagram account");
        }
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

        /** @var InstagramUser $user */
        $user = InstagramUser::find()->where([
            'user_instagram_id' => $id, //id in instagram
        ])->one();

        if ($user) {
            $oldAgentId = $user->agent_id;
            if(!$oldAgentId){
                // Check if this agent is at his account limit and stop them from adding if not allowed
                if(Yii::$app->user->identity->isAtAccountLimit){
                    $billingLink = Url::to(['billing/index']);

                    Yii::$app->getSession()->setFlash('error',
                        "[Account Limit Reached] Unable to add the account. Please upgrade your <a href=\'$billingLink\'>billing plan</a> to allow additional accounts to be added.");
                    Yii::error("[Agent hit account limit. Unable to add user @".$user->user_name."] Its still assigned to Agent #$oldAgentId", __METHOD__);
                    return;
                }
            }

            /**
             * Login: Update his details and Login
             */
            $user->user_name = $username;
            $user->agent_id = Yii::$app->user->identity->agent_id;
            $user->user_fullname = $fullname;
            $user->user_profile_pic = $profilePhoto;
            $user->user_bio = $bio;
            $user->user_website = $website;
            $user->user_media_count = $mediaCount;
            $user->user_following_count = $followsCount;
            $user->user_follower_count = $followersCount;
            $user->user_ig_access_token = $accessToken;
            $user->user_status = InstagramUser::STATUS_INACTIVE;

            // Check and log if this is an agent change
            if($oldAgentId && ($oldAgentId != $user->agent_id)){
                Yii::warning("[Instagram account @".$user->user_name." being moved to new agent account] It was previously in Agent #$oldAgentId", __METHOD__);
            }


            if ($user->save()) {
                Yii::info("[Instagram Updated Token @".$user->user_name."] http://instagram.com/".$user->user_name." - ".$user->user_follower_count." followers - ".$user->user_bio, __METHOD__);

                // Disable account if trial/billing not setup
                $user->activateAccountIfPossible();

                // Return the saved model
                return $user;
            }
        } else {
            /**
             * Signup
             */
             // Check if this agent is at his account limit and stop them from adding if not allowed
             if(Yii::$app->user->identity->isAtAccountLimit){
                 $billingLink = Url::to(['billing/index']);

                 Yii::$app->getSession()->setFlash('error',
                     "[Account Limit Reached] Unable to add the account. Please upgrade your <a href=\'$billingLink\'>billing plan</a> to allow additional accounts to be added.");
                 Yii::error("[Agent hit account limit. Unable to add user @".$username."] Get in touch if they need assistance.", __METHOD__);
                 return;
             }

            if ($username !== null && InstagramUser::find()->where(['user_name' => $username])->exists()) {
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app', "User with the same username as in {client} account already exists but isn't linked to it. Contact us to resolve the issue.", ['client' => $this->client->getTitle()]),
                ]);
            } else {
                $user = new InstagramUser([
                    'agent_id' => Yii::$app->user->identity->agent_id,
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
                    'user_status' => InstagramUser::STATUS_INACTIVE
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
                    //Log new Instagram signup
                    Yii::info("[New Instagram Signup @".$user->user_name."] http://instagram.com/".$user->user_name." - ".$user->user_follower_count." followers - ".$user->user_bio, __METHOD__);

                    // Disable account if trial/billing not setup
                    $user->activateAccountIfPossible();

                    // Return the saved model
                    return $user;
                }
            }
        }

    }

}
