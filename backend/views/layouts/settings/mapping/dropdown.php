<?php

use common\models\admin\SettingsMappingSearch;
use common\models\kanga\QuestionTemplate;
use common\utilities\UtilityArray;
use yii\helpers\ArrayHelper;


$name = $modelField->field_name;

if($name == 'target' && $model->hasAttribute('ques_temp_id') && $model->ques_temp_id && $modelQuestion = QuestionTemplate::findOne($model->ques_temp_id)) {
    $model->target = $modelQuestion->target;
}

$data[''] = '-- '.Yii::t('admin', $modelField->label).' --';
if($modelField->mapping_id != 0){
    $data += SettingsMappingSearch::mappingAll($modelField->mapping_id,className($model) == 'DynamicModel' ? '' : $model->tableName(),NULL,$model);
}
else{
    if(isset($field_options['callfunction']) && trim($field_options['callfunction']) != "") {
        $callfunction = trim($field_options['callfunction']);
        $data += UtilityArray::callFunction($callfunction);
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
$field_options['class'] = (isset($field_options['class'])) ? $field_options['class'].' setting_chosen' : ' setting_chosen';
$field_options['style'] = (isset($field_options['style'])) ? $field_options['style'] : ' max-width:400px;width:400px;';

$field_options['data-id'] = $model->$name;

if(isset($_GET['SettingsGridSearch']['table_id'])){
    $model->table_id = $_GET['SettingsGridSearch']['table_id'];
}
echo $form->field($model,$name)->dropDownList($data,$field_options);