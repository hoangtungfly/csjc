<?php
use common\core\form\GlobalActiveForm;
?>
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
                                ->label(Yii::t('lib','language_name')) ?>
                        
                        <?= $form->field($model,'color')
                                ->textInput([
                                        'placeHolder' => Yii::t('lib','language_code'),
                                        'class' => 'col-xs-12',
                                ])
                                ->label(Yii::t('lib','language_code')) ?>
                        
                      <?= $form->field($model,'status')
                                ->hiddenInput(array('class'=>'setting_checkbox'))->label(Yii::t('lib','status')) ?>

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