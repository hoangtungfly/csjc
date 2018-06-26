<?php
echo $form->field($model,$modelField->field_name)->textInput(array('class'=>'setting_icon col-sm-12','data-href'=>$this->createUrl('/settings/access/icon')));