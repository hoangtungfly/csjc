<?php
    $field_options['class'] = 'setting_role';
    $field_options['data-mappingid'] = $modelField->mapping_id;
echo $form->field($model,$modelField->field_name)->hiddenInput($field_options);