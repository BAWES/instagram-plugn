<?php
namespace frontend\controllers;

use Yii;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\components\AuthHandler;

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
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
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
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
        $instagram = Yii::$app->authClientCollection->clients['instagram'];

        //print_r($instagram->apiWithToken('35734335.a9d7f8a.1a5d6221613c40b6a1763c9c46fe3bc3' ,
        //          'users/self',
        //          'GET'));

        echo "<br/><br><br><br><br>";
        //Now test if this access token can expire, and respond to that as needed
        /**
         * - If user logs in for first time, his accesstoken is stored in the user table.
        - If access token is found to be expired/invalid (must check every instagram response for invalid access token), user will be logged out and prompted to log back in again. + his old auth records are deleted
        - When user logs in with IG + he already has an account, update his access token and create a new auth rule for him.
         */
        print_r($instagram->apiWithToken('35734335.a9d7f8a.1a5d6221613c40b6a1763c9c46fe3bc3' ,
                'users/self/media/recent',
                'GET',
                [
                    'count' => 2,
                ]));


        //BAWES ACCESS Token
        //1512951558.a9d7f8a.e6a6122d8a0a486ebb351b25c9f4ad86
        //KHALID ACCESS Token
        //35734335.a9d7f8a.1a5d6221613c40b6a1763c9c46fe3bc3

        return $this->render('index');
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

        return $this->render('login', [
            //'model' => $model,
        ]);

    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Authorization success handler
     *
     * @return mixed
     */
    public function onAuthSuccess($client)
    {
        Yii::error(print_r($client->accessToken, false));


        //Yii::error(print_r($client, false));

        //The following is whats inside $client
        /*
        'kotchuprik\\authclient\\Instagram_4e92fdc99bbdbf7ffd5c30393987fa08ca9f1f6e_token' => yii\authclient\OAuthToken#1
        (
            [tokenParamKey] => 'access_token'
            [tokenSecretParamKey] => 'oauth_token_secret'
            [createTimestamp] => 1461525528
            [yii\authclient\OAuthToken:_expireDurationParamKey] => null
            [yii\authclient\OAuthToken:_params] => [
                'access_token' => '1512951558.a9d7f8a.e6a6122d8a0a486ebb351b25c9f4ad86'
                'user' => [
                    'username' => 'bawestech'
                    'bio' => 'BAWES is a creative agency located in Kuwait. We specialize in working with advertising agencies to craft amazing digital work for major brands'
                    'website' => 'http://www.bawes.net'
                    'profile_picture' => 'https://igcdn-photos-f-a.akamaihd.net/hphotos-ak-xfp1/t51.2885-19/1169833_717533781633797_1900069462_a.jpg'
                    'full_name' => 'BAWES - Built Awesome'
                    'id' => '1512951558'
                ]
            ]
        )
        */


        //Yii::error(print_r($client->getUserAttributes()));

        //The following is whats inside $client->getUserAttributes()
        /*
        'kotchuprik\\authclient\\Instagram_4e92fdc99bbdbf7ffd5c30393987fa08ca9f1f6e_token' => yii\authclient\OAuthToken#1
            (
                [tokenParamKey] => 'access_token'
                [tokenSecretParamKey] => 'oauth_token_secret'
                [createTimestamp] => 1461525717
                [yii\authclient\OAuthToken:_expireDurationParamKey] => 'expires_in'
                [yii\authclient\OAuthToken:_params] => [
                    'access_token' => '1512951558.a9d7f8a.e6a6122d8a0a486ebb351b25c9f4ad86'
                    'user' => [
                        'username' => 'bawestech'
                        'bio' => 'BAWES is a creative agency located in Kuwait. We specialize in working with advertising agencies to craft amazing digital work for major brands'
                        'website' => 'http://www.bawes.net'
                        'profile_picture' => 'https://igcdn-photos-f-a.akamaihd.net/hphotos-ak-xfp1/t51.2885-19/1169833_717533781633797_1900069462_a.jpg'
                        'full_name' => 'BAWES - Built Awesome'
                        'id' => '1512951558'
                    ]
                ]
            )
        */


        //(new AuthHandler($client))->handle();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
