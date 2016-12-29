<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $redirectUrl string */

$this->title = "Redirecting to Instagram";

$this->registerJs("
$('#iglog').on('load', function(){
    window.location='".$redirectUrl."';
});
");
?>

<h1><i class="fa fa-refresh fa-spin  fa-fw"></i> Redirecting to Instagram..</h1>

<div style="display:none">
    <iframe id="iglog" src="https://instagram.com/accounts/logout/" width="0" height="0"></iframe>
</div>
