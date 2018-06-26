<?php

use common\core\form\GlobalActiveForm;
use common\models\system\SysContact;

$model = new SysContact();
?>
<div ng-show="flash" class="alert alert-{{ flash.class }}">
    {{flash.message}}
</div>
<h1>Contact</h1>

<p>
    If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
</p>
<div class="row">

    <div class="col-lg-5">
        <?php
            $form = GlobalActiveForm::begin([
                'id' => 'contact-form',
                'enableClientValidation' => false,
                'enableAjaxValidation' => false,
                'validateOnChange' => false,
                'validateOnSubmit' => false,
                'validateOnBlur' => false,
                'angular'   => true,
                'hideAction'    => true,
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'options'   => [
                        'class'     => 'form-group',
                    ],
                    'labelOptions'  => [
                        'class' => 'control-label',
                    ],
                    'inputOptions'  => [
                        'class' => 'form-control',
                    ],
                    'errorOptions'  => [
                        'class' => 'help-block help-block-error',
                    ],
                ],
                'options'   => [
                    'role' => 'form',
                    'ng-submit' => 'contact()',
                ],
            ]);
        ?>
        
        
            <?=$form->field($model, 'contact_name')->textInput()?>
        
        
            <?=$form->field($model, 'contact_email')->textInput()?>
        
        
            <?=$form->field($model, 'contact_subject')->textInput()?>
        
        
            <?=$form->field($model, 'contact_body')->textarea(['rows' => 6])?>
        
        
            <?=$form->field($model, 'verifyCode')->capcha([
                'parentOptions' => [
                    'class' => 'row',
                ],
                'divImgOptions' => [
                    'class' => 'col-lg-3',
                ],
                'divInputOptions' => [
                    'class' => 'col-lg-6',
                ],
                'imgOptions'    => [
                    'ng-click' => "refreshCaptcha()",
                    'ng-src' => "{{captchaUrl}}",
                ],
            ])?>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" name="contact-button">Submit</button>
            </div>

        <?php GlobalActiveForm::end(); ?>
    </div>
</div>
