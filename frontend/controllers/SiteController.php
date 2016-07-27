<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use frontend\components\InstagramAuthHandler;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['agent/index']);
    }

    /**
     * Authorization success handler
     *
     * @return mixed
     */
    public function onAuthSuccess($client)
    {
        //Client is an Instance of Instagram/OAuth2/BaseOAuth classes

        (new InstagramAuthHandler($client))->handle();
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->redirect(['site/auth', 'authclient' => 'instagram']);
    }

    /**
     * Logs out the current user from Plugn Platform
     *
     * @return mixed
     */
    public function actionLoginViaAgent()
    {
        Yii::$app->user->logout();

        return $this->redirect("login");
    }

    /**
     * Logs out the current user from Plugn Platform
     *
     * @return mixed
     */
    public function actionLogoutReal()
    {
        Yii::$app->user->logout();

        return $this->redirect("http://plugn.io");
    }

    /**
     * Logs out the current user.
     * Starts by logging out of Instagram then redirects to log you out of Plugn Platform
     * @return mixed
     */
    public function actionLogout()
    {
        $this->layout = 'blank';
        $logoutUrl = Url::to(['site/logout-real']);

        /**
         * The following view file will display an iFrame which will log the user out of
         * Instagram then redirect to site/logout-real to process logging out from Plugn
         */
        return $this->render("instagram-logout", ['logoutUrl' => $logoutUrl]);
    }


}
