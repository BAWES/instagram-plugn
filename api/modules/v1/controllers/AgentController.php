<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Agent controller
 *
 * An agent can be both an account owner and an account manager
 * This class will be to manage the agents profile changes and auth key generation
 */
class AgentController extends Controller
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
     * Return an overview of the users account details
     */
    public function actionDetails()
    {
        $isTrialActive = Yii::$app->user->identity->hasActiveTrial();
        $trialDaysLeft = Yii::$app->user->identity->agent_trial_days;
        $billingDaysLeft = Yii::$app->user->identity->getBillingDaysLeft();

        return [
            'accountStatus' => Yii::$app->user->identity->agent_status,
            'numberOfOwnedAccounts' => count(Yii::$app->ownedAccountManager->ownedAccounts),
            'ownedAccountLimit' => Yii::$app->user->identity->linkedAccountLimit,
            'trial' => [
                'isActive' => $isTrialActive,
                'daysLeft' => $trialDaysLeft,
            ],
            'billing' => [
                'isActive' => $billingDaysLeft?true:false,
                'daysLeft' => $billingDaysLeft,
            ]
        ];

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();
    }

    /**
     * Generate a single use Auth Key
     * @return array
     */
    public function actionGenerateAuthKey()
    {
        // Return a fresh and unique auth key
        return Yii::$app->user->identity->generateAuthKeyAndSave();

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();
    }


    /**
     * Allows logged in agent to unassign himself from an IG account hes been assigned to.
     * @param  integer $accountId
     * @return array
     */
    public function actionUnassign($accountId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        // Not allowed to remove yourself from managing your own account
        if($instagramAccount->agent_id == Yii::$app->user->identity->agent_id)
        {
            return [
                "operation" => "error",
                "message" => "You're the admin on this account. You may remove this account if you're no longer interested in managing it"
            ];
        }

        // Find the assignment and delete it
        $assignmentModel = \common\models\AgentAssignment::findOne([
            'agent_id' => Yii::$app->user->identity->agent_id,
            'user_id' => $instagramAccount->user_id
        ]);
        $assignmentModel->delete();

        return [
            "operation" => "success",
        ];

        // Check SQL Query Count and Duration
        return Yii::getLogger()->getDbProfiling();
    }


}
