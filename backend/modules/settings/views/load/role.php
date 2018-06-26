<?php

use common\utilities\UtilityArray;

?>
<div class="col-sm-12 D_role_html">
    <?php foreach($menu[0] as $key=>$value){?>
    <div class="col-sm-3 D_role_html2">
        <input type="checkbox" <?=  UtilityArray::searchArray($array, $key) ? 'checked="true"' : ''?> data-id="<?=$key?>" class="setting_checkbox" />
        <span><?=$value['name']?></span>
        <?php if(isset($menu[$key])){foreach($menu[$key] as $key2=>$value2){?>
        <div class="col-sm-12">
            <input type="checkbox" <?=UtilityArray::searchArray($array, $key2) ? 'checked="true"' : ''?> data-id="<?=$key2?>" class="setting_checkbox" />
            <span><?=$value2['name']?></span>
            <?php if(isset($menu[$key2])){foreach($menu[$key2] as $key3=>$value3){?>
            <div class="col-sm-12">
                <input type="checkbox" <?=UtilityArray::searchArray($array, $key3) ? 'checked="true"' : ''?> data-id="<?=$key3?>" class="setting_checkbox" />
                <span><?=$value3['name']?></span>
                <?php if(isset($menu[$key3])){foreach($menu[$key3] as $key4=>$value4){?>
                <div class="col-sm-12">
                    <input type="checkbox" <?=UtilityArray::searchArray($array, $key4) ? 'checked="true"' : ''?> data-id="<?=$key4?>" class="setting_checkbox" />
                    <span><?=$value4['name']?></span>
                </div>
                <?php }} ?>
            </div>
            <?php }} ?>
        </div>
        <?php }} ?>
    </div>
    <?php } ?>
</div>