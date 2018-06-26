<?php

use common\models\admin\SettingsMappingSearch;
use yii\helpers\ArrayHelper;

    if($modelField->mapping_id != 0){
        $data = SettingsMappingSearch::mappingAll($modelField->mapping_id);
    }
    else{
        if(isset($field_options['callfunction']) && trim($field_options['callfunction']) != "") {
            $callfunction = trim($field_options['callfunction']);
            $data = common\utilities\UtilityArray::callFunction($callfunction);
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
    unset($field_options['callfunction']);
    unset($field_options['options']);
    $field_options['class'] = 'setting_checkboxsmall';
    $name = $modelField->field_name;
    echo $form->field($model,$name)->dropDownList($data,$field_options);
?>