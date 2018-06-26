<?php


namespace frontend\controllers\restcontrollers;

use common\core\action\GlobalAction;
use common\models\settings\Useronline;
use common\models\settings\Webaccess;
use yii\validators\RequiredValidator;


class Countaccess extends GlobalAction {

    public function run() {
        $post = r()->post();
        Useronline::useronline($post['useronline_type'],$post);
        Webaccess::insertRecord($post);
        return [];
    }

}
