<?php

namespace agent\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use common\models\InstagramUser;
use common\models\Media;

class MediaController extends \yii\web\Controller {

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
                        'roles' => ['@'], //only allow authenticated users to job actions
                    ],
                ],
            ],
        ];
    }


    /**
     * Manage an Instagram Account in Media View
     * @param string $accountName the account name we're looking to manage
     */
    public function actionList($accountId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);
        $media = $instagramAccount->media;


        return $this->render('list',[
            'account' => $instagramAccount,
            'media' => $media
        ]);
    }

    /**
     * View conversation with user who'se userId is provided
     * @param integer $accountId the instagram account id we're managing
     * @param integer $mediaId the media id we're interested in
     */
    public function actionView($accountId, $mediaId)
    {
        $instagramAccount = Yii::$app->accountManager->getManagedAccount($accountId);

        $media = Media::find()->where([
            'media_id' => (int) $mediaId,
            'user_id' => $instagramAccount->user_id,
        ])->with('comments')->one();

        return $this->render('view',[
            'account' => $instagramAccount,
            'media' => $media,
        ]);
    }


}
