<?php

use yii\helpers\Html;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo Html::activeHiddenInput($model, $name);
$strId = Html::getInputId($model, $name);
foreach ($mappingMenu as $key=>$item){
    if($item && count($item)>0){
        $mapp = array();
        $mapp[''] = $title.($key>0 ? $key : '');
        $mapp[0] = 'Category Parent';
        $mapp += $item;
        echo Html::dropDownList(
                '',
                isset($modelValue[$key]) ? $modelValue[$key] : '',
                $mapp, 
                array(
                    'id'=>$name.'-'.$key,
                    'style'=>'width:200px;margin-right:10px;',
                    'placeholder'=>$model->getAttributeLabel("pid"),
                    'class' => 'setting_chosen',
                    'onchange' => "$('#$strId').val(this.value);",
                ));
    }
}