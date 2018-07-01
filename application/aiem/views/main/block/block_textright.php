<div class="row row_<?=$key-1?>">
    <div class="col-xs-6 col col_1 hidden-xxs" style="background: #0079AF url(<?= $item['image'] ?>);background-position: center;background-repeat: no-repeat;background-size: cover;"></div>
    <div class="col-xs-6 col col_2 full-expand-xxs">
        <div class="title">
            <h4><?=$item['title']?></h4>
        </div>
        <p class="wow slideInUp" data-wow-duration="1.5s"><?= $item['description'] ?></p>
        <button><?=$item['text2']?></button>
    </div>
</div>