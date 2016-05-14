<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
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
        $userMedia = Yii::$app->user->identity->media;

        return $this->render('index', ['userMedia' => $userMedia]);
    }

    /**
     * Displays a single Media model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
            'media' => $this->findModel($id),
        ]);
    }


    /**
     * Finds the Media model based on its primary key value.
     * Media must belong to this users account for it to be found
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Job the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        $condition = [
            'media_id' => (int) $id,
            'user_id' => Yii::$app->user->identity->user_id,
        ];

        if (($model = Media::findOne($condition)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
