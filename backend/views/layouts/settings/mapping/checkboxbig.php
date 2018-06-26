<?php

use common\models\admin\SettingsMappingSearch;
use common\utilities\UtilityArray;
use yii\helpers\ArrayHelper;
    if($modelField->mapping_id != 0){
        $data = SettingsMappingSearch::mappingAll($modelField->mapping_id);
    }
    else{
        if(isset($field_options['callfunction']) && trim($field_options['callfunction']) != "") {
            $callfunction = trim($field_options['callfunction']);
            $data = UtilityArray::callFunction($callfunction);
        } else {
            if($field_options['options'] && count($field_options['options']) > 0) {
                $data = ArrayHelper::map($field_options['options'],'value','label');
                if($model->isNewRecord) {
                    foreach ($field_options['options'] as $item) {
                        if($item->checked) {
                            $model->$name = $item->value;
                            break;
                        }
                    }
                }
            }
        }
    }
    unset($field_options['options']);
    unset($field_options['callfunction']);
    unset($field_options['options']);
    $field_options['class'] = 'setting_checkboxbig';
    $name = $modelField->field_name;
    $html = '';
    if(count($data)>0){
        foreach($data as $id=>$name1){
            $html.= '<div class="checkboxbig'.((UtilityArray::searchArray(explode(',',$model->$name), $id)) ? ' active' : '').'" data-id="'.$id.'">';
            $html.= $name1;
            $html.= '</div>';
        }
    }
    echo $form->field($model,$name,[
        'template'  => '{input}'.$html.'<div class="clear"></div>{error}'
    ])->hiddenInput($field_options);
?>