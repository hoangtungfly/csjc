<?php

use yii\helpers\Html;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo $title . ': ' . Html::activeTextInput($model, $name, array('placeholder' => $title,'style' => 'width:200px;height:34px;'));
