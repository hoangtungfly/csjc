<?php
    $field_options['class'] .= ' setting_colorpicker';
?>
<div class="control-group">
    <?=$form->field($model,$modelField->field_name,[
        'options'   => [
            'class' => 'bootstrap-colorpicker',
        ],
    ])->textInput($field_options);?>
</div>
