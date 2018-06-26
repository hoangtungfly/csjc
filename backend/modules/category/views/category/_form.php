<?php

use common\core\form\GlobalActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>

<!--BEGIN BREADCRUMB-->
<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="<?= $this->createUrl('/') ?>"><?=Yii::t('admin','Home')?></a>
        </li>
        <li><a class="breadcrumbsa" href="<?=$url?>"><?=$title?></a></li>        
    </ul>
</div>
<!--END BREADCRUMB-->

<!--BEGIN TITLE-->
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span><?=$title?> </span></h1>    
    <div class="clear"></div>
</div>
<!--END TITLE-->

<?php 
    $form = GlobalActiveForm::begin([
    'id' => $id_form,
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'validateOnChange' => false,
    'validateOnSubmit' => false,
    'validateOnBlur' => false,
    'fieldConfig' => [
        'template' => '{label}<div class="form-group-input-child col-sm-10">{input}<div class="clear"></div>{description}{error}</div>',
        'options'   => [
            'class' => 'col-sm-12 form-group-input',
        ],
        'labelOptions'  => [
            'class' => 'col-sm-2 control-label no-padding-right D-form-label',
        ],
    ],
    'options'   => [
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
                        <?= $form->field($model,'name')
                                ->textInput([
                                        'placeHolder' => 'Name',
                                        'class' => 'col-xs-12',
                                ])
                                ->label('Name') ?>
                        
                        <?= $form->field($model,'meta_title')
                                ->textInput([
                                        'placeHolder' => 'Meta title',
                                        'class' => 'col-xs-12',
                                ])
                                ->label('Meta title') ?>
                        
                     <?= $form->field($model,'meta_keyword')
                                ->textInput([
                                        'placeHolder' => 'Meta keyword',
                                        'class' => 'col-xs-12',
                                ])->label('Meta keyword') ?>  
                     <?= $form->field($model,'domain')
                                ->textInput([
                                        'placeHolder' => 'Domain',
                                        'class' => 'col-xs-12',
                                ])->label('Domain') ?>  
                        
                      <?= $form->field($model, 'limitproduct',[
                            'template'  => '{label}<div class="form-group-input-child col-sm-10">{input}'.Html::textInput('', $model->limitproduct, [
                                'placeHolder' => 'Limit product',
                                'class' => ' isnumber numberformat D_loadurl col-xs-12',
                                'onblur' => '$(this).prev().val($(this).val().replace(/,/gi,""));',
                        ]).'<div class="clear"></div>{description}{error}</div>',
                        ])->hiddenInput()->label('Limit product') ?> 
                    
                    <?= $form->field($model,'hyperlink')
                            ->textInput([
                                    'placeHolder' => 'http://',
                                    'class' => 'col-xs-12',
                            ])->label('Hyper link') ?> 
                     
                        <?php $listSettingsMapping = ['' => '-- '.Yii::t('admin', 'lang').' --'] + ArrayHelper::map(app()->db->createCommand('select `country_code`,`country_name` from `lib_countries`')->queryAll(),'country_code','country_name'); ?>

                        <?= $form->field($model,'lang')->dropDownList($listSettingsMapping,[
                                'include_blank_option' => '',
                                'placeHolder' => 'Dropdown',
                                '' => '',
                                'class' => ' setting_chosen',
                                'style' => ' max-width:400px;width:400px;',
                                'data-id' => $model->lang,
                        ])->label('Lang') ?>  
                        
                     <?= $form->field($model, 'meta_description')->textArea(array('class'=>'setting_ckeditor'))->label('Meta Description') ?>   
                    </div>
                </div>
            </div>  
        </div>
    </div>
    
    <div class="col-sm-12" style="margin-bottom: 10px;">
        <div class="col-sm-12 D_form_submit" style="text-align:center;">
            <input type="submit" class="btn btn-primary" id="D_update_submit" value="<?= Yii::t("sys_page", "save") ?>" style="border:0px;" />
            <a class="btn btn-success D_cancel" href= '.<?=base64decodeUrl($urlb)?>.' style="border:0px;"><?= Yii::t("sys_page", "back") ?></a>
        </div>
    </div>

    <div class="d_form_right">
    </div>
</div>
