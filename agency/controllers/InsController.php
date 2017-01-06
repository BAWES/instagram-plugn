<?php
namespace agency\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Agency;

/**
 * 2Checkout INS controller
 */
class InsController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // Only Accept POST requests
                    'notification' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Accepts and processes all 2Checkout INS Notifications
     *
     * @return mixed
     */
    public function actionNotification()
    {
        // Redirect to Instagram management page
        return $this->redirect(['instagram/index']);
    }

}
