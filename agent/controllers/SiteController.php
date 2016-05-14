<?php
namespace agent\controllers;

use Yii;
use yii\web\Controller;
use agent\models\LoginForm;
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if(!Yii::$app->user->isGuest){
            return $this->redirect(['dashboard/index']);
        }

        return $this->render('index');
    }

    /**
     * Employer Registration Thank You Page
     */
    public function actionThanks(){
        return $this->render('thanks');
    }

    public function actionRegistration() {
        $model = new \employer\models\Employer();

        if ($model->load(Yii::$app->request->post())) {
            $model->employer_logo = UploadedFile::getInstance($model, 'employer_logo');

            if ($model->validate()) {
                if ($model->employer_logo) {
                    //file upload is valid - Upload file to amazon S3
                    $model->uploadLogo();
                }

                $model->signup();
                return $this->redirect(['thanks']);
            } else {
                foreach ($model->errors as $error => $errorText) {
                    Yii::$app->getSession()->setFlash('error', $errorText);
                }
            }
        }

        return $this->render('register', [
                    'model' => $model,
        ]);
    }

    /**
     * Email verification by clicking on link in email which includes the code that will verify
     * @param string $code Verification key that will verify your account
     * @param int $verify Employer ID to verify
     * @throws NotFoundHttpException if the code is invalid
     */
    public function actionEmailVerify($code, $verify) {
        //Code is his auth key, check if code is valid
        $employer = Employer::findOne(['employer_auth_key' => $code, 'employer_id' => (int) $verify]);
        if ($employer) {
            //If not verified
            if ($employer->employer_email_verification == Employer::EMAIL_NOT_VERIFIED) {
                //Verify this employers  email
                $employer->employer_email_verification = Employer::EMAIL_VERIFIED;
                $employer->save(false);

                //Log him in
                Yii::$app->user->login($employer, 0);
            }

            //Render thanks for verifying + Button to go to his portal
            return $this->render('verified');
        } else {
            //inserted code is invalid
            throw new BadRequestHttpException(Yii::t('register', 'Invalid email verification code'));
        }
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

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
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $employer = Employer::findOne([
                        'employer_email' => $model->email,
            ]);

            if ($employer) {
                //Check if this user sent an email in past few minutes (to limit email spam)
                $emailLimitDatetime = new \DateTime($employer->employer_limit_email);
                date_add($emailLimitDatetime, date_interval_create_from_date_string('4 minutes'));
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
                } else if ($model->sendEmail($employer)) {
                    Yii::$app->getSession()->setFlash('success', Yii::t('employer', 'Password reset link sent, please check your email for further instructions.'));

                    return $this->redirect(['login']);
                } else {
                    Yii::$app->getSession()->setFlash('error', Yii::t('employer', 'Sorry, we are unable to reset password for email provided.'));
                }
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('employer', 'New password was saved.'));

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
        $employer = Employer::findOne([
                    'employer_id' => (int) $id,
                    'employer_email' => $email,
        ]);

        if ($employer) {
            //Check if this user sent an email in past few minutes (to limit email spam)
            $emailLimitDatetime = new \DateTime($employer->employer_limit_email);
            date_add($emailLimitDatetime, date_interval_create_from_date_string('4 minutes'));
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

            } else if ($employer->employer_email_verification == Employer::EMAIL_NOT_VERIFIED) {
                $employer->sendVerificationEmail();
                Yii::$app->getSession()->setFlash('success', Yii::t('register', 'Please click on the link sent to you by email to verify your account'));
            }
        }

        return $this->redirect(['login']);
    }

}
