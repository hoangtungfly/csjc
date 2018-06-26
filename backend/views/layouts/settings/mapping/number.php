<?php

use yii\helpers\Html;
$name = $modelField->field_name;
$units = '';
if(isset($field_options['units'])){ 
   $units = $field_options['units'];
    unset($field_options['units']);
}
$class = ' isnumber numberformat D_loadurl col-xs-12';
if(isset($field_options['class'])) {
    $class = $field_options['class']. $class;
}
$field_options['class'] = $class;
$field_options['onblur'] = '$(this).prev().val($(this).val().replace(/,/gi,""));';

$value = $model->$name;
echo $form->field($model, $name,[
    'template'  => "{input}".Html::textInput('', number_format((int)$value), $field_options).'{error}',
])->hiddenInput();
?>