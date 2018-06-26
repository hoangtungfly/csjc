<?php

$field_options['class'] = 'setting_manyimages';

$name = $modelField->field_name;

$value = $model->$name;
if($value) {
    $a = json_decode($value,true);
    if($a && count($a)) {
        foreach($a as $v) {
            if(isset($v['name'])) {
                $model->getimage([30,30],$v['name']);
            }
        }
    }
}

echo $form->field($model,$modelField->field_name)->hiddenInput($field_options);

?>