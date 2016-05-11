<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\Media;

class MediaController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * List all media this user owns
     */
    public function actionIndex()
    {
        $userMedia = Media::find()
            ->where(['user_id' => Yii::$app->user->identity->user_id])
            ->orderBy('media_created_datetime DESC')
            ->all();

        echo Yii::$app->user->identity->user_id;

        return $this->render('index', ['userMedia' => $userMedia]);
    }

}
