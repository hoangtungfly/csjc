<?php

use application\aiem\assets\AppAsset;
use common\core\form\GlobalActiveForm;
use common\core\view\GlobalView;

/* @var $this GlobalView */
AppAsset::register($this);
$class = 'cms-index-index cms-home-demo-01 cms-page-1';
if($this->context->contact == 1) {
    $class = 'catalog-category-view categorypath-contact-us-html category-contact-us cms-page-1';
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="vi" xmlns="http://www.w3.org/1999/xhtml" prefix="og: http://ogp.me/ns#">
    <?= $this->render('partials/head') ?>
    <body class="<?=$class?>" itemscope=itemscope itemtype="http://schema.org/WebPage">

        <div class="overlay-bg" style="background:#fff;z-index:9999;position:fixed; width:120%; height:120%; overflow:hidden"></div>
        <?php $this->beginBody() ?>
            <?= $this->render('partials/header') ?>
            <?php echo $content ?>
            <?= $this->render('partials/footer') ?>
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
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
</html>
<?php $this->endPage() ?>