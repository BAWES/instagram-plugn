<?php

namespace agency\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * ActivityController
 */
class ActivityController extends Controller
{
    public $layout = 'account';

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
     * Display main view
     * @param string $accountId the account id we're looking to manage
     * @return mixed
     */
    public function actionList($accountId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $activities = $instagramAccount->getActivities()->with('agent')->all();
        $numActivities = count($activities);


        //Change View Displayed based on number of activities this account has
        $viewToDisplay = $numActivities > 0 ? 'index' : 'index-noactivity';

        return $this->render($viewToDisplay, [
            'account' => $instagramAccount,
            'activities' => $activities
        ]);
    }

}
