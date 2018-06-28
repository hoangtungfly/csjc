<div class="row row_<?=$key-1?>">
    <div class="col-xs-6 col col_1">
        <div class="title">
            <img src="<?= $item['image'] ?>">
            <h4><?=$item['title']?></h4>
        </div>
        <p class="wow slideInUp" data-wow-duration="2s"><?= $item['description'] ?></p>
        <button><?=$item['text2']?></button>
    </div>
    <div class="col-xs-6 col col_2"></div>
</div>