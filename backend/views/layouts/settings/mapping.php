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
    $type = $modelField->field_type;
    $file = __DIR__ . '/mapping/' . $modelField->field_type;
    if(isset($field_options['attributes'])){
        $attributes = $field_options['attributes'];
        unset($field_options['attributes']);
        foreach($attributes as $key=>$value){
            if($value->value && $value->value{0} == '/' && !preg_match('~^'.MAIN_ROUTE.'~', $value->value)) {
                $value->value = MAIN_ROUTE . $value->value;
            }
            $field_options[$value->label] = $value->value;
        }
    }
    unset($field_options['required']);
    if (file_exists($file . '.php') && $modelField->field_name != "") {
        if(trim($modelField->js) != "") {
            cs()->registerJs($modelField->js);
        }
        echo $this->render('mapping/' . $modelField->field_type, array(
            'field_options' => $field_options,
            'model' => $model,
            'modelField' => $modelField,
            'form' => $form,
            'modelForm' => $modelForm,
        ));
    }
?>