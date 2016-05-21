<?php

/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>


<h1>Content here</h1>
<?php foreach($managedAccounts as $account){
    echo $account->user_name."<br/>";
}
?>
