<?php

use common\core\form\GlobalActiveForm;
use common\models\order\OrderSearch;

$model = new OrderSearch();
$model->setScenario('frontend');
?>
<div class="col-lg-12 col-md-12">
    <div class="row">
        <div class="opc-col-left col-lg-12 col-md-12 fr">
            <?php
            $form = GlobalActiveForm::begin([
                        'id' => 'cart-form',
                        'enableClientValidation' => false,
                        'enableAjaxValidation' => false,
                        'validateOnChange' => false,
                        'validateOnSubmit' => false,
                        'validateOnBlur' => false,
                        'angular' => false,
                        'action' => $this->createUrl('/cart/proccess'),
                        'fieldConfig' => [
                            'template' => "{label}<div class=\"input-box\">{input}</div>\n{error}",
                            'options' => [
                                'class' => 'col-md-12 col-sm-12 col-xs-12',
                            ]
                        ],
                        'options' => [
                            'role' => 'form',
                            'onsubmit' => 'return false;',
                            'class' => 'comment-respond',
                        ],
            ]);
            ?>
            <div id="co-billing-form">
                <h3>Thông tin mua hàng</h3>
                <ul class="form-list">
                    <li id="billing-new-address-form">
                        <fieldset>
                            <ul>
                                <li class="fields row">
                                    <?= $form->field($model, 'shipping_name')->textInput(['class' => 'input-text required-entry'])->label('Họ tên') ?>
                                </li>
                                <li class="fields row">
                                    <?= $form->field($model, 'shipping_email')->textInput(['class' => 'input-text required-entry'])->label('Email') ?>
                                </li>
                                <li class="fields row">
                                    <?= $form->field($model, 'shipping_phone')->textInput(['class' => 'input-text required-entry'])->label('Điện thoại') ?>
                                </li>
                                <li class="fields row">
                                    <?= $form->field($model, 'shipping_address')->textInput(['class' => 'input-text required-entry'])->label('Địa chỉ') ?>
                                </li>
                                <li class="fields row">
                                    <?= $form->field($model, 'content')->textarea(['class' => 'input-text required-entry']) ?>
                                </li>
                                <li class="fields row">
                                    <?= $form->field($model, 'captcha')->capcha(['class' => 'input-text required-entry','placeholder' => 'Nhập mã bảo mật'])->label('') ?>
                                </li>
                            </ul>
                        </fieldset>
                    </li>


                </ul>
                <div class="opc-wrapper-opc">
                    <button type="submit" title="Mua hàng" class="button btn-checkout opc-btn-checkout">Mua hàng</button>
                </div>

            </div>
            <?php GlobalActiveForm::end(); ?>


        </div>

    </div>
</div>