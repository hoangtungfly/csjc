<?php

use common\models\admin\SettingsMessageSearch;

$columns = $item['arraymanyjson'] ? $item['arraymanyjson'] : [];
?>

<div class="container-fluid section_1">
    <div class="row">
        <div class="container">
            <div class="row row_1">
                <div class="col-xs-12">
                    <h4 class="text-center wow slideInUp" data-wow-duration="2s"><?=$item['title']?></h4>
                    <p class="wow slideInUp" data-wow-duration="2s"><?=$item['description']?></p>
                    <button class="center-block"><?= SettingsMessageSearch::t('common', 'button_learnmore','Learn more')?></button>
                </div>
            </div>
            <?php if(count($columns)):?>
            <div class="row row_2">
                <div class="col-xs-12">
                    <h4 class="text-center"><?= SettingsMessageSearch::t('home', 'our_business','Our Business')?></h4>
                </div>
                <?php foreach($columns as $col):?>
                <div class="col-xs-3">
                    <div id="f1_container">
                        <div id="f1_card" class="shadow">
                            <div class="front face">
                                <div>
                                    <img class="center-block" src="<?= isset($col->image) && $col->image ? $col->image :''?>">
                                    <p class="text-center"><?= isset($col->altimage) && $col->altimage ? $col->altimage :''?></p>
                                </div>
                            </div>
                            <div class="back face center">
                                <?= SettingsMessageSearch::t('home', 'image_description','This is nice for exposing more information about an image.')?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>