<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\models\admin\SettingsCronSearch;

class CronproductController extends BackendController {
    public function actionCron() {
        $id = (int) $this->getParam('id');
        set_time_limit(400000000);
        $model = SettingsCronSearch::findOne($id);
        if ($model) {
            $model->content_log = '';
            $model->cronAll();
        }
        $this->jsonResponse(200);
    }
    
    public function actionShowlog() {
        $id = (int) $this->getParam('id');
        $model = SettingsCronSearch::findOne($id);
        $html = '';
        if ($model) {
            $html = nl2br($model->content_log);
        }
        $this->jsonResponse(200,$html);
    }
}