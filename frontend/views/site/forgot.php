<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="col-md-6 content_wc">
    <p class="creat_pass">reset password</p>
    <div class="frm_create_pass frm_reset">
        <p class="reset_pass">Please enter email linked to your Metrixa account. We will send you a link to setup new password</p>
        <?php
        $form = ActiveForm::begin(['id' => 'reset-form',
                    'enableClientValidation' => false,
                    'enableAjaxValidation' => true,
                    'validateOnChange' => false,
                    'validateOnSubmit' => true,
                    'validateOnBlur' => false,
        ]);
        ?>
        <div class="form-group">
            <!--<p class="lable_field">Email</p>-->
            <?= $form->field($model, 'email')->textinput(['placeholder' => 'email', 'class' => 'txt_let_talk'])->label(false) ?>
        </div>
        <?= Html::submitButton('Reset password', ['class'=>"btn btn-submit btn-convert next-btn full-button", 'style'=>"width:100%" ])?>
        <?php ActiveForm::end(); ?>
    </div>
</div>