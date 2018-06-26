<?php

use common\models\admin\SettingsMappingSearch;
use common\utilities\UtilityArray;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
$data[''] = '-- '.Yii::t('admin', $modelField->label).' --';
if($modelField->mapping_id != 0){
    $data += SettingsMappingSearch::mappingAll($modelField->mapping_id,$model->tableName());
}
else{
    if(isset($field_options['callfunction']) && trim($field_options['callfunction']) != "") {
        $callfunction = trim($field_options['callfunction']);
        $data += UtilityArray::callFunction($callfunction);
    } else {
        if(isset($field_options['options']) && count($field_options['options']) > 0) {
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
if(count($data) > 1) {
    echo '<div class="array_chosen_div" style="display:none;">'.Html::dropDownList('', '', $data, ['style' => 'width:180px;']).'</div>';
    $field_options['data-chosen']    = 1;
}
$field_options['class'] = isset($field_options['class']) ? $field_options['class'] . ' setting_array' : 'setting_array';
echo $form->field($model,$modelField->field_name)->hiddenInput($field_options);