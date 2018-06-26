<?php
$std = (array)json_decode($modelField->field_options)->options[0];
$name = $modelField->field_name;
if($model->$name === null) {
    $model->$name = $std['checked'] ? 1 : 0;
}
$field_options['class'] = isset($field_options['class']) ? $field_options['class'] . ' setting_onoff' : ' setting_onoff'; 
unset($field_options['options']);
    echo $form->field($model,$modelField->field_name)->hiddenInput($field_options);
?>