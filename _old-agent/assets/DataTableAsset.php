<?php

namespace agent\assets;

use yii\web\AssetBundle;

/**
 * This is the AssetBundle containing the sweet alert files
 * @author Khalid Al-Mutawa <khalid@bawes.net>
 */
class DataTableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    //CSS will be added before closing </head> tag
    public $css = [
        'css/lib/datatables-net/datatables.min.css',
    ];

    //JS will be added before closing </body> tag
    public $js = [
        'js/lib/datatables-net/datatables.min.js',
    ];

    public $depends = [
        'agent\assets\TemplateAsset',
    ];
}
