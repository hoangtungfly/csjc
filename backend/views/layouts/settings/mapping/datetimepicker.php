<?= $form->field($model, $modelField->field_name,[
    'template'  => '{input}<span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span><div class="clear"></div>{error}',
    'options' => [
        'class' => 'input-group ',
        'style' => 'padding-right:0px;padding-left:0px;',
    ]
])->textInput(['class' => 'form-control setting_datetimepicker','data-date-format' => FORMAT_DATETIME_INPUT]); ?>