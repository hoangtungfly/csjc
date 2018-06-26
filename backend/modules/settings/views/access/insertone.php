<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
$dsn = app()->components['db']['dsn'];
$array = explode('=',$dsn);
$db_name = $array[count($array) - 1];
$listTable = app()->db->createCommand("SHOW TABLES")->queryAll();
$idStr = 'Tables_in_'.$db_name;
$list = ArrayHelper::map($listTable, $idStr, $idStr);
?>
<form method="POST">
        <div class="col-sm-6">
            <label>Table name: </label> 
            <?php
            echo Html::dropDownList('table', '', $list,['class' => 'setting_chosen','width:300px;']);
            ?>
        </div>
        <div class="col-sm-6">
            <label style="width: 100%;">Attribute: </label>
            <input class="setting_json" type="hidden" name="attribute" />
        </div>
    <div class="clear"></div>
    <textarea id="value" name="value" class="form-control" style="height: 500px;"></textarea>
    <input type="submit" value="Save" class="btn btn-primary" style="border: none;" />
</form>