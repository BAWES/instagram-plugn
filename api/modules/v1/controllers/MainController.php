<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Main controller is an example of what a typical controller should look like
 */
class MainController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['bearerAuth'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    /**
     * Return a List of Accounts Managed by User
     */
    public function actionManagedAccounts()
    {
        $managedAccounts = Yii::$app->accountManager->managedAccounts;

        return $managedAccounts;
    }
}
