<div class="block block-slider">
    <div id="owl-main" class="owl-carousel owl-inner-nav owl-ui-sm">
        <?php
        if (isset($item['slider']) && $item['slider'] && count($item['slider'])) {
            foreach ($item['slider'] as $k => $v) {
                ?>
                <a href="<?= $v->link ?>" title="<?= $v->title ?>"><img src="<?= $v->image ?>?v=1.1" alt="<?= $v->title ?>" /></a>
                <?php
            }
        }
        ?>
    </div>
</div>