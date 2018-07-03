<?php 
?>

<div class="container company">
    <h1 class="text-center"><?=isset($project['name_display']) ? $project['name_display'] : ''?></h1>
    <h2 class="text-center"><?=isset($project['description']) ? $project['description'] : ''?></h2>
    <hr>
</div>
<div class="container projectDetail">
    <div class="col-xs-12">
        <img class="img-responsive center-block" src="<?=isset($project['image_main'])? $project['image_main'] : ''?>">
        <div class="content">
            <?=isset($project['content']) ? $project['content'] : ''?>
        </div>
    </div>
    <hr>
</div>
<?=$this->render('related', ['listProject' => $listProject])?>