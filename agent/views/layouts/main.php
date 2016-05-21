<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Agent Panel',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Home', 'url' => ['/site/index']];
        $menuItems[] = ['label' => 'Sign up', 'url' => ['/site/registration']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => 'Dashboard', 'url' => ['/dashboard/index']];
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->agent_name . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>

        <div class='row'>
            <?php if(Yii::$app->user->isGuest){ echo $content; }else{ ?>
                <div class='col-md-3'>
                    <h3>Your Accounts</h3>

                    <ul class="nav nav-pills nav-stacked">
                        <?php
                        if($managedAccounts = Yii::$app->accountManager->managedAccounts){
                            foreach($managedAccounts as $account){?>
                                <li <?= $this->title==$account->user_name?" class='active'":"" ?>>
                                    <a href="<?= Url::to(['dashboard/manage' ,'accountName' => $account->user_name]) ?>">
                                        <?= Html::img($account->user_profile_pic, ['width'=>30, 'height'=>30]) ?>
                                        <?= $account->user_name ?>
                                    </a>
                                </li>
                            <?php
                        }}else echo "You're currently not assigned to any accounts";
                        ?>
                    </ul>

                </div>
                <div class='col-md-9'>
                    <?= $content ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= date('Y') ?></p>

        <p class="pull-right">A weekend project</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
