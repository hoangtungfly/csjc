<?php

use yii\helpers\Html;
$array = explode('\\',get_class($model));
$className = strtolower($array[count($array) - 1]);
?>
<div class="panel panel-default col-sm-12 pl0 pr0" data-id="<?= $modelForm->form_id ?>">
    <?php if ($modelForm->form_name != '') { ?>
        <div class="panel-heading">
            <a href="#faq-2-<?= $dem ?>" data-parent="#faq-list-<?= $dem ?>" 
               data-toggle="collapse" class="accordion-toggle <?= $modelForm->hidden == 0 ? 'collapsed' : '' ?>" aria-expanded="<?= $modelForm->hidden == 1 ? 'true' : 'false' ?>">
                <i class="smaller-80 ace-icon fa <?= $modelForm->hidden == 0 ? 'fa-chevron-right' : ' fa-chevron-down align-top' ?>" 
                   data-icon-hide="ace-icon fa fa-chevron-down align-top" 
                   data-icon-show="ace-icon fa fa-chevron-right"></i>&nbsp;<?= $modelForm->form_name ?>
            </a>
        </div>
    <?php } ?>
    <div class="panel-collapse collapse in" data-status="<?= $modelForm->hidden == 0 ? '' : 'in' ?>" id="faq-2-<?= $dem ?>" aria-expanded="true">
        <div class="panel-body form-group form-group-panel">
            <?php if ($modelForm->form_description != '') { ?>
                <div class="col-sm-12 form-group-description" ><?= $modelForm->form_description ?></div>
            <?php } ?>
            <?php
            $countField = 1;
            $totalField = count($listFields[$modelForm->form_id]);
            foreach ($listFields[$modelForm->form_id] as $key1 => $modelField) {
                if($modelField->status) {
                    if ($modelField->field_type != 'hidden') {
                        echo '<div class="col-sm-12 form-group-input" data-fieldid="'.$modelField->field_id.'">';
                        $field_options = (array) json_decode($modelField->field_options);
                        $field_options['placeHolder'] = Yii::t('admin', $modelField->label);

                        if ($modelField->required == 1)
                            $field_options['required'] = 'required';

                        if ($modelField->label != '') {
                            $optionLabel = array(
                                'class' => 'col-sm-2 control-label no-padding-right D-form-label',
                                'style' => 'padding-top:4px;',
                            );
                            echo Html::label($field_options['placeHolder'] . ($model->isAttributeRequired($modelField->field_name) ? ' <span>*</span>' : ''), $className . '-' . $modelField->field_name, $optionLabel);
                        }

                        unset($field_options['placeHolder']);

                        echo '<div class="col-sm-' . ($modelField->label != '' ? '10' : '12') . ' form-group-input-child">';

                        echo $this->render('mapping', array(
                            'model' => $model,
                            'modelField' => $modelField,
                            'form' => $form,
                            'modelForm' => $modelForm,
                            'countField' => $countField,
                            'totalField' => $totalField,
                            'field_options' => $field_options,
                        ));
                        if (isset($field_options['description']))
                            echo '<div class="col-sm-12 plr0 D_form_field_description">' . $field_options['description'] . '</div>';
                        echo '</div>';
                        echo '</div>';
                        $countField++;
                    }
                    else {
                        $name = $modelField->field_name;
                        $model->$name = $modelField->label;
                        echo Html::activeHiddenInput($model, $name);
                    }
                }
            }
            ?>
        </div>
    </div>
</div>