<?php

use yii\widgets\ActiveForm;
$field_options = (array)json_decode($modelField->field_options);
$get = r()->get();
$form = ActiveForm::begin([
        'id' => 'update_fast_form',
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'validateOnChange' => false,
        'validateOnSubmit' => false,
        'validateOnBlur' => false,
        'action' => $this->createUrl('/settings/access/updatefastprocess',$get),
        'fieldConfig' => [
            'template' => '{input}{error}',
        ],
        'options'   => [
            'class' => 'form-horizontal formsortable',
            'role' => 'role',
            'onsubmit'  => 'return false;',
        ],
    ]);
?>
<input type="hidden" id="table_id" value="<?= $modelTable->table_id ?>" />
<input type="hidden" id="tmp" value="<?= $modelTable->table_name ?>" />
<input type="hidden" id="did" value="<?=$id?>" />
<div class="col-sm-12" style="padding-bottom: 10px;">
    <?php if($modelField->label != '') { ?>
    <div class="col-sm-2"><?=$modelField->label?></div>
    <?php } ?>
    <div class="col-sm-<?=$modelField->label != '' ? '10' : '12'?>">
        <?php
        if (isset($field_options['size'])) {
            switch ($field_options['size']) {
                case 'medium' : $field_options['class'] = 'col-xs-5';
                    break;
                case 'small' : $field_options['class'] = 'col-xs-9';
                    break;
                default : $field_options['class'] = 'col-xs-12';
                    break;
            }
            unset($field_options['size']);
        }
        if (isset($field_options['description'])) {
            unset($field_options['description']);
        }
        if(isset($field_options['attributes'])){
            $attributes = $field_options['attributes'];
            unset($field_options['attributes']);
            foreach($attributes as $key=>$value){
                $field_options[$value->label] = $value->value;
            }
        }
        echo $this->render('@app/views/layouts/settings/mapping/' . $modelField->field_type, array(
            'field_options' => $field_options,
            'model' => $model,
            'modelField' => $modelField,
            'form' => $form,
        ));
        ?>
    </div>
</div>


<?php ActiveForm::end(); ?>