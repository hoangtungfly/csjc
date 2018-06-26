<?php

use common\utilities\UtilityArray;
use yii\helpers\Html;

$arrayAttribute = [];
$listTable = [
    'common\models\kanga\TblVisaName' =>  [
        'remove'    => ['id',],
    ],
];

$list = [];

$dataTable = [];

$dataAttribute = [];

if($listTable && count($listTable)) {
    foreach($listTable as $class => $item) {
        $modelV = new $class;
        $className = $class::tableName();
        $attribute = $modelV->attributeLabels();
        if(isset($item['remove'])) {
            foreach($attribute as $k => $v) {
                $attribute[$k] = $k;
            }
            $attribute = UtilityArray::ua($item['remove'], $attribute);
            $list[$class::tableName()] = $attribute;
            $dataTable[$className] = $className;
        }
        if($model->target == $className) {
            $dataAttribute = $attribute;
        }
    }
}
if($list && count($list)) { 
    foreach($list as $key => $item) {
        echo Html::dropDownList('', '', $item, ['style' => 'display:none;','id' => $key]);
    }
}
$left = '<div class="D_left_parent"><div class="D_left">';
if($dataAttribute && count($dataAttribute)) {
    foreach($dataAttribute as $key => $value) {
        $left .= '<p>'.$key.'</p>';
    }
}
$left .= '</div></div>';
$inputvalue = '<div class="D_ohidden"><div class="D_display-input">';
if($model->content != "") {
    $arrayTemplateAttributes = explode('{',$model->content);
    foreach($arrayTemplateAttributes as $key => $value) {
        $array = explode('}',$value);
        $valueInput = $array[0];
        if(isset($array[1])) {
            $inputvalue .= '<span class="D_question-text">' . $array[0] . ' <i class="fa fa-times"></i></span>';
            $valueInput = $array[1];
        }
        $inputvalue .= '<input type="text" class="D_question D_question-text" value="'.$valueInput.'" />';
    }
} else {
    $inputvalue .= '<input type="text" class="D_question D_question-text" />';
}
$inputvalue .= '</div><div class="D_display-input-right"></div></div>';

$name = $modelField->field_name;
if(isset($field_options['data-default']) && $model->$name != "") {
    $model->$name = $field_options['data-default'];
}
if($model->isNewRecord) {
    unset($field_options['readonly']);
}
$field_options['id'] = 'D_question';

echo $form->field($model, $name, [
    'template'  => '{label}<div class="D_wrapper D_replaceattribute">'.$left.'<div class="D_right">{input}'.$inputvalue.'</div></div>{error}',
    'options'   => [
        'class'     => 'form-group',
    ],
])->hiddenInput($field_options);
?>