<?php

use application\directory\assets\AppAsset;
use common\core\form\GlobalActiveForm;
use common\core\view\GlobalView;

/* @var $this GlobalView */
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html ng-app="app" lang="vi" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
    <?= $this->render('partials/head') ?>
    <body>
        <?php $this->beginBody() ?>
        
        <ng-view></ng-view>

        <?php
        GlobalActiveForm::begin([
            'id' => 'user-default-form',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'validateOnChange' => false,
            'validateOnSubmit' => true,
            'validateOnBlur' => false,
        ]);
        ?>
        <?php GlobalActiveForm::end(); ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>