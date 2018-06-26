<?php

use yii\helpers\Html;

?>
<div class="fl" style="width:300px;">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="fa fa-calendar bigger-110"></i>
        </span>
        <?= Html::activeTextInput($model, $name, array('class' => 'form-control setting_daterangepicker', 'placeholder' => $title, 'style' => 'width:250px;')) ?>
    </div>
</div>