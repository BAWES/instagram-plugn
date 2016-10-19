<?php

namespace agent\api\v1\controllers;

use yii\web\Controller;

/**
 * Default controller for the `v1` module
 */
class DefaultController extends Controller
{
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = "/signup";
        return $this->render('index');
    }
}
