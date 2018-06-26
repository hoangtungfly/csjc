<?php
$std = (array)json_decode($modelField->field_options)->options[0];
$name = $modelField->field_name;
if($model->$name === null) {
    $model->$name = $std['checked'] ? 1 : 0;
}
echo $form->field($model,$name)->hiddenInput(array('class'=>'setting_checkbox'));