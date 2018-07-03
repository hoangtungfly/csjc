<?php 
use common\models\admin\SettingsMessageSearch;
?>
<?php if($listNews):?>
<div class="container projectsList corresponding">
    <h2 class="text-center"><?= SettingsMessageSearch::t('news','releated_news', 'TIN TỨC LIÊN QUAN')?></h2>
     <?php foreach($listNews as $key => $news) { ?>
    <div class="col-xs-4">
        <div class="item">
            <img class="img-responsive" src="<?=$news['image_main']?>">
            <div class="content">
                <h4><?=$news['title']?></h4>
                <p><?=$news['description']?></p>
                <a class="general" href="<?=$news['link_main']?>"><?=SettingsMessageSearch::t('common','button_viewmore', 'View more')?></a>
            </div>
        </div>
    </div>
     <?php } ?>
</div>

<?php endif;?>