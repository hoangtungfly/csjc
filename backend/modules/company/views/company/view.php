<?php

use common\models\admin\SettingsFieldSearch;
use common\models\admin\SettingsFormSearch;
use common\models\admin\SettingsMappingSearch;
use common\utilities\UtilityArray;
use common\utilities\UtilityDateTime;
use yii\helpers\ArrayHelper;
$UtilityDateTime = new UtilityDateTime;
$primaryKey = $model->getKey();

$listForms = SettingsFormSearch::find()->where('table_id = '.$this->context->table_id)->orderBy('odr')->all();
$listFieldsAll = SettingsFieldSearch::find()->where('table_id = '.$this->context->table_id)->all();
$listFields = array();
if($listFieldsAll) {
    foreach($listFieldsAll as $key => $item) {
        $listFields[$item->form_id][] = $item;
    }
}
?>
<div class="page-header">
    <h1>View partner <?= $model->name ?></h1>
</div>
<div class="col-sm-12 plr0" style="margin-top:20px;">
<?php
$dem = 1;
?>
<?php foreach($listForms as $key=>$modelForm) { ?>
    <div class="col-sm-12 plr0">
            <?php foreach($listFields[$modelForm->form_id] as $key1=>$modelField){ ?>
            <div class="col-sm-12 plr0" style="margin-bottom: 12px;">
                <?php
                    $field_options = (array) json_decode($modelField->field_options);
                    $field_options['placeHolder'] = Yii::t('admin', $modelField->label);
                    $name = $modelField->field_name;
                ?>
                <label class="pl0 col-sm-3 control-label no-padding-right D-form-label"><?=$field_options['placeHolder'] . (($modelField->required == 1) ? ' <span>*</span>' : '')?></label>
                <div class="col-sm-8 pr0">
                    <?php 
                    $value = $model->$name;
                    $flagAttr = true;
                    if(isset($field_options['attributes']) && count($field_options['attributes']) > 0) {
                        foreach($field_options['attributes'] as $key => $item) {
                            if($item->label == 'alias') {
                                $array = explode(',',$item->value);
                                $a = $array[0]; 
                                if(isset($array[1])) {
                                    $b = $array[1];
                                    $value = $model->$a->$b;
                                    $flagAttr = false;
                                    break;
                                }
                            }
                        }
                    } 
                    if($flagAttr) {
                        if(isset($field_options['callfunction']) && trim($field_options['callfunction']) != "") {
                            $callfunction = trim($field_options['callfunction']);
                            $data = UtilityArray::callFunction($callfunction);
                            $value = $data[$value];
                        } else if($modelField->mapping_id != 0) {
                            $data = SettingsMappingSearch::mappingAll($modelField->mapping_id);
                            $value = isset($data[$value]) ? $data[$value] : '';
                        } else if(isset($field_options['options']) && count($field_options['options']) > 0 && isset($field_options['options'][0]->value)) {
                            $data = ArrayHelper::map($field_options['options'],'value','label');
                            $value = $data[$value];
                        }
                    }
                    switch($modelField->field_type) {
                        case 'checkbox': $value = '<input disabled="true" type="checkbox" checked="'.($value == 1 ? 'true' : 'false').'" class="ace" /><span class="lbl"></span>'; break;
                        case 'onoff': $value = '<input disabled="true" type="checkbox" checked="'.($value == 1 ? 'true' : 'false').'" class="ace" /><span class="lbl"></span>'; break;
                        case 'datetimepicker': 
                            $value = UtilityDateTime::formatDateTime($value); 
//                            $value = $UtilityDateTime->getDateTime($value);
                            break;
                    }
                    ?>
                    <?= nl2br($value) ?>
                </div>
            </div>
            <?php $dem++;} ?>
    </div>
<?php } ?>
</div>