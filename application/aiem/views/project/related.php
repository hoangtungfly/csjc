<?php 
use common\models\admin\SettingsMessageSearch;
?>
<?php if($listProject):?>
<div class="container projectsList corresponding">
    <h2 class="text-center"><?= SettingsMessageSearch::t('project','releated_project', 'PROJECT LIÊN QUAN')?></h2>
     <?php foreach($listProject as $key => $project) { ?>
    <div class="col-xs-4">
        <div class="item">
            <img class="img-responsive" src="<?=isset($project['image_main']) ? $project['image_main'] : ''?>">
            <div class="content">
                <h4><?=isset($project['name']) ? $project['name'] : ''?></h4>
                <p><?=isset($project['description']) ? $project['description'] : ''?></p>
                <a class="general" href="<?=isset($project['image_main']) ? $project['image_main'] : ''?>"><?=SettingsMessageSearch::t('common','button_viewmore', 'View more')?></a>
            </div>
        </div>
    </div>
     <?php } ?>
</div>

<?php endif;?>