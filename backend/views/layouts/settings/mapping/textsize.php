<?php

$name = $modelField->field_name;
if(isset($field_options['data-default']) && $model->$name != "") {
    $model->$name = $field_options['data-default'];
}
if($model->isNewRecord) {
    unset($field_options['readonly']);
}
if(isset($field_options['data-choicetype'])) {
    $arrayType = explode("||",$field_options['data-choicetype']);
    $arrayValue = isset($field_options['data-choice']) ? explode("||",$field_options['data-choice']) : array();
    foreach ($arrayType as $key => $label) {
        $label = trim($label);
        $value = isset($arrayValue[$key]) ? trim($arrayValue[$key]) : '';
?>
<div class="col-sm-4">
    <label>
        <input type="radio" name="ace_datachoice<?=$name?>" class="ace ace_datachoice" value="<?=$value?>" />
        <span class="lbl"> <?=$label?></span>
    </label>
</div>

<?php
    }
}
echo $form->field($model,$name)->textInput($field_options);