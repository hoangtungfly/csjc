<?php

use yii\helpers\Html;

?>
<div class="fl" style="width:300px;">
    <div class="input-group">
        <span class="fl" style="margin:5px 10px 0px 10px;">
            <?= $title ?>
        </span>
        <?php
        echo Html::activeTextInput($model, $name . '_from', array('class' => 'form-control', 'placeholder' => $title . ' từ', 'style' => 'width:100px;')) . '<div class="fl" style="margin:5px 5px 0px 5px;"> - </div>';
        echo Html::activeTextInput($model, $name . '_to', array('class' => 'form-control', 'placeholder' => $title . ' đến', 'style' => 'width:100px;'));
        ?>
    </div>
</div>