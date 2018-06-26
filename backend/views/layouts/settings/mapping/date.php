<?= $form->field($model, $modelField->field_name,[
    'template'  => '{input}<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span><div class="clear"></div>{error}',
    'options' => [
        'class' => 'input-group ',
        'style' => 'padding-right:0px;padding-left:0px',
    ]
])->textInput([
    'class' => 'form-control setting_date-picker',
    'data-date-format' => FORMAT_DATE_INPUT,
]); ?>
