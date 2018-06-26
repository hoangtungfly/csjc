<?php

use backend\assets\BackendAsset;
use common\core\form\GlobalActiveForm;
use common\core\widgets\Flashes;
use yii\web\View;

/* @var $this View */
/* @var $content string */

BackendAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= app()->language ?>">
    <?=$this->render("partial/head")?>
    <body class="no-skin">
        <?php $this->beginBody() ?>
        <?php echo $this->render("partial/header")?>
        <div class="main-container" id="main-container">
            <div class="main-content">
                <div class="main-content-inner">
                    <?php echo $this->render("partial/left")?>
                    <div class="page-content" id="main_parent">
                        <div id="main_content">
                            <?php echo $content?>
                        </div>
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->
            <?php echo $this->render("partial/footer")?>
            <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div>
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
        <?php 
            echo Flashes::widget([]);
        ?>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>