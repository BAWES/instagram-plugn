<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use agent\assets\TemplateAsset;
use common\widgets\Alert;

TemplateAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head lang="<?= Yii::$app->language ?>">
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
    <?= Html::csrfMetaTags() ?>

	<title><?= Html::encode($this->title) ?></title>

	<link rel="apple-touch-icon" sizes="180x180" href="<?= Url::to('@web/apple-touch-icon.png') ?>">
	<link rel="icon" type="image/png" href="<?= Url::to('@web/favicon-32x32.png') ?>" sizes="32x32">
	<link rel="icon" type="image/png" href="<?= Url::to('@web/favicon-16x16.png') ?>" sizes="16x16">
	<link rel="manifest" href="<?= Url::to('@web/manifest.json') ?>">
	<link rel="mask-icon" href="<?= Url::to('@web/safari-pinned-tab.svg') ?>" color="#5bbad5">
	<meta name="theme-color" content="#ffffff">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

	<div class="page-center">
		<div class="page-center-in">
			<div class="container-fluid">

				<?= Alert::widget() ?>
		        <?= $content ?>

			</div>
		</div>
	</div><!--.page-center-->


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
