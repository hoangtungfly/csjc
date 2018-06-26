<?php

use common\core\payments\LoPayment;
use common\widgets\chosen\Chosen;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
if (!isset($model)) {
    $model = new LoPayment();
}
$urlb = $this->getParam('urlb');
$form = ActiveForm::begin([
    'id' => 'lopayment_form',
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validateOnChange' => false,
    'validateOnSubmit' => false,
    'validateOnBlur' => false,
    'action' => $this->createUrl('/payment/complete'),
    'fieldConfig' => [
        'template' => "<div class=\"form-group\">{label}{input}{error}</div>",
        'options' => [
            'class' => 'item-input'
        ]
    ],
    'options' => ['role' => 'form'],
]);

echo Html::hiddenInput('result_id', $result_id);
echo Html::hiddenInput('paytype', "lopayment");
echo isset($_GET['ptype']) ? Html::hiddenInput('ptype', $_GET['ptype']) : '';
echo $urlb ? Html::hiddenInput('urlbb', $urlb) : '';
?>




<div class="form-payment">
    <div class="cont">
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model,'cardnumber')->textInput(['class' => 'form-control', 'maxlength' => '16'])->label(SettingsMessageSearch::t('payment','payment_number_title','*Name On card')) ?>
                <?= $form->field($model,'cardholdername')->textInput(['class' => 'form-control'])->label(SettingsMessageSearch::t('payment','payment_name_title','*Card Name')) ?>
            </div>
            <div class="col-sm-6">
                <div class="item-input expiry">
                    <div class="form-group">
                        <label class="title-input"><?= SettingsMessageSearch::t('payment','payment_expiry_title','*Expiry') ?> </label>
                        <div class="row">
                                <?php
                                echo $form->field($model, 'expmonth', [
                                    'template'  => "{input}{error}",
                                    'options'   => [
                                        'class' => 'col-xs-6',
                                    ]
                                ])->dropDownList(LoPayment::getMonth(), [
                                    'class'     => 'chosen-select settings_chosen',
                                ]);
                                echo $form->field($model, 'expmonth', [
                                    'template'  => "{input}{error}",
                                    'options'   => [
                                        'class' => 'col-xs-6',
                                    ]
                                ])->dropDownList(LoPayment::getYear(), [
                                    'class'     => 'chosen-select settings_chosen',
                                ]);
                                ?>
                        </div>
                    </div>
                </div>
                <?= $form->field($model,'cvv')->textInput(['class' => 'form-control'])->label('*CVV') ?>
            </div>
        </div>
    </div>
</div>
<div class="item-submit clearfix">
    <button type="submit" class="btn btn-danger btn-red btn-checkout"><?= SettingsMessageSearch::t('payment','payment_submit_title','Check out') ?></button>
</div>
<div id="payment-show-message-success"></div> 
<?php ActiveForm::end(); ?>
