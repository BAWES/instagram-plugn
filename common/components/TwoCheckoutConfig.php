<?php
namespace common\components;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Bootstrap class that loads 2checkout params based on whether the sandbox is active or not
 */
class TwoCheckoutConfig implements BootstrapInterface {

    /**
    * Bootstrap method to be called during application bootstrap stage.
    * Loads all the settings into the Yii::$app->params array
    * @param Application $app the application currently running
    */
    public function bootstrap($app)
    {

        // Setup 2Checkout Config based on Sandbox or Not

        if(Yii::$app->params['2co.isSandbox']){
            // Setup Sandbox Config
            Yii::$app->params['2co.environment'] = Yii::$app->params['2co.sandbox.environment'];
            Yii::$app->params['2co.privateKey'] = Yii::$app->params['2co.sandbox.privateKey'];
            Yii::$app->params['2co.publishableKey'] = Yii::$app->params['2co.sandbox.publishableKey'];
            Yii::$app->params['2co.sellerId'] = Yii::$app->params['2co.sandbox.sellerId'];
            Yii::$app->params['2co.username'] = Yii::$app->params['2co.sandbox.username'];
            Yii::$app->params['2co.password'] = Yii::$app->params['2co.sandbox.password'];
            Yii::$app->params['2co.verifySSL'] = Yii::$app->params['2co.sandbox.verifySSL'];
        }else{
            // Setup Production Config
            Yii::$app->params['2co.environment'] = Yii::$app->params['2co.live.environment'];
            Yii::$app->params['2co.privateKey'] = Yii::$app->params['2co.live.privateKey'];
            Yii::$app->params['2co.publishableKey'] = Yii::$app->params['2co.live.publishableKey'];
            Yii::$app->params['2co.sellerId'] = Yii::$app->params['2co.live.sellerId'];
            Yii::$app->params['2co.username'] = Yii::$app->params['2co.live.username'];
            Yii::$app->params['2co.password'] = Yii::$app->params['2co.live.password'];
            Yii::$app->params['2co.verifySSL'] = Yii::$app->params['2co.live.verifySSL'];
        }

    }

}
