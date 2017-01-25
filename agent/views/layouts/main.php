<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use agent\assets\TemplateAsset;
use common\widgets\Alert;

TemplateAsset::register($this);

//Google Analytics JS
$analytics = "
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-80731173-1', 'auto');
ga('send', 'pageview');
";
$this->registerJs($analytics);

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

    <header class="site-header">
        <div class="container-fluid">
            <a href="<?= Url::home(); ?>" class="site-logo">
                <img class="hidden-md-down" src="<?= Url::to('@web/img/plugn-logo.png') ?>" alt="">
                <img class="hidden-lg-up" src="<?= Url::to('@web/img/plugn-logo-mob.png') ?>" alt="">
            </a>
            <div class="site-header-content">
                <div class="site-header-content-in">
                    <div class="site-header-shown">
						<!-- App Download Links -->
                        <div class="dropdown dropdown-lang">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-mobile fa-2x" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div>
									<a class="dropdown-item" target='_blank' href="https://itunes.apple.com/gr/app/plugn-for-instagram/id1196833693?mt=8"><span class="fa fa-apple"></span> iOS Application</a>
                                    <a class="dropdown-item" target='_blank' href="https://play.google.com/store/apps/details?id=net.bawes.plugn"><span class="fa fa-android"></span> Android Application</a>
									<a class="dropdown-item" target='_blank' href="https://agent.plugn.io/app"><span class="fa fa-chrome"></span> Web Application</a>
                                </div>
                            </div>
                        </div>
						<!-- End App Download Links -->
                        <?php /*
                        <div class="dropdown dropdown-lang">
                            <button class="dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="flag-icon flag-icon-us"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-menu-col">
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-ru"></span>Русский</a>
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-de"></span>Deutsch</a>
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-it"></span>Italiano</a>
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-es"></span>Español</a>
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-pl"></span>Polski</a>
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-li"></span>Lietuviu</a>
                                </div>
                                <div class="dropdown-menu-col">
                                    <a class="dropdown-item current" href="#"><span class="flag-icon flag-icon-us"></span>English</a>
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-fr"></span>Français</a>
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-by"></span>Беларускi</a>
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-ua"></span>Українська</a>
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-cz"></span>Česky</a>
                                    <a class="dropdown-item" href="#"><span class="flag-icon flag-icon-ch"></span>中國</a>
                                </div>
                            </div>
                        </div>
                        */
                        ?>

                        <div class="dropdown user-menu">
                            <button class="dropdown-toggle" id="dd-user-menu" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="<?= Url::to('@web/img/avatar-2-64.png') ?>" alt="">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
                                <?php /*
                                <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-user"></span>Profile</a>

                                <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-question-sign"></span>Help</a>

                                */
                                ?>

                                <a class="dropdown-item" href="<?= Url::to(['site/logout']) ?>" data-method= 'post'>
                                    <span class="font-icon glyphicon glyphicon-log-out"></span>Logout
                                </a>
                            </div>
                        </div>

                    </div><!--.site-header-shown-->

                </div><!--site-header-content-in-->
            </div><!--.site-header-content-->
        </div><!--.container-fluid-->
    </header><!--.site-header-->


    <div class="page-content" style='padding: 107px 0 0 0;'>

		<?= Alert::widget() ?>
        <?= $content ?>


	</div><!--.page-content-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
