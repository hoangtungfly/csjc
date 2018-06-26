<?php

use common\core\form\GlobalActiveForm;
use common\models\settings\Sendemail;

$link = trim($this->getParam('link'));
$model = new Sendemail();
$form = GlobalActiveForm::begin([
            'id' => 'sendmail_form',
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'validateOnChange' => false,
            'validateOnSubmit' => false,
            'validateOnBlur' => false,
            'angular' => true,
            'hideAction' => false,
            'action' => $this->createUrl('/rest/sendemail'),
            'fieldConfig' => [
                'template' => "{label}\n<div class='input_div'>{input}\n{error}</div>\n{hint}",
                'labelOptions' => ['class' => 'inputForm-label', 'style' => ''],
                'options' => [
                    'class' => 'form-group',
                    'style' => 'margin-bottom:10px;',
                ]
            ],
            'options' => [
                'role' => 'form',
                'ng-submit' => 'sendmaillink(this);',
                'onsubmit'  => 'return false;',
            ],
        ]);
echo $form->field($model, 'email')->textInput(['class' => 'input-text', 'placeholder' => 'Email',])->label('Email');
echo $form->field($model, 'title')->textInput(['class' => 'input-text', 'placeholder' => 'Subject',])->label('Subject');
echo $form->field($model, 'link')->textInput(['placeholder' => 'Link', 'readonly' => true, 'class' => 'input-text'])->label('Link');
echo $form->field($model, 'content')->textarea(['placeholder' => 'Message', 'size' => 60, 'class' => 'textarea-text'])->label('Message');
?>
<div class="form-group" style="text-align: center;margin-top:10px;">
    <button class="button-submit" style="">Send mail</button>
</div>
<style>
    .input-text{height:30px;width:100%;margin-top:10px;padding-left:10px;border-radius: 5px;border: #ddd 1px solid;}
    .textarea-text{height:100px;width:100%;margin-top:10px;border-radius: 5px;border: #ddd 1px solid;}
    .button-submit{background-color: #1b6aaa !important;color:#FFF;border:none;padding:5px 10px;}
</style>
<?php GlobalActiveForm::end(); ?>