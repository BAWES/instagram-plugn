<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $logoutUrl string */

$this->title = "Logging out";

$this->registerJs("
$('#iglog').on('load', function(){
    window.location='".$logoutUrl."';
});
");
?>

<h1>Logging out..</h1>

<div style="display:none">
    <iframe id="iglog" src="https://instagram.com/accounts/logout/" width="0" height="0"></iframe>
</div>
