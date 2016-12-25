<?php

namespace agency\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ActivityController
 */
class ActivityController extends Controller
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
        ];
    }

    /**
     * Display main view
     * @return mixed
     */
    public function actionIndex()
    {
        $activities = Yii::$app->user->identity->getActivities()->with('agent')->all();
        $numActivities = count($activities);


        //Change View Displayed based on number of activities this account has
        $viewToDisplay = $numActivities > 0 ? 'index' : 'index-noactivity';

        return $this->render($viewToDisplay, [
            'activities' => $activities
        ]);
    }

}
