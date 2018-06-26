<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
$arrayTable = ArrayHelper::map($listTable, 'table_id', 'name');
?>
<div class="fl">
    <div class="fl" style="margin-top: 7px;margin-right:4px;">
    Settings table:
    </div> 
<?php
echo Html::dropDownList(
            'SettingsFieldSearch[table_id]', 
            isset($_GET['table_id']) ? $_GET['table_id'] : '', 
            $arrayTable, 
            array(
                'class'     => 'setting_chosen',
                'style'     => 'width:200px;',
                'data-href' => $this->createUrl('/settings/buildform/loadform'),
                'id'        => 'SettingsFieldSearch_table_id',
            )
);
?>
</div>
<div class="fl" style="margin-top: 5px;margin-left:10px;">
    <div class="fl" style="margin-top: 5px;">
    Multi Add:
    </div>
<?php
echo Html::hiddenInput('SettingsFieldSearch[multi_add]', isset($_GET['multi_add']) ? $_GET['multi_add'] : '0', array('class' => 'setting_onoff', 'id' => 'SettingsFieldSearch_multi_add'));
?>
</div>