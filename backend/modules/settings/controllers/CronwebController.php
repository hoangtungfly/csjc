<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\models\admin\SettingsWebcronSearch;

class CronwebController extends BackendController {
    public $viewCreateweblink;
    
    public function init() {
        parent::init();
    }
    
    public function actionCron() {
        $id = (int)$this->getParam('id');
        if($id && ($model = SettingsWebcronSearch::findOne($id))) {
            $model->cron();
            $this->jsonResponse(200);
        }
        $this->jsonResponse(400,'Has not found id = ' . $id);
    }
    
}