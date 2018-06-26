<?php
use yii\widgets\ActiveForm;
?>

<!--BEGIN BREADCRUMB-->
<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="<?= $this->createUrl('/') ?>"><?=Yii::t('admin','Home')?></a>
        </li>
        <li><a class="breadcrumbsa" href="<?=$this->createUrl('/system/syspages/create')?>"><?=Yii::t("sys_page","creaete_title")?></a></li>        
    </ul>
</div>
<!--END BREADCRUMB-->

<!--BEGIN TITLE-->
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span><?=Yii::t('sys_page','creaete_title')?> </span></h1>    
    <div class="clear"></div>
</div>
<!--END TITLE-->

<?php 
    $form = ActiveForm::begin([
        'id'=>'',
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'validateOnChange' => false,
        'validateOnSubmit' => false,
        'validateOnBlur' => false,
        'action' => $this->createUrl('create'),
        'fieldConfig' => [
            'template' => '<div class="col-sm-12" style="margin-bottom:12px;">'
                            . '{label}{input}<div class="clear"></div>{error}'
                            .'</div>'
                    ,
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
            <div class="panel panel-default col-sm-12" style="possition:relative;">
                <div id="faq-2-1" class="panel-collapse collapse in" aria-expanded="true" data-status="in">
                    <div class="panel-body form-group" style="padding-left:0px;padding-right:0px;padding-bottom: 0px;margin-bottom: 0px;margin-top:0px;padding-top:3px;">
                        <?=$form->field($model,'title')?>
                        <?=$form->field($model,'title')?>
                        <?=$form->field($model,'title')?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-12" style="margin-bottom: 10px;">
        <div class="col-sm-12 D_form_submit" style="text-align:center;">
            <input type="submit" class="btn btn-primary" id="D_update_submit" value="<?= Yii::t("admin", "Save") ?>" style="border:0px;" />
            <a class="btn btn-success D_cancel" href="" style="border:0px;"><?= Yii::t("admin", "Back") ?></a>
        </div>
    </div>

    <div class="d_form_right">
    </div>
</div>

