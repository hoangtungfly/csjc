<?php
use frontend\assets\AppErrorAsset;
AppErrorAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if lt IE 9]> <html class="no-js lt-ie"> <![endif]-->
<!--[if IE 9]> <html class="no-js lt-ie9"> <![endif]-->
<html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="description" content="Ajantha" />
    <meta name="keywords" content="Ajantha" />  
    <link rel="shortcut icon" href="images/favicon.ico">
    <title>Error 404</title>
    <?php $this->head(); ?>
</head>

    <body>
        <?php $this->beginBody() ?>
        <div class="container">
            <div class="content row">
                <?= $content; ?>
            </div>
        </div>
        <?= $this->render('footer'); ?>
        <?php $this->endBody() ?>
    </body>
    
    
</html>
<?php $this->endPage() ?>