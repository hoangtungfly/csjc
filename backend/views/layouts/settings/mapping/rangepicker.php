<?php

use yii\helpers\Html;

?>
<div class="row">
    <div class="col-xs-8 col-sm-11">
        <div class="input-daterange input-group">
            <?=  Html::activeTextInput($model, $modelField->field_name, array('class' => 'input-sm form-control setting_date-picker'))?>
            <span class="input-group-addon">
                <i class="fa fa-exchange"></i>
            </span>
            <?=  Html::activeTextInput($model, $field_options['name2'], array('class' => 'input-sm form-control setting_date-picker'))?>
        </div>
    </div>
</div>
