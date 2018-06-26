<div class="block block-text block-textleft transform_text_begin">
    <div class="container">
        <div class="col-sm-half image col-sm-half-right <?= isset($item['hieuung'])&&(int)$item['hieuung'] ? 'transform_text_top' : '' ?>">
            <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>" />
        </div>
        <div class="col-sm-half block-text-content">
            <h2 class="<?= isset($item['hieuung'])&& (int)$item['hieuung'] ? 'transform_text_top' : '' ?>"><?= $item['title'] ?><span></span></h2>
            <div class="description <?=isset($item['hieuung'])&& (int)$item['hieuung'] ? 'transform_text_bottom' : '' ?>"><?= $item['description'] ?></div>
        </div>
    </div>
</div>