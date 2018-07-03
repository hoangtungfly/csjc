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
<?=$this->render('related', ['listNews' => $listNews])?>