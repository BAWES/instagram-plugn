<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * This is the AssetBundle containing the sweet alert files
 * @author Khalid Al-Mutawa <khalid@bawes.net>
 */
class SweetAlertAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    //CSS will be added before closing </head> tag
    public $css = [
        'css/lib/bootstrap-sweetalert/sweetalert.css',
    ];

    //JS will be added before closing </body> tag
    public $js = [
        'js/lib/bootstrap-sweetalert/sweetalert.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
