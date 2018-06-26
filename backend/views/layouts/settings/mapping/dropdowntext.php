<?php

use common\models\admin\SettingsMappingSearch;
use common\models\kanga\QuestionTemplate;
use common\utilities\UtilityArray;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$name = $modelField->field_name;
$className = className($model);
$data[''] = '-- '.Yii::t('admin', $modelField->label).' --';
if($modelField->mapping_id != 0){
    $data += SettingsMappingSearch::mappingAll($modelField->mapping_id,$model->tableName(),NULL,$model);
}
else{
    if(isset($field_options['callfunction']) && trim($field_options['callfunction']) != "") {
        $callfunction = trim($field_options['callfunction']);
        $data += UtilityArray::callFunction($callfunction);
    } else {
        if($field_options['options'] && count($field_options['options']) > 0 && isset($field_options['options'][0]->value)) {
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
$field_options['class'] = (isset($field_options['class'])) ? $field_options['class'].' setting_chosen' : ' setting_chosen';
$field_options['style'] = (isset($field_options['style'])) ? $field_options['style'] : ' max-width:400px;width:400px;';

$value = $model->$name;

$str_id = Html::getInputId($model,$name);
$onchange = "$('#$str_id').val($(this).val());";
if(isset($field_options['onchange'])) {
    $field_options['onchange'] .= $onchange;
} else {
    $field_options['onchange'] = $onchange;
}
$field_options['data-chosen_field_id'] = $modelField->field_id;
$field_options['id']    = $name.'dropDownList';
$field_options['dataid']    = '{{'.$className.'.' . $name . '}}';

echo Html::dropDownList($field_options['id'], $value, $data, $field_options);

echo $form->field($model,$name)->textInput(['class' => 'col-xs-12']);