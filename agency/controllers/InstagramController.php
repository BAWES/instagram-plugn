<?php
namespace agency\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use agency\components\InstagramAuthHandler;

/**
 * Instagram controller
 */
class InstagramController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], //only allow authenticated users to all actions
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * Instagram Authorization success handler
     *
     * @return mixed
     */
    public function onAuthSuccess($client)
    {
        //Client is an Instance of Instagram/OAuth2/BaseOAuth classes

        $igAccountModel = (new InstagramAuthHandler($client))->handle();

        // Above variable holds an Instagram Account's info on success
        if($igAccountModel instanceof \common\models\InstagramUser){
            // Redirect to that accounts management page

            //die("Returned Instagram User, process shit here maybe?");
        }

        return $this->goHome();
    }

    /**
     * Request authorization from Instagram to manage an account
     * Logs a user out before proceeding
     *
     * @return mixed
     */
    public function actionAuthorize()
    {
        $this->layout = 'blank';

        $authUrl = Url::to(['instagram/auth', 'authclient' => 'instagram']);

        //return $this->redirect(['instagram/auth', 'authclient' => 'instagram']);

        /**
         * The following view file will display an iFrame which will log the user out of
         * Instagram then redirect to the Instagram authorization url
         */
        return $this->render("instagram-logout-redirect", ['redirectUrl' => $authUrl]);
    }


}
