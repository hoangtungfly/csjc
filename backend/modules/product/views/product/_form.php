<?php

use backend\models\UtilityAdmin;
use common\core\form\GlobalActiveForm;
use common\models\lib\LibColor;
use common\models\lib\LibCountries;
use common\models\lib\LibSize;
use common\widgets\tokeninput\TokenInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>
<?php
$form = GlobalActiveForm::begin([
            'id' => 'product_form',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'validateOnChange' => false,
            'validateOnSubmit' => true,
            'validateOnBlur' => false,
            'action' => $url,
            'fieldConfig' => [
                'template' => '{label}<div class="form-group-input-child col-sm-10">{input}<div class="clear"></div>{description}{error}</div>',
                'options' => [
                    'class' => 'col-sm-12 form-group-input',
                ],
                'labelOptions' => [
                    'class' => 'col-sm-2 control-label no-padding-right D-form-label',
                ],
            ],
            'options' => [
                'class' => 'form-horizontal formsortable',
                'role' => 'role',
            ]
        ]);
?>

<div class="row">
    <div class="col-xs-9 d_form_left" style="width:1050px;">
        <div class="col-xs-12 panel-group accordion-style1 accordion-style2">
            <div class="panel panel-default col-sm-12 pl0 pr0" data-id="137">
                <div class="panel-collapse collapse in" data-status="in" id="faq-2-1" aria-expanded="true">
                    <div class="panel-body form-group form-group-panel">
                        <?=
                                $form->field($model, 'name')
                                ->textInput([
                                    'placeHolder' => 'Text',
                                    'class' => 'col-xs-12',
                                ])
                                ->label('Name')
                        ?>   

                        <?=
                                $form->field($model, 'description')
                                ->textarea([
                                    'class' => 'setting_ckeditor',
                                ])
                                ->label('Desciption')
                        ?>   

                        <?=
                        $form->field($model, 'image', [
                            'options' => [
                                'class' => 'setting_oneupload form-group ',
                            ],
                        ])->textInput([
                            'placeHolder' => 'Oneimage',
                            'class' => 'setting_oneimage col-sm-10',
                        ])->label('Image')
                        ?>

                        <?=
                        $form->field($model, 'images')->hiddenInput([
                            'placeHolder' => 'Manyimages',
                            'class' => 'setting_manyimages',
                        ])->label('Images')
                        ?>    

                        <?=
                                $form->field($model, 'content')
                                ->textarea([
                                    'class' => 'setting_ckeditor',
                                ])
                                ->label('Content')
                        ?>   


                        <?=
                        $form->field($model, 'price', [
                            'template' => '{label}<div class="form-group-input-child col-sm-10">{input}' . Html::textInput('', 'price', [
                                'placeHolder' => 'Number',
                                'class' => ' isnumber numberformat D_loadurl col-xs-12',
                                'onblur' => '$(this).prev().val($(this).val().replace(/,/gi,""));',
                            ]) . '<div class="clear"></div>{description}{error}</div>',
                        ])->hiddenInput()->label('Price')
                        ?>   

                        <?=
                        $form->field($model, 'price_old', [
                            'template' => '{label}<div class="form-group-input-child col-sm-10">{input}' . Html::textInput('', 'price_old', [
                                'placeHolder' => 'Number',
                                'class' => ' isnumber numberformat D_loadurl col-xs-12',
                                'onblur' => '$(this).prev().val($(this).val().replace(/,/gi,""));',
                            ]) . '<div class="clear"></div>{description}{error}</div>',
                        ])->hiddenInput()->label('Price Old')
                        ?>   
                        <?=
                        $form->field($model, 'hot', [
                            'template' => '{label}<div class="form-group-input-child col-sm-10">{input}' . Html::textInput('', 'hot', [
                                'placeHolder' => 'Number',
                                'class' => ' isnumber numberformat D_loadurl col-xs-12',
                                'onblur' => '$(this).prev().val($(this).val().replace(/,/gi,""));',
                            ]) . '<div class="clear"></div>{description}{error}</div>',
                        ])->hiddenInput()->label('Hot')
                        ?>   

                        <?=
                        $form->field($model, 'status', [
                            'template' => '{label}<div class="form-group-input-child col-sm-10">{input}' . Html::textInput('', 'status', [
                                'placeHolder' => 'Number',
                                'class' => ' isnumber numberformat D_loadurl col-xs-12',
                                'onblur' => '$(this).prev().val($(this).val().replace(/,/gi,""));',
                            ]) . '<div class="clear"></div>{description}{error}</div>',
                        ])->hiddenInput()->label('Status')
                        ?>   

                      

                        <?=
                        $form->field($model, 'category_all_id', [
                            'template' => '{label}<div class="form-group-input-child col-sm-10"><div>{input}</div><div class="clear"></div>{description}{error}</div>',
                        ])->hiddenInput([
                            'placeHolder' => 'Multiallmenu',
                            'class' => 'setting_multiallmenu',
                            'data-mappingid' => 51,
                            'data-value' => ''
                        ])->label('Category')
                        ?>   

                        <?=
                        $form->field($model, 'views', [
                            'template' => '{label}<div class="form-group-input-child col-sm-10">{input}' . Html::textInput('', 'views', [
                                'placeHolder' => 'Number',
                                'class' => ' isnumber numberformat D_loadurl col-xs-12',
                                'onblur' => '$(this).prev().val($(this).val().replace(/,/gi,""));',
                            ]) . '<div class="clear"></div>{description}{error}</div>',
                        ])->hiddenInput()->label('Views')
                        ?>   

                        <?=
                                $form->field($model, 'meta_title')
                                ->textarea([
                                    'placeHolder' => 'Meta title',
                                    'class' => ' form-control setting_limited',
                                    'style' => 'padding-right:6px;',
                                ])
                                ->label('Name')
                        ?>   

                        <?=
                                $form->field($model, 'meta_keyword')
                                ->textarea([
                                    'placeHolder' => 'Meta Keyword',
                                    'class' => ' form-control setting_limited',
                                    'style' => 'padding-right:6px;',
                                ])
                                ->label('Meta Keyword')
                        ?>   

                        <?=
                                $form->field($model, 'meta_description')
                                ->textarea([
                                    'placeHolder' => 'Meta Description',
                                    'class' => ' form-control setting_limited',
                                    'style' => 'padding-right:6px;',
                                ])
                                ->label('Meta Description')
                        ?>   

                        <?=
                                $form->field($model, 'domain')
                                ->textInput([
                                    'placeHolder' => 'Text',
                                    'class' => 'col-xs-12',
                                ])
                                ->label('Domain')
                        ?>   

                        <?=
                                $form->field($model, 'code')
                                ->textInput([
                                    'placeHolder' => 'Text',
                                    'class' => 'col-xs-12',
                                ])
                                ->label('Code')
                        ?>   

                        <?=
                                $form->field($model, 'lang')
                                ->textInput([
                                    'placeHolder' => 'Text',
                                    'class' => 'col-xs-12',
                                ])
                                ->label('Lang')
                        ?>   




                        <?php
                        $model->location_id = LibCountries::getValueTokenInput($model->location_id, 'country_code', 'country_name');
                        echo $form->field($model, 'location_id', ['options' => [
                                'class' => 'form-group col-md-12'
                    ]])->widget(
                                TokenInput::className(), [
                            'url' => $this->createUrl('product/suggest'),
                            'options' => array(
                                'allowCreation' => false,
//                                'deleteText' => 'x',
                            ),
                        ])->label('Location');
                        ?> 

                        <?php
                        $list_checkboxbig = ArrayHelper::map(LibColor::find()->select('id,name')->asArray()->all(), 'id', 'name');
                        $html = UtilityAdmin::getHtmlCheckboxBig($list_checkboxbig, $model, 'color_id');
                        ?>

                        <?=
                        $form->field($model, 'color_id')->hiddenInput([
                            'placeHolder' => 'Checkboxbig',
                            'class' => 'setting_checkboxbig',
                        ])->label('Color')->description($html)
                        ?>                            



                        <?=
                        $form->field($model, 'size_id')->dropDownList(
                                ArrayHelper::map(LibSize::find()->select('id,size')->asArray()->all(), 'id', 'size')
                                , [
                            'include_blank_option' => '',
                            'placeHolder' => 'Dropdown',
                            '' => '',
                            'class' => ' setting_chosen',
                            'style' => ' max-width:400px;width:400px;',
                            'data-id' => 'location_id',
                        ])->label('Size')
                        ?>     
                    </div>
                </div>
            </div>
            <div class="col-sm-12" style="margin-bottom: 10px;">
                <div class="col-sm-12 D_form_submit" style="text-align:center;">
                    <input type="submit" class="btn btn-primary" id="D_update_submit" value="<?= Yii::t("admin", "Save") ?>" style="border:0px;" />
                    <a class="btn btn-success D_cancel" href="<?= $this->createUrl('/product/product/index') ?>" style="border:0px;"><?= Yii::t("admin", "Back") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php GlobalActiveForm::end(); ?>
