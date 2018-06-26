<?php
use common\core\enums\admin\AdminEnum;
use common\models\admin\SettingsFieldSearch;
use common\models\admin\SettingsFormSearch;
use common\utilities\UtilityArray;
use common\utilities\UtilityHtmlFormat;
use common\core\form\GlobalActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>

<input type="hidden" id="table_id" value="85" />
<input type="hidden" id="tmp" value="user" />
<input type="hidden" id="did" value="<?=$model->isNewRecord ? '0' : $model->user_id?>" />
<?php
$get = r()->get();
$form = GlobalActiveForm::begin([
    'id' => $idform,
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
<input type="hidden" name="imageid" id="imageid" value="" />
<input type="hidden" name="imageiddelete" id="imageiddelete" value="" />
<input type="hidden" name="imageiddeletename" id="imageiddeletename" value="" />

<input type="hidden" name="fileid" id="fileid" value="" />
<input type="hidden" name="fileiddelete" id="fileiddelete" value="" />
<input type="hidden" name="fileiddeletename" id="fileiddeletename" value="" />
<div class="row">
    <div class="col-xs-9 d_form_left" style="width:1050px;">
        <div class="col-xs-12 panel-group accordion-style1 accordion-style2">
            <div class="panel panel-default col-sm-12 pl0 pr0" data-id="87">
        <div class="panel-collapse collapse in" data-status="in" id="faq-2-1" aria-expanded="true">
        <div class="panel-body form-group form-group-panel">
                                        <?php $model->app_type = '3';  
                echo Html::activeHiddenInput($model, 'app_type')?>
                                
                    
            <?= $form->field($model,'email')
->textInput([
	'placeHolder' => 'Email',
	'class' => 'col-xs-12',
])
->label('Email') ?>                            
                    
            <?= $form->field($model,'password')->passwordInput([
	'placeHolder' => 'Password',
	'class' => 'col-xs-12',
])->label('Password') ?>                            
                    
            <?= $form->field($model,'firstname')
->textInput([
	'placeHolder' => 'Firstname',
	'class' => 'col-xs-12',
])
->label('Firstname') ?>                            
                    
            <?= $form->field($model,'lastname')
->textInput([
	'placeHolder' => 'Lastname',
	'class' => 'col-xs-12',
])
->label('Lastname') ?>                            
                    
            <?= $form->field($model,'display_name')
->textInput([
	'placeHolder' => 'Display name',
	'class' => 'col-xs-12',
])
->label('Display name') ?>                            
                    
            
<?php
if($model->status === null) {
    $model->status = 0;
}
?>
<?= $form->field($model,'status')->hiddenInput(array('class'=>'setting_checkbox'))->label('Status') ?>                    </div>
    </div>
</div>            <div class="col-sm-12" style="margin-bottom: 10px;">
                <div class="col-sm-12 D_form_submit" style="text-align:center;">
                    <input type="hidden" value="<?= base64_encode($this->createUrl('/settings/user/index',$get)) ?>" name="urlb" />
                    <input type="submit" class="btn btn-primary" id="D_update_submit" value="<?=Yii::t("admin","Save")?>" style="border:0px;" />
                    <a class="btn btn-success D_cancel" href="<?= $this->createUrl('/settings/user/index',$get) ?>" style="border:0px;"><?=Yii::t("admin","Back")?></a>
                </div>
            </div>


        </div><!-- form -->
    </div>
    <div class="d_form_right">
        
    </div>
</div>
<?php GlobalActiveForm::end(); ?>