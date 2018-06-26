<?php

use common\core\form\GlobalActiveForm;
use common\models\lib\LibGender;
use common\models\lib\LibRole;
use common\widgets\chosen\Chosen;
use yii\helpers\Html;
use yii\helpers\Url;

$genders = LibGender::getAllGender();
$roles = LibRole::getAllRole();
$html = '';

foreach ($genders as $value => $label) {
    $html .= '<div class="col-sm-4"><label>';
    $html .= Html::radio('radio_radio', $model->gender == $value ? true : false, [
                'class' => 'ace ' . ($model->gender == $value ? 'radio_radio' : ''),
                'value' => $value,
                'onclick' => '$(this).closest(".setting_radio").find(".setting_radio_input").val($(this).val());' . '',
    ]);
    $html .= '<span class="lbl"> ' . $label . '</span>';
    $html .= '</label>';
    $html .= '</div>';
}
?>
<div class="page-header">
    <h1>Create new user</h1>
</div>
<div class = "row">
    <div class = "col-xs-9" style = "width:900px;">
        <div class = "col-xs-12 panel-group accordion-style1 accordion-style2">
            <?php
            $form = GlobalActiveForm::begin([
                        'id' => "userform",
                        'enableAjaxValidation' => true,
                        "action" => Url::to(['details/save', 'id' => $model->user_id]),
                        'method' => "POST",
                        'enableClientValidation' => false,
                        'validateOnChange' => false,
                        'validateOnBlur' => false,
                        'fieldConfig' => [
                            'template' => '<div class = "col-sm-12" style = "margin-bottom: 12px;">{label}<div class = "col-sm-10" style = "padding-right:0px;padding-left:0px;">{input}{error}</div></div>',
                            'labelOptions' => [
                                'class' => 'col-sm-2 control-label no-padding-right D-form-label',
                                'style' => 'padding-top:4px;'
                            ],
                            'inputOptions' => [
                                'class' => 'col-xs-12',
                            ],
                        ],
                        'options' => [
                            'class' => 'form-horizontal formsortable',
                        ],
            ]);
            ?>    
            <div class = "panel panel-default col-sm-12" data-id = "43">
                <div class = "panel-collapse collapse in" data-status = "in" id = "faq-2-1" aria-expanded = "true">
                    <div class = "panel-body form-group" style = "padding-left:0px;padding-right:0px;padding-bottom: 0px;margin-bottom: 0px;margin-top:0px;padding-top:3px;">
                        <?= $form->field($model, 'firstname')->textInput(['placeholder' => "First name"]) ?>
                        <?= $form->field($model, 'lastname')->textInput(['placeholder' => "Last name"]) ?>
                        <?=
                                $form->field($model, 'gender', [
                                    'template' => '{label}<div class="form-group-input-child col-sm-10 setting_radio">{input}{description}<div class="clear"></div>{error}</div>',
                                    'options' => [
                            ]])->hiddenInput([
                                    'placeHolder' => 'Gender',
                                    'class' => 'setting_radio_input',
                                ])
                                ->label('Gender')
                                ->description($html)
                        ?>  
                        <?=
                        $form->field($model, 'birthday', [
                            'template' => '{label}<div class="form-group-input-child col-sm-10 input-group" style="width:82%">{input}<input type="text" class="form-control setting_date-picker" data-date-format="' . FORMAT_DATE_INPUT . '" onchange="$(this).closest(\'.input-group\').find(\'#usermodel-birthday\').val($(this).val());" style="width:99%; margin-left:8px; height:29px"/><span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span><div class="clear"></div>{description}{error}</div>',
                        ])->hiddenInput()->label('Birthday');
                        ?>                       
                        <?= $form->field($model, 'email')->textInput(['placeholder' => "Email address"])->label('Email'); ?>
                        <?php if($model->isNewRecord):?>
                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => "Password"])->label('Password') ?>
                        <?php endif; ?>
                        <?= $form->field($model, 'phone')->textInput(['placeholder' => "Phone"]); ?>
                        <?=
                        $form->field($model, 'role')->widget(
                                Chosen::className(), [
                            'value' => $model->role,
                            'items' => $roles,
                            'placeholder' => 'Role',
                        ]);
                        ?>
                       </div>
                </div>
            </div>
            <div class = "col-sm-12" style = "margin-bottom: 10px;">
                <div class = "col-sm-12 D_form_submit" style = "text-align:center;">
                    <input type = "hidden" value = "http://mesop.com/user/details/index" name = "urlb" />
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary', 'style' => 'border:0px;']) ?>
                    <a class = "btn btn-success D_cancel" data-href = "http://mesop.com/user/details/index" style = "border:0px;">Back</a>
                    </form>
                </div>
            </div> 
            <?php GlobalActiveForm::end(); ?>
        </div>               
    </div>
</div>

<style type="text/css">
    .field-usermodel-birthday label {
        padding-top:4px;
        padding-left:13px
    }
</style>



