<div class="block block-main <?= isset($item['hieuung']) && (int)$item['hieuung'] ? 'transform_text_begin' : '' ?>">
    <img class="background" src="<?= $item['background'] ?>" alt="<?= $item['title'] ?>" />
    <div class="block-main-content">
        <div class="container">
            <h2 class="<?= isset($item['hieuung']) && (int)$item['hieuung'] ? 'transform_text_top' : '' ?>"><?= $item['title'] ?><span></span></h2>
            <div class="<?= isset($item['hieuung']) && (int)$item['hieuung'] ? 'transform_text_bottom' : '' ?>">
                <?= $item['description'] ?>
            </div>
        </div>
    </div>
</div>