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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'remove' => ['POST'],
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

    /**
     * Default Action
     * Either takes you to your top managed account or to the page to add account
     */
    public function actionIndex()
    {
        $managedAccounts = Yii::$app->accountManager->managedAccounts;

        if(isset($managedAccounts[0])){
            return $this->redirect(['agent/list' ,'accountId' => $managedAccounts[0]->user_id]);
        }
        return $this->redirect(['add-account']);
    }

    /**
     * Displays guide for user on how to add an account
     */
    public function actionAddAccount()
    {
        return $this->render('addAccount',[]);
    }

    /**
     * Remove an Instagram account from the agency
     * @param integer $id the instagram account id
     * @return mixed
     */
    public function actionRemove($id)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($id);

        // Remove the agency from this Instagram account
        $instagramAccount->agency_id = null;
        // Set account as Inactive to stop crawling data & stop deducting trial days
        $instagramAccount->user_status = \common\models\InstagramUser::STATUS_INACTIVE;
        $instagramAccount->save(false);

        return $this->redirect(['index']);
    }


}
