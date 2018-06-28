<div class="jumbotron container-fluid">
    <div class="row">
        <div class="owl-carousel owl-theme">
            <?php
            if (isset($item['slider']) && $item['slider'] && count($item['slider'])) {
                foreach ($item['slider'] as $k => $v) {
                    ?>
                    <div class="slide slide_<?=$k+1?>">
                        <div class="container">
                            <div class="col-xs-6 col_1">
                                <h4 class="slogan"><?= $v->title ?></h4>
                                <p>Payments are becoming increasingly global and the need for a modern platform that reduces the time, cost and risk of transactions is essential to power the future of commerce.</p>
                                <p>nanopay—real-time Frictionless® payments</p>
                                <button class="watchVideo">Watch video</button>
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