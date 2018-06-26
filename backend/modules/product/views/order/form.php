<?php

use common\core\form\GlobalActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$statusList = common\models\lib\LibOrderStatus::getOrderStatus();
$countryList = \common\models\lib\LibCountries::getAllCountry();
$stateList = \common\models\lib\LibState::getStates();
$shippingList = \common\models\lib\LibShippingMethod::getShippingMethods();
?>
<div class="page-header">
    <h1>Create new order</h1>
</div>
<div class = "row">
    <div class = "col-xs-9" style = "width:900px;">
        <div class = "col-xs-12 panel-group accordion-style1 accordion-style2">
            <?php
            $form = GlobalActiveForm::begin([
                        'id' => "orderform",
                        'enableAjaxValidation' => true,
                        "action" => Url::to(['order/save', 'id' => $model->id]),
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
                        <?= $form->field($model, 'customer_firstname')->textInput(['placeholder' => "First name"])->label('First name') ?>
                        <?= $form->field($model, 'customer_lastname')->textInput(['placeholder' => "Last name"])->label('Last name') ?>
                        <?= $form->field($model, 'customer_email')->textInput(['placeholder' => "Email"])->label('Email'); ?>
                        <?= $form->field($model, 'customer_phone')->textInput(['placeholder' => "Phone"])->label('Phone'); ?>
                        <?= $form->field($model, 'customer_company')->textInput(['placeholder' => "Company"])->label('Company') ?>
                        <?= $form->field($model, 'customer_address')->textInput(['placeholder' => "Address 1"])->label('Address 1') ?>
                        <?= $form->field($model, 'customer_address_two')->textInput(['placeholder' => "Address 2"])->label('Address 2') ?>  

                        <?=
                        $form->field($model, 'country_id')->dropDownList($countryList, [
                            'include_blank_option' => '',
                            'placeHolder' => 'Country',
                            '' => '',
                            'class' => ' setting_chosen',
                            'style' => ' max-width:400px;width:400px;',
                            'data-id' => $model->country_id,
                        ])->label('Country')
                        ?> 
                        <?=
                        $form->field($model, 'state_id')->dropDownList($stateList, [
                            'include_blank_option' => '',
                            'placeHolder' => 'State',
                            '' => '',
                            'class' => ' setting_chosen',
                            'style' => ' max-width:400px;width:400px;',
                            'data-id' => $model->state_id,
                        ])->label('State')
                        ?>                         
                        <?= $form->field($model, 'city')->textInput(['placeholder' => "City"])->label('City') ?>
                        <?= $form->field($model, 'post_code')->textInput(['placeholder' => "Post code"]) ?>                                                

                        <?=
                        $form->field($model, 'shipping_method')->dropDownList($shippingList, [
                            'include_blank_option' => '',
                            'placeHolder' => 'Shipping Method',
                            '' => '',
                            'class' => ' setting_chosen',
                            'style' => ' max-width:400px;width:400px;',
                            'data-id' => $model->shipping_method,
                        ])->label('Shipping Method')
                        ?>  
                        <?=
                        $form->field($model, 'status')->dropDownList($statusList, [
                            'include_blank_option' => '',
                            'placeHolder' => 'Status',
                            '' => '',
                            'class' => ' setting_chosen',
                            'style' => ' max-width:400px;width:400px;',
                            'data-id' => $model->status,
                        ])->label('Order Status')
                        ?> 
                        <div class="product_area">
                            
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class = "col-sm-12" style = "margin-bottom: 10px;">
                <div class = "col-sm-12 D_form_submit" style = "text-align:center;">
                    <input type = "hidden" value = "http://mesop.com/product/order/index" name = "urlb" />
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary', 'style' => 'border:0px;']) ?>
                    <a class = "btn btn-success D_cancel" data-href = "http://mesop.com/product/order/index" style = "border:0px;">Back</a>
                    </form>
                </div>
            </div> 
<?php GlobalActiveForm::end(); ?>
        </div>               
    </div>
</div>




