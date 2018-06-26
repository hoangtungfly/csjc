<?php
    $field_options['class'] = isset($field_options['class']) ? $field_options['class'] . 'setting_multimenu' : 'setting_multimenu';
    $field_options['data-mappingid'] = $modelField->mapping_id;
    echo $form->field($model,$modelField->field_name)->hiddenInput($field_options);
?>