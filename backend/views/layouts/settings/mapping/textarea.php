<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//form-control limited
$field_options['class'] = ' form-control setting_limited';
$field_options['style'] = isset($field_options['style']) ? $field_options['style'].'padding-right:6px;' : 'padding-right:6px;';
$name = $modelField->field_name;
echo $form->field($model,$modelField->field_name)->textArea($field_options);