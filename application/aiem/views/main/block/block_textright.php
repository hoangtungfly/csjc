<div class="block block-text block-textright transform_text_begin">
    <div class="container">
        <div class="col-sm-half image <?= isset($item['hieuung'])&&(int)$item['hieuung'] ? 'transform_text_top' : '' ?>">
            <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>" />
        </div>
        <div class="col-sm-half block-text-content transform_text_bottom">
            <h2 class="<?= isset($item['hieuung'])&& (int)$item['hieuung'] ? 'transform_text_top' : '' ?>"><?= $item['title'] ?><span></span></h2>
            <div class="description <?= isset($item['hieuung'])&&(int)$item['hieuung'] ? 'transform_text_bottom' : '' ?>"><?= $item['description'] ?></div>
        </div>
    </div>
</div>