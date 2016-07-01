<?php

namespace agent\assets;

use yii\web\AssetBundle;

/**
 * This is the AssetBundle containing C3 Charts Files
 * @author Khalid Al-Mutawa <khalid@bawes.net>
 */
class ChartC3Asset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    //CSS will be added before closing </head> tag
    public $css = [
        'css/lib/charts-c3js/c3.min.css',
    ];

    //JS will be added before closing </body> tag
    public $js = [
        'js/lib/d3/d3.min.js',
        'js/lib/charts-c3js/c3.min.js'
    ];

    public $depends = [
        'agent\assets\TemplateAsset',
    ];
}
