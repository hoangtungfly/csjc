<?php

use common\core\enums\admin\AdminEnum;
use yii\helpers\Html;
$fbnameVal = 'fu_' . $form_id;
?>
<div id="listitems" style="display: inline-block; width: 100%;">
    <?php echo Html::hiddenInput('SettingsFormSearch[table_id]', $table_id, array('class' => 'itemid','id' => 'SettingsFormSearch_table_id')) ?>
    <?php echo Html::textarea('SettingsFormSearch[fields]', isset($modelForm['fields']) ? Html::encode($modelForm['fields']) : '',array('style' => 'display:none;','id' => 'SettingsFormSearch_fields')) ?>
    <?php echo Html::hiddenInput('SettingsFormSearch[form_id]', isset($modelForm['form_id']) ? $modelForm['form_id'] : '',array('id' => 'SettingsFormSearch_form_id')) ?>
    <div class="form-group col-md-12">
        <label class="col-sm-2 control-label" for="inputSchool"> Name  </label>
        <div class="col-sm-10">
            <input type="text" name="SettingsFormSearch[form_name]" value="<?php echo (isset($modelForm['form_name']) ? Html::encode($modelForm['form_name']) : ''); ?>" style="width:600px;" placeholder="Form name" class="itemname form-control txt-section">
            <div style="display: none;" class="errorMessage">Please complete this field</div>
        </div>
    </div>
    <div class="form-group col-md-12 frm-inline">
        <label class="col-sm-2 control-label" for="inputSchool"> Description </label>
        <div class="col-sm-10">
            <textarea  style="width:600px;" name="SettingsFormSearch[form_description]" class="itemdescription form-control"><?php echo (isset($modelForm['form_description']) ? Html::encode(str_replace("<br>", "\n", $modelForm['form_description'])) : ''); ?></textarea>
            <div style="display: none;" class="errorMessage">Please complete this field</div>
        </div>
    </div>
    <div class="form-group col-md-12 frm-inline">
        <label class="col-sm-2 control-label" for="inputSchool"> hide/ show </label>
        <div class="col-sm-10">
            <?php
            echo Html::activeHiddenInput($modelForm, 'hidden', array('class' => 'setting_onoff'));
            ?>
        </div>
    </div>
    <div class="form-group col-md-12 frm-inline">
        <label class="col-sm-2 control-label" for="inputSchool"> on/ off </label>
        <div class="col-sm-10">
            <?php
            echo Html::activeHiddenInput($modelForm, 'status', array('class' => 'setting_onoff'));
            ?>
        </div>
    </div>
    <div class="form-group col-md-12 frm-inline">
        <label class="col-sm-2 control-label" for="inputSchool"> One line </label>
        <div class="col-sm-10">
            <?php
            echo Html::activeDropDownList($modelForm, 'line', AdminEnum::lineNInN(), array('class' => 'setting_chosen_nosearch', 'style' => 'width:150px;'));
            ?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="fb-main drag-drop-box">
        <div class="fbitem drag-drop-box" id="<?= $fbnameVal ?>"></div>
    </div>
</div>
<script>
    $(document).ready(function(e) {
        fb = new Formbuilder({
            optionName: '<?= json_encode($tb) ?>',
            mappingName: '<?= json_encode($mapping) ?>',
            selector: '#<?= $fbnameVal ?>',
            bootstrapData: <?= $modelForm['fields'] == '' ? '[]' : $modelForm['fields'] ?>
        });
        owlCaro();
    });
</script>