<?php
use yii\widgets\ActiveForm;
?>
<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="<?= $this->createUrl('/') ?>"><?=Yii::t('admin','Home')?></a>
        </li>
        <li><a class="breadcrumbsa" href="<?=$this->createUrl('/user/details/index')?>"><?=Yii::t("admin","User")?></a></li>        
    </ul>
</div>
<div class="row">
    <div class="col-xs-9" style="width:1050px;">
        <div class="col-xs-12 panel-group accordion-style1 accordion-style2">
            <?php
            $form = ActiveForm::begin([
                        'enableClientValidation' => false,
                        'enableAjaxValidation' => true,
                        'validateOnChange' => false,
                        'validateOnSubmit' => false,
                        'validateOnBlur' => false,
                        'action' => '',
                        'fieldConfig' => [
                            'template' => '{input}<div class="clear"></div>{error}',
                        ],
                        'options' => [
                            'class' => 'form-horizontal formsortable',
                            'role' => 'role',
                        ]
            ]);
            ?>
            <?=$form->field($model,'name')?>
            <?=$form->field($model,'name')?>
            <?=$form->field($model,'name')?>
            <?=$form->field($model,'name')?>
        </div>
    </div>
</div>