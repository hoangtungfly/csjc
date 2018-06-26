<?php

use common\core\form\GlobalActiveForm;
use common\models\settings\Sendemail;

$link = trim($this->getParam('link'));
$model = new Sendemail();
$form = GlobalActiveForm::begin([
            'id' => 'mes-private-messager-form',
            'enableClientValidation' => false,
            'enableAjaxValidation' => false,
            'validateOnChange' => false,
            'validateOnSubmit' => false,
            'validateOnBlur' => false,
            'angular' => true,
            'hideAction' => true,
            'action' => $this->createUrl('/home/send'),
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
                'ng-submit' => 'sendmail()',
            ],
        ]);
echo $form->field($model, 'title')->textInput(['class' => 'input-text', 'placeholder' => 'Subject',])->label('Subject');
if ($link != "") {
    echo $form->field($model, 'link')->textInput(['placeholder' => 'Link', 'readonly' => true, 'class' => 'input-text'])->label('Link');
}
echo $form->field($model, 'content')->textarea(['placeholder' => 'Content', 'size' => 60, 'class' => 'textarea-text'])->label('Content');
?>
<div class="form-group" style="text-align: center;margin-top:10px;">
    <button class="button-submit" style="">Send mail</button>
</div>
<style>
    .input-text{height:30px;width:100%;margin-top:10px;}
    .textarea-text{height:100px;width:100%;margin-top:10px;}
    .button-submit{background-color: #003bb3;color:#FFF;border:none;padding:5px 10px;}
</style>
<?php GlobalActiveForm::end(); ?>