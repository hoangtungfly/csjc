<?php

$name = $modelField->field_name;
if(isset($field_options['data-default']) && $model->$name != "") {
    $model->$name = $field_options['data-default'];
}
echo $form->field($model,$name)->textInput($field_options);