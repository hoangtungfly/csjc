<?php
    $field_options['class'] = 'setting_multiallmenu';
    $field_options['data-mappingid'] = $modelField->mapping_id;
    echo $form->field($model,$modelField->field_name,[
        'template' => '<div class="ohidden">{input}</div><div class="clear"></div>{error}'
    ])->hiddenInput($field_options);
?>