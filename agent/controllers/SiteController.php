<?php
namespace agent\controllers;

use Yii;
use yii\web\Controller;
use agent\components\authhandlers\GoogleAuthHandler;
use agent\components\authhandlers\LiveAuthHandler;
use agent\components\authhandlers\SlackAuthHandler;
use agent\models\LoginForm;
use agent\models\PasswordResetRequestForm;
use agent\models\ResetPasswordForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Agent;

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
                'only' => ['logout', 'registration'],
                'rules' => [
                    [
                        'actions' => ['registration'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
            ";
        }else $script = "Unable to login.";


        /**
         * Redirect with values stored in localstorage
         */
         Yii::$app->response->content = $script;
         return Yii::$app->response;
    }

    /**
     * Redirects to Ionic2 Agent Panel
     */
    public function actionIndex()
    {
        return $this->redirect("https://agent.plugn.io/app");
    }

    public function actionRegistration() {
        $this->layout = 'signup';

        $model = new Agent();
        $model->scenario = "manualSignup";

        if ($model->load(Yii::$app->request->post()))
        {
            if ($model->signup())
            {
                Yii::$app->session->setFlash('success', "[Thanks, you are almost done] Please click on the link sent to you by email to verify your account");
                return $this->redirect(['index']);
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Email verification by clicking on link in email which includes the code that will verify
     * @param string $code Verification key that will verify your account
     * @param int $verify Agent ID to verify
     * @throws NotFoundHttpException if the code is invalid
     */
    public function actionEmailVerify($code, $verify) {
        $this->layout = 'signup';

        //Code is his auth key, check if code is valid
        $agent = Agent::findOne(['agent_auth_key' => $code, 'agent_id' => (int) $verify]);
        if ($agent) {
            //If not verified
            if ($agent->agent_email_verified == Agent::EMAIL_NOT_VERIFIED) {
                //Verify this agents  email
                $agent->agent_email_verified = Agent::EMAIL_VERIFIED;
                $agent->save(false);

                //Log him in
                Yii::$app->user->login($agent, 0);
            }

            //Render thanks for verifying + Button to go to his portal
            Yii::$app->getSession()->setFlash('success', '[You have verified your email] You may now use Plugn to manage your accounts');

            return $this->redirect(['dashboard/index']);
        } else {
            //inserted code is invalid
            throw new BadRequestHttpException(Yii::t('register', 'Invalid email verification code'));
        }
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'signup';

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['dashboard/index']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRequestPasswordReset() {
        $this->layout = 'signup';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $agent = Agent::findOne([
                        'agent_email' => $model->email,
            ]);

            if ($agent) {
                //Check if this user sent an email in past few minutes (to limit email spam)
                $emailLimitDatetime = new \DateTime($agent->agent_limit_email);
                date_add($emailLimitDatetime, date_interval_create_from_date_string('2 minutes'));
                $currentDatetime = new \DateTime();

                if ($currentDatetime < $emailLimitDatetime) {
                    $difference = $currentDatetime->diff($emailLimitDatetime);
                    $minuteDifference = (int) $difference->i;
                    $secondDifference = (int) $difference->s;

                    $warningMessage = Yii::t('app', "Email was sent previously, you may request another one in {numMinutes, number} minutes and {numSeconds, number} seconds", [
                                'numMinutes' => $minuteDifference,
                                'numSeconds' => $secondDifference,
                    ]);

                    Yii::$app->getSession()->setFlash('warning', $warningMessage);
                } else if ($model->sendEmail($agent)) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('agent', 'Password reset link sent, please check your email for further instructions.'));

                    return $this->redirect(['login']);
                } else {
                    Yii::$app->getSession()->setFlash('error', Yii::t('agent', 'Sorry, we are unable to reset password for email provided.'));
                }
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    public function actionResetPassword($token) {
        $this->layout = 'signup';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('agent', 'New password was saved.'));

            return $this->redirect(['login']);
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    /**
     * Resend verification email
     * @param int $id the id of the user
     * @param string $email the email of the user
     */
    public function actionResendVerification($id, $email) {
        $this->layout = 'signup';

        $agent = Agent::findOne([
                    'agent_id' => (int) $id,
                    'agent_email' => $email,
        ]);

        if ($agent) {
            //Check if this user sent an email in past few minutes (to limit email spam)
            $emailLimitDatetime = new \DateTime($agent->agent_limit_email);
            date_add($emailLimitDatetime, date_interval_create_from_date_string('2 minutes'));
            $currentDatetime = new \DateTime();

            if ($currentDatetime < $emailLimitDatetime) {
                $difference = $currentDatetime->diff($emailLimitDatetime);
                $minuteDifference = (int) $difference->i;
                $secondDifference = (int) $difference->s;

                $warningMessage = Yii::t('app', "Email was sent previously, you may request another one in {numMinutes, number} minutes and {numSeconds, number} seconds", [
                            'numMinutes' => $minuteDifference,
                            'numSeconds' => $secondDifference,
                ]);

                Yii::$app->getSession()->setFlash('warning', $warningMessage);

            } else if ($agent->agent_email_verified == Agent::EMAIL_NOT_VERIFIED) {
                $agent->sendVerificationEmail();
                Yii::$app->getSession()->setFlash('success', Yii::t('register', 'Please click on the link sent to you by email to verify your account'));
            }
        }

        return $this->redirect(['login']);
    }

}
