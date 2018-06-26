<div class="row">
    <div class="col-xs-8 col-sm-12">
        <div class="input-group">
            <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
            </span>
            <?= $form->field($model, $modelField->field_name)->textInput(array('class' => 'form-control setting_daterangepicker')) ?>
        </div>
    </div>
</div>