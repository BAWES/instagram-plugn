<?php

namespace agent\api\v1;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'agent\api\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Disable session and login url
        // so the api is stateless and user authentication status
        // will NOT be persisted across requests using sessions
        \Yii::$app->user->enableSession = false;
        \Yii::$app->user->loginUrl = null;

        \Yii::$app->getUrlManager()->addRules([
            // rule declarations here
            'v1/authorize/<authclient:(google|live|slack)>' => 'v1/authorize/auth',
        ], false); //false to overwrite url rules, true to append to existing rules
    }

    /**
     * Method called during parent applications bootstrapping process
     * @param  yii\web\Application $app Agent application instance
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            // rule declarations here
            'v1/authorize/<authclient:(google|live|slack)>' => 'v1/authorize/auth',
        ], false); //false to overwrite url rules, true to append to existing rules
    }

}
