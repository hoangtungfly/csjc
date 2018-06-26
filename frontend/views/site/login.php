<?php

use common\core\enums\UserEnum;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="col-md-6 content_wc">
    <p class="creat_pass">Login</p>
    <div class="frm_create_pass">
        <?php
        $form = ActiveForm::begin([
            'id' => 'login-form',
        ]);
        ?>
        <div class="form-group">
            <!--<p class="lable_field">Email</p>-->    
            <?= $form->field($model, 'username')->textInput(['class' => 'txt_let_talk', 'placeholder' => 'email'])->label(false) ?>
        </div>
        <div class="form-group">
            <!--<p class="lable_field">Password</p>-->      
             <?= $form->field($model, 'password')->passwordInput(['class' => 'txt_let_talk', 'placeholder' => 'password'])->label(false) ?>
        </div>
        <div class="forgotPass">
            <?php
            $optionsRmb = ['value'=>'1','class' => 'new_style'];
            if($model->rememberMe) 
                $optionsRmb['checked'] = true;
            echo $form->field($model, 'rememberMe', [
                'template' => '<label>{input}<div class="txt_assement lbl_check_radio">Remember me</div></label>{error}',
                'options' => [
                    'class' => 'checkbox_group'
                ],
            ])->input('checkbox', $optionsRmb)->label(false);
            ?>
            <a href="<?= $this->createUrl('/site/forgot');?>" class="link_now">Forgot password</a>
        </div>
        <?= Html::submitButton('Login', ['class' => 'btn btn-submit btn-convert next-btn full-button', 'name' => 'login-button', 'style'=>'width:100%']) ?>
    </div>
</div>
<?php if(Yii::$app->session->has(UserEnum::LOGIN_COUNT) && Yii::$app->session->get(UserEnum::LOGIN_COUNT) >= 5):?>
<?php $this->registerJs(
        "$(document).ready(function(e) {
            confirmModal({
                'header': 'Alert',
                'confirmHtml': 'OK',
                'message': '".Yii::t('notify', 'login_5_times')."',
                'confirm': function() {
                    window.location = '/site/forgot';
                },
            })
        });", \yii\web\View::POS_END
);
?>
<?php endif;?>