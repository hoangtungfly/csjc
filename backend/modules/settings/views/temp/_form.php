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

<input type="hidden" id="table_id" value="95" />
<input type="hidden" id="tmp" value="temp" />
<input type="hidden" id="did" value="<?=$model->isNewRecord ? '0' : $model->id?>" />
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
            <div class="panel panel-default col-sm-12 pl0 pr0" data-id="137">
        <div class="panel-collapse collapse in" data-status="in" id="faq-2-1" aria-expanded="true">
        <div class="panel-body form-group form-group-panel">
                                        
                    
            <?= $form->field($model,'multiallmenu',[
'template'  => '{label}<div class="form-group-input-child col-sm-10"><div>{input}</div><div class="clear"></div>{description}{error}</div>',
])->hiddenInput([
	'placeHolder' => 'Multiallmenu',
	'class' => 'setting_multiallmenu',
	'data-mappingid' => 50,
])->label('Multiallmenu') ?>                            
                    
            <?= $form->field($model,'role')
->hiddenInput([
	'placeHolder' => 'Role',
	'class' => 'setting_role',
	'data-mappingid' => 50,
])
->label('Role') ?>                            
                    
            <?= $form->field($model,'multimenu')->hiddenInput([
	'placeHolder' => 'Multimenu',
	'data-url1' => '/settings/load/multimenu',
	'data-url2' => '/settings/load/menu',
	'data-classcommon' => 'common\models\admin\MenuAdminSearch',
	'class' => 'setting_multimenu',
	'data-mappingid' => 0,
])->label('Multimenu') ?>                            
                    
            
<?php $listSettingsMapping = ['' => '-- '.Yii::t('admin', 'Multiselect').' --'] + ArrayHelper::map(app()->db->createCommand('select `mapping_id`,`mapping_name` from `settings_mapping`')->queryAll(),'mapping_id','mapping_name'); ?>

<?= $form->field($model,'multiselect')->dropDownList($listSettingsMapping,[
	'placeHolder' => 'Multiselect',
	'class' => 'setting_multiselect',
	'multiple' => '1',
])->label('Multiselect') ?>                            
                    
            
<?= $form->field($model,'array')->hiddenInput([
	'placeHolder' => 'Array',
	'data-count' => '2',
	'data-placeholder' => 'label,value',
	'class' => 'setting_array',
])->label('Array') ?>
                            
                    
            
<?= $form->field($model,'arrayjson')->hiddenInput([
	'placeHolder' => 'Arrayjson',
	'data-name1' => 'v',
	'data-name2' => 'b',
	'data-placeholder' => 'label,value',
	'class' => 'setting_arrayjson',
])->label('Arrayjson') ?>
                            
                    
            
<?= $form->field($model,'json')->hiddenInput([
	'placeHolder' => 'Json',
	'data-placeholder' => 'name,value',
	'class' => 'setting_json',
])->label('Json') ?>                            
                    
            
<?php
$list_checkboxbig = [
	'2' => '2',
	'3' => '3',
	'4' => '4',
];
 
$html = backend\models\UtilityAdmin::getHtmlCheckboxBig($list_checkboxbig,$model,'checkboxbig');
?>
        
<?= $form->field($model,'checkboxbig')->hiddenInput([
	'placeHolder' => 'Checkboxbig',
	'class' => 'setting_checkboxbig',
])->label('Checkboxbig')->description($html ) ?>                            
                    
            
<?= $form->field($model,'colorpicker',[
    'template'  => '{label}<div class="form-group-input-child col-sm-10 bootstrap-colorpicker">{input}<div class="clear"></div>{description}{error}</div>',
    ])->textInput([
	'placeHolder' => 'Colorpicker',
	'class' => 'col-xs-12 setting_colorpicker',
])->label('Colorpicker') ?>
                            
                    
            <?= $form->field($model, 'content')->textArea(array('class'=>'setting_ckeditor'))->label('Content') ?>                            
                    
            <?= $form->field($model, 'contentsmall')->textArea(array('class'=>'setting_ckeditor_small'))->label('Contentsmall') ?>
                            
                    
            <?php $list_customercolorpicker = [
	'#ac725e' => '#ac725e',
	'#d06b64' => '#d06b64',
	'#f83a22' => '#f83a22',
	'#fa573c' => '#fa573c',
	'#ff7537' => '#ff7537',
	'#ffad46' => '#ffad46',
	'#42d692' => '#42d692',
	'#16a765' => '#16a765',
	'#7bd148' => '#7bd148',
	'#b3dc6c' => '#b3dc6c',
	'#fbe983' => '#fbe983',
	'#fad165' => '#fad165',
	'#92e1c0' => '#92e1c0',
	'#9fe1e7' => '#9fe1e7',
	'#9fc6e7' => '#9fc6e7',
	'#4986e7' => '#4986e7',
	'#b99aff' => '#b99aff',
	'#9a9cff' => '#9a9cff',
	'#c2c2c2' => '#c2c2c2',
	'#cabdbf' => '#cabdbf',
	'#cca6ac' => '#cca6ac',
	'#f691b2' => '#f691b2',
	'#cd74e6' => '#cd74e6',
	'#a47ae2' => '#a47ae2',
	'#555' => '#555',
];
 ?>
<?= $form->field($model,'customercolorpicker')->dropDownList($list_customercolorpicker,[
	'include_blank_option' => '',
	'placeHolder' => 'Customercolorpicker',
	'class' => 'setting_customercolorpicker',
])->label('Customercolorpicker') ?>                            
                    
            <?= $form->field($model, 'date',[
    'template'  => '{label}<div class="form-group-input-child col-sm-10 input-group">{input}<input type="text" class="form-control setting_date-picker" data-date-format="'.FORMAT_DATE_INPUT.'" /><span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span><div class="clear"></div>{description}{error}</div>',
])->hiddenInput()->label('Date') ?>
                            
                    
            <?= $form->field($model, 'daterangepicker',[
    'template'  => '{label}<div class="form-group-input-child col-sm-10 input-group"><span class="input-group-addon" style="height:30px;"><i class="fa fa-calendar bigger-110"></i></span>{input}<div class="clear"></div></div>{description}{error}',
])->textInput(array('class' => 'form-control setting_daterangepicker'))->label('Daterangepicker') ?>
                            
                    
            <?= $form->field($model, 'datetimepicker',[
    'template'  => '{label}<div class="form-group-input-child col-sm-10 input-group">{input}<span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span><div class="clear"></div>{description}{error}</div>',
])->textInput(array('class' => 'form-control setting_datetimepicker'))->label('Datetimepicker') ?>                            
                    
            
<?php $listSettingsMapping = ['' => '-- '.Yii::t('admin', 'Dropdown').' --'] + ArrayHelper::map(app()->db->createCommand('select `mapping_id`,`mapping_name` from `settings_mapping`')->queryAll(),'mapping_id','mapping_name'); ?>

<?= $form->field($model,'dropdown')->dropDownList($listSettingsMapping,[
	'include_blank_option' => '',
	'placeHolder' => 'Dropdown',
	'' => '',
	'class' => ' setting_chosen',
	'style' => ' max-width:400px;width:400px;',
	'data-id' => $model->dropdown,
])->label('Dropdown') ?>                            
                    
            <?= $form->field($model,'icon')->textInput(array('class'=>'setting_icon col-sm-12','data-href'=>'/settings/access/icon'))->label('Icon') ?>                            
                    
            <?= $form->field($model,'text')
->textInput([
	'placeHolder' => 'Text',
	'class' => 'col-xs-12',
])
->label('Text') ?>                            
                    
            <?= $form->field($model,'password')->passwordInput([
	'placeHolder' => 'Password',
	'class' => 'col-xs-12',
])->label('Password') ?>                            
                    
            
<?php
if($model->checkbox === null) {
    $model->checkbox = 0;
}
?>
<?= $form->field($model,'checkbox')->hiddenInput(array('class'=>'setting_checkbox'))->label('Checkbox') ?>                            
                    
            
<?= $form->field($model,'manyfiles')->hiddenInput([
	'placeHolder' => 'Manyfiles',
	'class' => 'setting_manyfiles',
])->label('Manyfiles') ?>                            
                    
            
<?= $form->field($model,'manyimages')->hiddenInput([
	'placeHolder' => 'Manyimages',
	'class' => 'setting_manyimages',
])->label('Manyimages') ?>                            
                    
            
<?php
if($model->number != "") {
    $model->number = number_format($model->number);
}
?>

<?= $form->field($model, 'number',[
    'template'  => '{label}<div class="form-group-input-child col-sm-10">{input}'.Html::textInput('', $model->number, [
	'placeHolder' => 'Number',
	'class' => ' isnumber numberformat D_loadurl col-xs-12',
	'onblur' => '$(this).prev().val($(this).val().replace(/,/gi,""));',
]).'<div class="clear"></div>{description}{error}</div>',
])->hiddenInput()->label('Number') ?>                            
                    
            
<?= $form->field($model,'onefile')->hiddenInput([
	'placeHolder' => 'Onefile',
	'class' => 'setting_onefile col-sm-8',
])->label('Onefile') ?>                            
                    
            
<?= $form->field($model,'oneimage')->textInput([
	'placeHolder' => 'Oneimage',
	'class' => 'setting_oneimage col-sm-10',
])->label('Oneimage') ?>                            
                    
            <?php

if($model->onoff === null) {
    $model->onoff = 1;
}

?>

<?= $form->field($model,'onoff')->hiddenInput(array('class'=>'setting_onoff'))->label('Onoff') ?>                            
                    
            
<?php 

$list_radio = [
	'2' => '2',
	'3' => '3',
	'4' => '4',
];

$html = \backend\models\UtilityAdmin::getHtmlRadio($list_radio,$model,'radio', '');
?>

<?= $form->field($model,'radio',[
    'template'  => '{label}<div class="form-group-input-child col-sm-10 setting_radio">{input}{description}<div class="clear"></div>{error}</div>',
    'options' => [
        ]])->hiddenInput([
	'placeHolder' => 'Radio',
	'class' => 'setting_radio_input',
])
        ->label('Radio')
        ->description($html) ?>                            
                    
                                        
                    
            
<?= $form->field($model,'textarea')
->textArea([
	'placeHolder' => 'Textarea',
	'class' => ' form-control setting_limited',
	'style' => 'padding-right:6px;',
])
->label('Textarea') ?>                            
                    
            <?= $form->field($model, 'time', [
    'template'  => '{label}<div class="form-group-input-child col-sm-10 input-group bootstrap-timepicker">{input}<span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span><div class="clear"></div>{description}{error}</div>',
])->textInput(array('class' => 'form-control setting_timepicker'))
->label('Time') ?>                            
                    
            <?= $form->field($model,'website')
->textInput([
	'placeHolder' => 'http://',
	'style' => 'width:100%;',
])
->label('Website') ?>                    </div>
    </div>
</div>            <div class="col-sm-12" style="margin-bottom: 10px;">
                <div class="col-sm-12 D_form_submit" style="text-align:center;">
                    <input type="hidden" value="<?= base64_encode($this->createUrl('/settings/temp/index',$get)) ?>" name="urlb" />
                    <input type="submit" class="btn btn-primary" id="D_update_submit" value="<?=Yii::t("admin","Save")?>" style="border:0px;" />
                    <a class="btn btn-success D_cancel" href="<?= $this->createUrl('/settings/temp/index',$get) ?>" style="border:0px;"><?=Yii::t("admin","Back")?></a>
                </div>
            </div>


        </div><!-- form -->
    </div>
    <div class="d_form_right">
        
    </div>
</div>
<?php GlobalActiveForm::end(); ?>