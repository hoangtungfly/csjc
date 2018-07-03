<?php 
?>

<div class="container company">
    <h1 class="text-center"><?=$news['name_display']?></h1>
    <h2 class="text-center"><?=$news['description']?></h2>
    <hr>
</div>
<div class="container projectDetail">
    <div class="col-xs-12">
        <img class="img-responsive center-block" src="<?=$news['image_main']?>">
        <div class="content">
            <?=$news['content']?>
        </div>
    </div>
    <hr>
</div>
<div class="container projectsList corresponding">
    <h2 class="text-center"><?= common\models\admin\SettingsMessageSearch::t('news','releated_news', 'TIN TỨC LIÊN QUAN')?></h2>
    <div class="col-xs-4">
        <div class="item">
            <img class="img-responsive" src="<?=$news['image_main']?>">
            <div class="content">
                <h4>Project title</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <a class="general" href="#">More detail ></a>
            </div>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="item">
            <img class="img-responsive" src="<?=$news['image_main']?>">
            <div class="content">
                <h4>Project title</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <a class="general" href="#">More detail ></a>
            </div>
        </div>
    </div>
    <div class="col-xs-4">
        <div class="item">
            <img class="img-responsive" src="<?=$news['image_main']?>">
            <div class="content">
                <h4>Project title</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <a class="general" href="#">More detail ></a>
            </div>
        </div>
    </div>
</div>