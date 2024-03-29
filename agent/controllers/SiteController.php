<?php
namespace agent\controllers;

use Yii;
use yii\web\Controller;
use agent\components\authhandlers\GoogleAuthHandler;
use agent\components\authhandlers\LiveAuthHandler;
use agent\components\authhandlers\SlackAuthHandler;
use common\models\Agent;
use agent\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * Redirects to Ionic2 Agent Panel
     */
    public function actionIndex()
    {
        return $this->redirect("https://agent.plugn.io/app");
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
            'authmobile' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthMobileSuccess'],
            ],
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     *  Handle successful authentication on Mobile.
     *  Should return a temporary invalid access token on success
     *
     *  Invalid access tokens need to be exchanged for valid ones once device details are provided
     */
    public function onAuthMobileSuccess($client)
    {
        if($client instanceof yii\authclient\clients\Live){
            //Handle Microsoft Live Authentication
            (new LiveAuthHandler($client, "mobile"))->handle();
        }elseif($client instanceof yii\authclient\clients\GoogleOAuth){
            //Handle Google Authentication
            (new GoogleAuthHandler($client, "mobile"))->handle();
        }elseif($client instanceof \agent\components\SlackAuthClient){
            //Handle Slack Authentication
            (new SlackAuthHandler($client, "mobile"))->handle();
        }

        $response = "";
        if(!Yii::$app->user->isGuest){
            // Send a token back to app which will be used in future requests
            $token = Yii::$app->user->identity->getAccessToken()->token_value;
            $agentId = Yii::$app->user->identity->agent_id;
            $name = Yii::$app->user->identity->agent_name;
            $email = Yii::$app->user->identity->agent_email;

            $response = "$token:!:$agentId:!:$name:!:$email";
        }else $response = "Error during login, please contact us for assistance";

        $response = "
        <script>
        var resp = '".$response."';
        localStorage.setItem('response', resp );
        </script>";


        /**
         * Send Oauth Response to Mobile for handling
         */
        Yii::$app->response->content = $response;
        return Yii::$app->response;
    }

    /**
     *  Handle successful authentication on Desktop
     */
    public function onAuthSuccess($client)
    {
        if($client instanceof yii\authclient\clients\Live){
            //Handle Microsoft Live Authentication
            (new LiveAuthHandler($client))->handle();
        }elseif($client instanceof yii\authclient\clients\GoogleOAuth){
            //Handle Google Authentication
            (new GoogleAuthHandler($client))->handle();
        }elseif($client instanceof \agent\components\SlackAuthClient){
            //Handle Slack Authentication
            (new SlackAuthHandler($client))->handle();
        }

        $script = "";
        if(!Yii::$app->user->isGuest){
            // Send a token back to app which will be used in future requests
            $token = Yii::$app->user->identity->getAccessToken()->token_value;
            $agentId = Yii::$app->user->identity->agent_id;
            $name = Yii::$app->user->identity->agent_name;
            $email = Yii::$app->user->identity->agent_email;

            $script .= "
            <script>
            localStorage.setItem('bearer', '$token' );
            localStorage.setItem('agentId', '$agentId' );
            localStorage.setItem('name', '$name' );
            localStorage.setItem('email', '$email' );
            window.location = 'https://agent.plugn.io/app';
            </script>
            ";
        }else $script = "Unable to login.";


        /**
         * Redirect with values stored in localstorage
         */
         Yii::$app->response->content = $script;
         return Yii::$app->response;
    }

    /**
     * Use a previously generated Access Key to login then redirect to where the user wants.
     * Redirects to billing by default
     */
    public function actionLoginAuthKey($key, $path = "billing"){
        // Find the agent by Auth Key
        $agent = Agent::findOne(['agent_auth_key' => $key]);
        if($agent){
            // Reset the previously used auth key
            $agent->generateAuthKeyAndSave();
            // Log agent out before attempting to log him in
            if(!Yii::$app->user->isGuest){
                Yii::$app->user->logout();
            }
            // Log the agent in
            Yii::$app->user->login($agent, 0);
        }

        // Resolve what path the agent wants to follow
        switch($path){
            case "billing":
                $path = "billing/index";
                break;
            case "instagram":
                $path = "instagram/index";
                break;
        }
        return $this->redirect([$path]);
    }

    /**
     * Login Page
     */
    public function actionLogin() {
        if(!Yii::$app->user->isGuest){
            return $this->goHome();
        }

        $this->layout = 'signup';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['billing/index']);
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout Page
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->redirect(['site/index']);
    }

    /**
     * Returns the apple site association file required for deeplinking.
     */
    public function actionAppleAppAssociation(){
        $output = '
        {
            "applinks": {
                "apps": [],
                "details": [
                    {
                        "appID": "PUJYM5YS6T.net.bawes.plugn",
                        "paths": [ "/app/", "/deeplink/*"]
                    },
                ]
            }
        }';
        Yii::$app->response->content = $output;
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->statusCode = 200;
        Yii::$app->response->acceptMimeType = 'application/pkcs7-mime';
        Yii::$app->response->headers->set("content-type", "application/pkcs7-mime");

        return Yii::$app->response;
    }

}
