<?php

use common\models\admin\SettingsMappingSearch;
use common\utilities\UtilityArray;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
if(isset($field_options['data-type']) && $field_options['data-type']) {
    $arrayType =  explode(',',$field_options['data-type']);
    $arrayClass =  explode(',',$field_options['data-cl']);
    foreach($arrayType as $k => $type) {
        if($type == 'chosen') {
            $ac = explode('_', $arrayClass[$k]);
            if(isset($ac[1]) && $ac[1] != "") {
                $dt = [];
                $dt[''] = '-- Select --';
                $table_name = isset($ac[2]) ? $ac[2] : '';
                $dt += SettingsMappingSearch::mappingAll($ac[1],$table_name, null, false, $table_name == 'categories' ? true : false);
                echo '<div class="array_chosen_div" id="'.$arrayClass[$k].'" style="display:none;">'.Html::dropDownList('', '', $dt, ['style' => 'width:100%;']).'</div>';
            }
        }
    }
}

$field_options['class'] = isset($field_options['class']) ? $field_options['class'] . ' setting_arraymanyjson' : 'setting_arraymanyjson';
echo $form->field($model,$modelField->field_name)->hiddenInput($field_options);