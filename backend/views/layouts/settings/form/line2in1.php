<?php

use yii\helpers\Html;

$array = explode('\\', get_class($model));
$className = strtolower($array[count($array) - 1]);
?>
<div class="panel panel-default col-sm-12 plr0" style="possition:relative;" data-id="<?= $modelForm->form_id ?>">
<?php if ($modelForm->form_name != '') { ?>
        <div class="col-sm-3" style="height:100%;">
            <div class="D_panel" style="position: absolute;"><?= $modelForm->form_name ?></div>
        </div>
<?php } ?>
    <div class="col-sm-9" data-status="<?= $modelForm->hidden == 0 ? '' : 'in' ?>" id="faq-2-<?= $dem ?>" aria-expanded="true">
        <div class="panel-body form-group" style="padding-left:0px;padding-right:0px;padding-bottom: 0px;margin-bottom: 0px;margin-top:0px;padding-top:3px;">
            <?php if ($modelForm->form_description != '') { ?>
                <div class="col-sm-12" style="font-weight: bold;color:green;text-align: justify;"><?= $modelForm->form_description ?></div>
            <?php } ?>
            <?php
            $countField = 1;
            $totalField = count($listFields[$modelForm->form_id]);
            foreach ($listFields[$modelForm->form_id] as $key1 => $modelField) {
                if ($modelField->status) {
                    if ($modelField->field_type != 'hidden') {
                        if ($countField % 2 != 0) {
                            echo '<div class="col-sm-12">';
                        }
                        $field_options = (array) json_decode($modelField->field_options);
                        $field_options['placeHolder'] = Yii::t('admin', $modelField->label);
                        if ($modelField->required == 1)
                            $field_options['required'] = 'required';
                        echo '<div class="col-sm-6">';
                        if ($countField % 2 != 0) {
                            echo '<div class="fr">';
                        }
                        if ($modelField->label != '') {
                            $optionLabel = array(
                                'class' => 'control-label no-padding-right D-form-label',
                                'style' => 'white-space:nowrap;text-align:right !important;float:left;margin-right:5px;margin-top:5px;',
                            );
                            if ($field_options['placeHolder']) {
                                echo Html::label($field_options['placeHolder'] . (($modelField->required == 1) ? ' <span>*</span>' : ''), $className . '_' . $modelField->field_name, $optionLabel);
                            }
                        }

                        unset($field_options['placeHolder']);

                        echo '<div style="padding-right:0px;float:left;' . ($countField % 2 != 0 ? 'margin-top:5px;' : '') . '">';

                        echo $this->render('mapping', array(
                            'model' => $model,
                            'modelField' => $modelField,
                            'form' => $form,
                            'modelForm' => $modelForm,
                            'countField' => $countField,
                            'totalField' => $totalField,
                            'field_options' => $field_options,
                        ));
                        if ($countField % 2 != 0) {
                            echo '</div>';
                        }
                        echo '</div>';
                        echo '</div>';
                        if ($countField % 2 == 0 || $totalField == $countField) {
                            echo '</div>';
                        }

                        $countField++;
                    } else {
                        $name = $modelField->field_name;
                        $model->$name = $modelField->label;
                        echo Html::activeHiddenInput($model, $name, ['id' => $className . '_' . $name]);
                    }
                }
            }
            ?>
        </div>
    </div>
</div>