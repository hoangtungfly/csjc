<div class="jumbotron container-fluid">
    <div class="row">
        <div class="owl-carousel owl-theme">
            <?php
            if (isset($item['slider']) && $item['slider'] && count($item['slider'])) {
                foreach ($item['slider'] as $k => $v) {
                    ?>
                   
                <div class="slide slide_<?=$k+1?>" style="background: url(<?=isset($v->image) ? $v->image : ''?>)">
                        <div class="container">
                            <div class="col-xs-6 col_1">
                                <?php if($k == 0 ):?>
                                <h4 class="slogan"><?= $v->title ?></h4>
                                <button class="watchVideo">Watch video</button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>