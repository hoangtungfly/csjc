<?php


namespace frontend\controllers\restcontrollers;

use common\core\action\GlobalAction;


class Count extends GlobalAction {

    public function run() {
        $table_name = preg_replace('/[^a-zA-Z-_]+/','',$this->getParam('table_name'));
        $id = (int)$this->getParam('id');
        app()->db->createCommand("update `".$table_name."` set count = count + 1 where id = " . $id)->execute();
        return [];
    }

}
