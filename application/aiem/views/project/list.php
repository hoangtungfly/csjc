<?php 
    use common\models\admin\SettingsMessageSearch;
?>

<div class="container company">
    <h1 class="text-center"><?=SettingsMessageSearch::t('news','project_list_title', 'PROJECTS')?></h1>
    <h2 class="text-center"><?=SettingsMessageSearch::t('news','project_list_description', 'Showcase of our work for clients')?></h2>
    <hr>
</div>
<?php 
    $len = count($listProject);
    if($len) {  
?>
<div class="container projectsList">
    <?php foreach($listProject as $item):
        ?>
    <div class="col-xs-4">
        <div class="item">
            <img class="img-responsive" src="<?=isset($item['image_main']) ? $item['image_main'] : ''?>">
            <div class="content">
                <h4><a href="<?=isset($item['link_main']) ? $item['link_main'] : ''?>"><?=isset($item['name_display'])? $item['name_display'] : ''?></a></h4>
                <p><?=isset($item['description']) ? $item['description'] : ''?></p>
                <a href="<?=isset($item['link_main'])? $item['link_main'] : ''?>" class="general" style="color: #333"><?=SettingsMessageSearch::t('common','button_moredetail', 'More detail')?></a>
            </div>
        </div>
    </div>
    <?php endforeach;?>
</div>
<?php }?>