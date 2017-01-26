<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Profile controller
 */
class ProfileController extends Controller
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


}
