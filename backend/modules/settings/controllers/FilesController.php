<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\models\admin\SettingsFiles;
class FilesController extends BackendController {

    public function actionUpload() {
        set_time_limit(10000000);
        if ($this->isAjax()) {
            $upload = $_FILES['upload'];
            $tmp = $this->getParam('tmp');
            $id = (int)$this->getparam('did');
            if (is_array($upload['name'])) {
                $count = count($upload['name']);
                $notify = array();
                for ($i = 0; $i < $count; $i++) {
                    $file['name'] = $upload['name'][$i];
                    $file['tmp_name'] = $upload['tmp_name'][$i];
                    $file['type'] = $upload['type'][$i];
                    $file['error'] = $upload['error'][$i];
                    $file['size'] = $upload['size'][$i];
                    $notify[] = SettingsFiles::upload($file, $tmp, $id);
                }
                if (count($notify) > 0) {
                    $this->notifyImages($notify);
                } else {
                    $this->jsonResponse(400);
                }
            } else {
                $array = SettingsFiles::upload($upload, $tmp, $id);
                $this->notifyImages($array);
            }
        }
    }

    public function notifyImages($notify) {
        echo json_encode($notify);
    }
    
    public function actionRemoveimage() {
        $id = (int)$this->getParam('id');
        $model = SettingsFiles::findOne($id);
        if($model) {
            $model->delete();
        }
    }
    
    public function actionAddlink() {
        $link = $this->getParam('link');
        $table_name = $this->getParam('table_name');
        $this->notifyImages(SettingsFiles::updateFileByLink($link, $table_name));
    }

}
