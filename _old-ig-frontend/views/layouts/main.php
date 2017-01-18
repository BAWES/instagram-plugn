<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\TemplateAsset;
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
<body class="with-side-menu">
<?php $this->beginBody() ?>

    <header class="site-header">
        <div class="container-fluid">
            <a href="#" class="site-logo">
                <img class="hidden-md-down" src="<?= Url::to('@web/img/plugn-logo.png') ?>" alt="">
                <img class="hidden-lg-up" src="<?= Url::to('@web/img/plugn-logo-mob.png') ?>" alt="">
            </a>
            <button class="hamburger hamburger--htla">
                <span>toggle menu</span>
            </button>
            <div class="site-header-content">
                <div class="site-header-content-in">
                    <div class="site-header-shown">
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
                                <img src="<?= Yii::$app->user->identity->user_profile_pic ?>" alt="">
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd-user-menu">
                                <?php /*
                                <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-user"></span>Profile</a>
                                <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-cog"></span>Settings</a>
                                <a class="dropdown-item" href="#"><span class="font-icon glyphicon glyphicon-question-sign"></span>Help</a>
                                <div class="dropdown-divider"></div>
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

    <div class="mobile-menu-left-overlay"></div>
    <nav class="side-menu">
        <div class="side-menu-avatar">
            <div class="avatar-preview avatar-preview-100">
                <img src="<?= Yii::$app->user->identity->user_profile_pic ?>" alt="">
                <span style='color:#343434; font-weight:600'>@<?= Yii::$app->user->identity->user_name ?></span>
            </div>
        </div>
        <ul class="side-menu-list">
            <li class="blue <?= Yii::$app->controller->id=="agent"?"opened":"" ?>">
                <a href="<?= Url::to(['agent/index']) ?>">
                    <i class="font-icon font-icon-users"></i>
                    <span class="lbl">Agents</span>
                </a>
            </li>
            <li class="pink-red <?= Yii::$app->controller->id=="activity"?"opened":"" ?>">
                <a href="<?= Url::to(['activity/index']) ?>">
                    <i class="font-icon font-icon-zigzag"></i>
                    <span class="lbl">Activity</span>
                </a>
            </li>
            <li class="aquamarine <?= Yii::$app->controller->id=="billing"?"opened":"" ?>">
                <a href="<?= Url::to(['billing/index']) ?>">
                    <i class="font-icon font-icon-build"></i>
                    <span class="lbl">Billing</span>
                </a>
            </li>
            <li class="blue-sky <?= Yii::$app->controller->id=="help"?"opened":"" ?>">
                <a href="<?= Url::to(['help/index']) ?>">
                    <i class="font-icon font-icon-question"></i>
                    <span class="lbl">Help</span>
                </a>
            </li>
        </ul>
        <section>
            <header class="side-menu-title">Agent Portal</header>
            <ul class="side-menu-list">
                <li>
                    <a href="<?= Yii::$app->urlManagerAgent->createUrl('site/index') ?>" target='_blank'>
                        <i class="font-icon font-icon-user"></i>
                        Switch to agent portal
                    </a>
                </li>
            </ul>
        </section>
        <section>
            <header class="side-menu-title">Mobile Apps [Soon]</header>
            <ul class="side-menu-list">
                <li>
                    <a href="#" style='padding-left:22px'>
                        <img src="<?= Url::to('@web/img/applecomingsoon.png') ?>" alt="Soon on App Store" style="width:88px">
                        <img src="<?= Url::to('@web/img/playcomingsoon.png') ?>" alt="Soon on Play Store" style="width:88px">
                    </a>
                </li>
            </ul>
        </section>
    </nav><!--.side-menu-->

    <div class="page-content">

		<?= Alert::widget() ?>
        <?= $content ?>


	</div><!--.page-content-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>