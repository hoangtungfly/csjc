<?php

$name = $modelField->field_name;
if($modelField->mapping_id != 0){
    $field_options['data-mapping_id'] = $modelField->mapping_id;
    $field_options['class'] = isset($field_options['class']) ? $field_options['class'] .' setting_tokeninput' : 'setting_tokeninput';
    $field_options['data-vl'] = $model->$name != "" ? $model->getTokenValue($model->$name,$modelField->mapping_id) : '[]';
}
echo $form->field($model,$name)->textInput($field_options);