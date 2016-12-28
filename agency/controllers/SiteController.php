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

use agency\models\LoginForm;
use agency\models\PasswordResetRequestForm;
use agency\models\ResetPasswordForm;
use common\models\Agency;

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
     * Instagram Authorization success handler
     *
     * @return mixed
     */
    public function onAuthSuccess($client)
    {
        //Client is an Instance of Instagram/OAuth2/BaseOAuth classes

        (new InstagramAuthHandler($client))->handle();
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['dashboard/index']);
    }

    public function actionRegistration() {
        $this->layout = 'signup';

        $model = new Agency();
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
     * @param int $verify Agency ID to verify
     * @throws NotFoundHttpException if the code is invalid
     */
    public function actionEmailVerify($code, $verify) {
        $this->layout = 'signup';

        //Code is his auth key, check if code is valid
        $agency = Agency::findOne(['agency_auth_key' => $code, 'agency_id' => (int) $verify]);
        if ($agency) {
            //If not verified
            if ($agency->agency_email_verified == Agency::EMAIL_NOT_VERIFIED) {
                //Verify this agency email
                $agency->agency_email_verified = Agency::EMAIL_VERIFIED;
                $agency->save(false);

                //Log him in
                Yii::$app->user->login($agency, 0);
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

    /**
     * Logs out the current user.
     * Starts by logging out of Instagram then redirects to log you out of Plugn Platform
     * @return mixed
     */
    public function actionInstagramLogout()
    {
        $this->layout = 'blank';
        $logoutUrl = Url::to(['site/logout']);

        /**
         * The following view file will display an iFrame which will log the user out of
         * Instagram then redirect to site/logout-real to process logging out from Plugn
         */
        return $this->render("instagram-logout", ['logoutUrl' => $logoutUrl]);
    }

    public function actionRequestPasswordReset() {
        $this->layout = 'signup';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $agency = Agency::findOne([
                        'agency_email' => $model->email,
            ]);

            if ($agency) {
                //Check if this user sent an email in past few minutes (to limit email spam)
                $emailLimitDatetime = new \DateTime($agency->agency_limit_email);
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
                } else if ($model->sendEmail($agency)) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('agency', 'Password reset link sent, please check your email for further instructions.'));

                    return $this->redirect(['login']);
                } else {
                    Yii::$app->getSession()->setFlash('error', Yii::t('agency', 'Sorry, we are unable to reset password for email provided.'));
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
            Yii::$app->getSession()->setFlash('success', Yii::t('agency', 'New password was saved.'));

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

        $agency = Agency::findOne([
                    'agency_id' => (int) $id,
                    'agency_email' => $email,
        ]);

        if ($agency) {
            //Check if this user sent an email in past few minutes (to limit email spam)
            $emailLimitDatetime = new \DateTime($agency->agency_limit_email);
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

            } else if ($agency->agency_email_verified == Agency::EMAIL_NOT_VERIFIED) {
                $agency->sendVerificationEmail();
                Yii::$app->getSession()->setFlash('success', Yii::t('register', 'Please click on the link sent to you by email to verify your account'));
            }
        }

        return $this->redirect(['login']);
    }


}
