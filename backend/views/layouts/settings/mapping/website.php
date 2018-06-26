<?php
$field_options['placeHolder'] = 'http://';
$field_options['style'] = 'width:100%;';
echo $form->field($model,$modelField->field_name)->textInput($field_options);