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

        // initialize the module with the configuration loaded from config.php
        \Yii::configure($this, require(__DIR__ . '/config.php'));

    }

    /**
     * Method called during parent applications bootstrapping process
     * @param  yii\web\Application $app Agent application instance
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            // rule declarations here
        ], false); //false to overwrite, true to append
    }

}
