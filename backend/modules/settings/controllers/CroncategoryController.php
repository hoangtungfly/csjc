<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\models\admin\SettingsCronSearch;

class CroncategoryController extends BackendController {
    
    public function actionCronmenu() {
        $id = (int) $this->getParam('id');
        $model = SettingsCronSearch::findOne($id);
        if ($model) {
            $model->content_log = '';
            $model->cronMenu();
        }
        $this->jsonResponse(200);
    }
    
    public function actionShowlog() {
        $id = (int) $this->getParam('id');
        $model = SettingsCronSearch::findOne($id);
        $html = '';
        if ($model) {
            $html = $model->content_log;
        }
        $this->jsonResponse(200,$html);
    }
}