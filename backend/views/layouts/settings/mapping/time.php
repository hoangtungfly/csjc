<?= $form->field($model, $modelField->field_name, [
    'template'  => '<div class="input-group bootstrap-timepicker">{input}'.'<span class="input-group-addon">
    <i class="fa fa-clock-o bigger-110"></i>
</span></div>'.'{error}',
    'options'   => [
        'class' => '',
    ],
])->textInput(array('class' => 'form-control setting_timepicker')); ?>