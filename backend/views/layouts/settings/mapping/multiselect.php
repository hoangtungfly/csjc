<?php

use common\models\admin\SettingsMappingSearch;
use yii\helpers\ArrayHelper;

$data[''] = '-- '.Yii::t('admin', $modelField->label).' --';
    if($modelField->mapping_id != 0){
        $modelMapping = SettingsMappingSearch::findOne($modelField->mapping_id);
        $app = app()->db->createCommand()
                ->select($modelMapping->select_id.','.$modelMapping->select_name)
                ->from($modelMapping->table_name)
                ->where($modelMapping->where)
                ->queryAll();
        $data += ArrayHelper::map(($app),$modelMapping->select_id,$modelMapping->select_name);
    }
    else{
        if(isset($field_options['callfunction']) && trim($field_options['callfunction']) != "") {
            $callfunction = trim($field_options['callfunction']);
            $data += common\utilities\UtilityArray::callFunction($callfunction);
        } else {
            if($field_options['options'] && count($field_options['options']) > 0) {
                $data += ArrayHelper::map($field_options['options'],'value','label');
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
    $field_options['class'] = 'setting_multiselect';
    $field_options['multiple'] = true;
    $field_options['style'] = 'width:300px;';
    echo $form->field($model,$modelField->field_name)->dropDownList($data,$field_options);
?>