<?php

use yii\helpers\Html;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo $title.': ';
$title{0} = strtolower($title{0});
$mapp[''] = '-- Select ' .$title. ' --';
//if(!isset($mapping['0']) && isset($mapping[0]))
//    $mapp['0'] = $title;
$mapp += $mapping;
echo Html::activeDropDownList($model,$name,  $mapp, 
                                array(
                                    'style'=>'width:200px;',
                                    'placeholder'=>$model->getAttributeLabel("pid"),
                                    'class' => 'setting_chosen',
                                ));