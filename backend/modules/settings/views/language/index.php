<?php

use yii\helpers\Html;

?>
<div class="page-header">    
    <h1 class="fl" style="width:100%;"><span><?=  Yii::t('admin','Language')?></span></h1>    
</div>
<form method="post">
    <button class="btn btn-primary" style="border: none;float:right;">Update</button>
    <div class="col-sm-12 plr0">
        <?php
        $dem = 1;
        foreach ($arrayDataFile as $key => $item) {
            ?>
        <div class="panel panel-default col-sm-12 plr0">
            <div class="panel-heading">
                <a href="#faq-2-<?= $dem ?>" data-parent="#faq-list-<?= $dem ?>" 
                   data-toggle="collapse" style="display: block;" class="accordion-toggle collapsed" aria-expanded="false">
                    <i class="smaller-80 ace-icon fa fa-chevron-right" 
                       data-icon-hide="ace-icon fa fa-chevron-down align-top" 
                       data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;<?= $key ?>
                </a>
            </div>
            <div class="panel-collapse collapse in" data-status="" id="faq-2-<?= $dem ?>" aria-expanded="true">
                <div class="panel-body form-group" style="padding-left:0px;padding-right:0px;padding-bottom: 0px;margin-bottom: 0px;margin-top:0px;padding-top:3px;">
            <?php
            echo Html::hiddenInput($key, json_encode($item), array(
                'class' => 'setting_json',
            ));
            $dem++;
            ?>
                </div>
            </div>
        </div>
        <?php }  ?>
    </div>
    <button class="btn btn-primary" style="border: none;float:right;">Update</button>
</form>