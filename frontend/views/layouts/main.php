<?php
use frontend\assets\AppAsset;

/* @var $this \yii\web\View */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" ng-app="app">
<head>
    <base href="<?=HOST_PUBLIC?>/"  >
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>My Angular Yii Application</title>
 <base href="/" />
<?php $this->head() ?>

<script>
    paceOptions = {ajax: {trackMethods: ['GET', 'POST']}};
</script>
</head>
<body ng-controller="MainController">
<?php $this->beginBody() ?>
<div class="wrap">
    <nav class="navbar-inverse navbar-fixed-top navbar" role="navigation" bs-navbar>
        <div class="container">
            <div class="navbar-header">
                <button ng-init="navCollapsed = true" ng-click="navCollapsed = !navCollapsed" type="button" class="navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span></button>
                <a class="navbar-brand" href="">{{config.twitter_link}}</a>
            </div>
            <div ng-class="!navCollapsed && 'in'" ng-click="navCollapsed=true" class="collapse navbar-collapse" >
                <ul class="navbar-nav navbar-right nav">
                    <li data-match-route="<?=$this->createUrl('/')?>">
                        <a href="<?=$this->createUrl('/')?>">Home</a>
                    </li>
                    <li data-match-route="<?=$this->createUrl('/about')?>">
                        <a href="<?=$this->createUrl('/about')?>">About</a>
                    </li>
                    <li data-match-route="<?=$this->createUrl('/contact')?>">
                        <a href="<?=$this->createUrl('/contact')?>">Contact</a>
                    </li>
                    <li data-match-route="/dashboard" ng-show="loggedIn()" class="ng-hide">
                        <a href="<?=$this->createUrl('/dashboard')?>">Dashboard</a>
                    </li>
                    <li ng-show="loggedIn()" ng-click="logout()"  class="ng-hide">
                        <a href="">Logout</a>
                    </li>
                    <li data-match-route="/login" ng-hide="loggedIn()">
                        <a href="<?=$this->createUrl('/login')?>">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div ng-view>
        </div>
    </div>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <a href="http://blog.neattutorials.com">Neat Tutorials</a> <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?> <?= Yii::getVersion() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>