<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>
<div class="col-md-6 content_wc">
    <p class="creat_pass">Reset password</p>
    <div class="frm_create_pass">
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
            <!--<p class="lable_field">New Password </p>-->   
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'New Password', 'class' => 'txt_let_talk'])->label(false) ?>
        </div>
        <div class="form-group">
            <!--<p class="lable_field">Retype New Password</p>-->      
            <?= $form->field($model, 'passwordConfirm')->passwordInput(['placeholder' => 'Retype New Password', 'class' => 'txt_let_talk'])->label(false) ?>
        </div>
        <?= Html::submitButton('Reset Password', ['class'=>"btn btn-submit btn-convert next-btn full-button", 'style'=>"width:100%" ])?>
        <?= Html::hiddenInput('token', $token) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>