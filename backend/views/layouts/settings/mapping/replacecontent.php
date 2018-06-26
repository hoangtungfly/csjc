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
$left = '<div class="D_left_parent" style="height:309px;"><div class="D_left">';
if($dataAttribute && count($dataAttribute)) {
    foreach($dataAttribute as $key => $value) {
        $left .= '<p>'.$key.'</p>';
    }
}
$left .= '</div></div>';
$inputvalue = '<div class="D_display-content">';
if($model->content != "") {
    $content = '';
    $arrayTemplateAttributes = explode('{',$model->content);
    foreach($arrayTemplateAttributes as $key => $value) {
        $array = explode('}',$value);
        
        if(isset($array[1])) {
            $content .= '<img src="/image.php?text=' . $array[0] . '" />';
            $content .= $array[1];
        } else {
            $content .= $array[0];
        }
    }
    $model->content = $content;
}
$inputvalue .= '</div>';

$name = $modelField->field_name;
if(isset($field_options['data-default']) && $model->$name != "") {
    $model->$name = $field_options['data-default'];
}
if($model->isNewRecord) {
    unset($field_options['readonly']);
}
$field_options['id'] = 'D_question';
$field_options['class'] = 'setting_ckeditor_small setting_ckeditor_small_replace_content';
echo $form->field($model, $name, [
    'template'  => '{label}<div class="D_replacecontent">'.$left.'<div class="D_right">{input}</div></div>{error}',
    'options'   => [
        'class'     => 'form-group',
    ],
])->textarea($field_options);
?>