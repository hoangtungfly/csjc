<div class="row row_<?=$key-1?>">
    <div class="col-xs-6 col col_1 full-expand-xxs">
        <div class="title">
            <h4><?=isset($item['title']) ? $item['title'] : ''?></h4>
        </div>
        <p class="wow slideInUp" data-wow-duration="1.5s"><?= isset($item['description'])? $item['description']:'' ?></p>
        <button><?=isset($item['text2']) ? $item['text2'] : ''?></button>
    </div>
    <div class="col-xs-6 col col_2 col_2 hidden-xxs" style="background: #0079AF url(<?=isset($item['image']) ? $item['image'] : ''?>);background-position: center;background-repeat: no-repeat;background-size: cover;"></div>
</div>