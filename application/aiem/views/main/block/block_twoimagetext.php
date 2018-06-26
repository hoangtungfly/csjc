<div class="block block-twoimagetext">
    <div class="block-twoimagetext-one transform_text_begin">
        <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>" />
        <div class="div-content">
            <h2 class="<?= isset($item['hieuung'])&& (int)$item['hieuung'] ? 'transform_text_top' : '' ?>"><?= $item['title'] ?></h2>
            <div class="<?= isset($item['hieuung']) && (int)$item['hieuung'] ? 'transform_text_bottom' : '' ?>"><?= $item['description'] ?></div>
        </div>
    </div>
    <div class="block-twoimagetext-two transform_text_begin">
        <img src="<?= $item['image2'] ?>" alt="<?= $item['title2'] ?>" />
        <div class="div-content">
            <h2 class="<?= isset($item['hieuung']) && (int)$item['hieuung'] ? 'transform_text_top' : '' ?>"><?= $item['title2'] ?></h2>
            <div class="<?= isset($item['hieuung']) && (int)$item['hieuung'] ? 'transform_text_bottom' : '' ?>"><?= $item['description2'] ?></div>
        </div>
    </div>
</div>