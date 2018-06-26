<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\models\admin\SettingsImages;
class ImagesController extends BackendController {

    public function actionUpload() {
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
                    $notify[] = SettingsImages::upload($file, $tmp, $id);
                }
                if (count($notify) > 0)
                    $this->notifyImages($notify);
            } else {
                $array = SettingsImages::upload($upload, $tmp, $id);
                $this->notifyImages($array);
            }
        }
    }
    
    public function actionAddlink() {
        $link = $this->getParam('link');
        $table_name = $this->getParam('table_name');
        $this->notifyImages(SettingsImages::updateImageByLink($link, $table_name));
    }

    public function notifyImages($notify) {
        echo json_encode($notify);
    }
    
    public function actionRemoveimage() {
        $id = (int)$this->getParam('id');
        $model = SettingsImages::findOne($id);
        if($model) {
            $model->delete();
        }
    }

}
