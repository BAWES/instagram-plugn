<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\TemplateAsset;
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

	<link href="img/favicon.144x144.png" rel="apple-touch-icon" type="image/png" sizes="144x144">
	<link href="img/favicon.114x114.png" rel="apple-touch-icon" type="image/png" sizes="114x114">
	<link href="img/favicon.72x72.png" rel="apple-touch-icon" type="image/png" sizes="72x72">
	<link href="img/favicon.57x57.png" rel="apple-touch-icon" type="image/png">
	<link href="img/favicon.png" rel="icon" type="image/png">
	<link href="img/favicon.ico" rel="shortcut icon">

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
                                <img src="img/instagram-account/account1.jpg" alt="">
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
	<nav class="side-menu side-menu-big-icon">
	    <ul class="side-menu-list">
	        <li class="opened">
	            <a href="pn-agent-layout.html">
	                <i class="font-icon font-icon-home"></i>
	                <span class="lbl">Overview</span>
	            </a>
	        </li>
	        <li>
	            <a href="pn-agent-layout2.html" class="label-right">
	                <img src='img/instagram-account/account1.jpg' width=32 height=32 style='width:32px; height:32px; margin-bottom:5px;'/>
	                <span class="lbl">@khalidmnet</span>
	                <span class="label label-custom label-pill label-danger">23</span>
	            </a>
	        </li>
	        <li>
	            <a href="pn-agent-layout2.html" class="label-right">
	                <img src='img/instagram-account/account2.jpg' width=32 height=32 style='width:32px; height:32px; margin-bottom:5px;'/>
	                <span class="lbl">@bawestech</span>
	            </a>
	        </li>
	        <li>
	            <a href="pn-agent-layout2.html"  class="label-right">
	                <img src='img/instagram-account/account3.jpg' width=32 height=32 style='width:32px; height:32px; margin-bottom:5px;'/>
	                <span class="lbl">@studenthubco</span>
	                <span class="label label-custom label-pill label-danger">5</span>
	            </a>
	        </li>
	        <li style='background-color:#EEE;'>
	            <a href="pn-agent-layout.html">
	                <i class="icon fa fa-plus"></i>
	                <span class="lbl">Add Instagram Account</span>
	            </a>
	        </li>
	    </ul>
	    <section>
	        <header class="side-menu-title">Mobile Apps [Soon]</header>
	        <ul class="side-menu-list">
	            <li>
	                <a href="#">
	                    <img src='<?= Url::to('@web/img/applecomingsoon.png') ?>' alt='Soon on App Store' style='width:90px'/>
	                    <img src='<?= Url::to('@web/img/playcomingsoon.png') ?>' alt='Soon on Play Store' style='width:90px'/>
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