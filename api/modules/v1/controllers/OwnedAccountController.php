<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Owned Account controller
 */
class OwnedAccountController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [],
            ],
        ];

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * Return a List of Accounts Owned by User
     */
    public function actionList()
    {
        // Get cached owned accounts list from owned account manager component
        $ownedAccounts = Yii::$app->ownedAccountManager->ownedAccounts;

        return $ownedAccounts;
    }

    /**
     * Remove Account Ownership from an Agent
     * @param  integer $accountId
     * @return array
     */
    public function actionRemoveAccount($accountId)
    {
        // Get Instagram account from Account Manager component
        $instagramAccount = Yii::$app->ownedAccountManager->getOwnedAccount($accountId);

        // Remove the agent from this Instagram account
        $instagramAccount->agent_id = null;
        // Set account as Inactive to stop crawling data & stop deducting trial days
        $instagramAccount->user_status = \common\models\InstagramUser::STATUS_INACTIVE;
        $instagramAccount->save(false);

        return [
            "operation" => "success",
        ];

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();
    }


}
