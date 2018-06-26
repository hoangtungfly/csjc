<?php

    use yii\helpers\ArrayHelper;

    if(isset($field_options['callfunction']) && trim($field_options['callfunction']) != "") {
        $callfunction = trim($field_options['callfunction']);
        $data = common\utilities\UtilityArray::callFunction($callfunction);
    } else {
        if($field_options['options'] && count($field_options['options']) > 0) {
            $data = ArrayHelper::map($field_options['options'],'value','label');
            if($model->isNewRecord) {
                foreach ($field_options['options'] as $item) {
                    if($item->checked) {
                        $model->$name = $item->value;
                        break;
                    }
                }
            }
        }
    }
    unset($field_options['callfunction']);
    unset($field_options['options']);
    $field_options['class'] = 'setting_customercolorpicker';
    echo $form->field($model,$modelField->field_name)->dropDownList($data,$field_options);
?>