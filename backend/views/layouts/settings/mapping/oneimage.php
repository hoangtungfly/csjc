<?php
$name = $modelField->field_name;
$field_options['class'] = 'setting_oneimage col-sm-10';
echo $form->field($model,$name,[
    'options'  => [
        'class' => 'setting_oneupload  ',
    ],
])->textInput($field_options);
?>