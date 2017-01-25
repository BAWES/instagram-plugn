<?php
namespace agent\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use common\models\Agent;
use agent\components\InstagramAuthHandler;

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

        $instagramAccount = (new InstagramAuthHandler($client))->handle();

        // Above variable holds an Instagram Account's info on success
        if($instagramAccount instanceof \common\models\InstagramUser){
            // Add account owner as an agent of this Instagram Account
            $agentAssignment = new \common\models\AgentAssignment();
            $agentAssignment->instagramAccountModel = $instagramAccount;
            $agentAssignment->user_id = $instagramAccount->user_id;
            $agentAssignment->assignment_agent_email = Yii::$app->user->identity->agent_email;
            $agentAssignment->save();

            // Redirect to success page with username shown
            $igUsername = $instagramAccount->user_name;
            return $this->redirect(['instagram/response', 'responseType' => 'success']);
        }

        Yii::error("[Agent #".Yii::$app->user->identity->agent_id." Oauth Success Error] Agent faced EXTREME issue with auth process.", __METHOD__);

        return $this->redirect(['instagram/response', 'responseType' => 'fail']);
    }

    /**
     * Display Response based on Response Type
     */
    public function actionResponse($responseType = 'success'){
        if($responseType == 'fail'){
            // Render Error Page
            return $this->render("fail", ['message' => "There was a problem linking your Instagram account. Please try again."]);
        }

        // Render Success Page
        return $this->render("success", ['message' => "Your Instagram account has been linked to Plugn."]);
    }

    /**
     * Request authorization from Instagram to manage an account
     * Logs a user out before proceeding
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'blank';

        // If billing has expired for this user, redirect to billing page
        if(Yii::$app->user->identity->agent_status == Agent::STATUS_INACTIVE){
            return $this->redirect(['billing/index']);
        }

        // Agent Hit Account Limit?
        if(Yii::$app->user->identity->isAtAccountLimit){
            Yii::error("[Agent #".Yii::$app->user->identity->agent_id." unable to add more accounts] Agent needs to upgrade plan to add more accounts.", __METHOD__);
            Yii::$app->getSession()->setFlash('warning',
                "[Account Limit Reached] Please upgrade your billing plan for additional Instagram accounts.");
            return $this->redirect(['billing/index']);
        }

        /**
         * The following view file will display an iFrame which will log the user out of
         * Instagram then redirect to the Instagram authorization url
         */
        $authUrl = Url::to(['instagram/auth', 'authclient' => 'instagram']);
        return $this->render("instagram-logout-redirect", ['redirectUrl' => $authUrl]);
    }

}
