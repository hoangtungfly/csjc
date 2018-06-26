<?php

/**
 * @author Phong Pham Hong
 * 
 * show all flashes 
 */

namespace common\core\widgets;

use Yii;

class Flashes extends \yii\base\Widget {

    // time show flash
    public $time = 7000;
    // key of flash
    public $key = '';

    public function run() {
        if ($this->key != '') {
            $this->showFlash($this->key, Yii::$app->session->getFlash($this->key, null, true));
        } else {
            $errors = Yii::$app->session->getAllFlashes(true);
            if ($errors) {
//                regCssFile('js/notifit/notifit.css');
//                regJsFile('js/notifit/notifit.js');
                foreach ($errors as $k => $v) {
                    $this->showFlash($k, $v);
                }
            }
        }
    }

    private function showFlash($k = 'info', $v) {
        if ($v == '') {
            return false;
        }
        if ($k == 'echo_text') {
            echo $v;
            return true;
        }

        $this->getView()->registerJs('
                    $(document).ready(function(e){
                        notif({
                                type: "' . $k . '",
                                msg: "' . $v . '",
                                position: "bottom",
                                fade: true,
                                timeout: ' . $this->time . ',
                            });
                    });
                        ', \yii\web\View::POS_READY
        );
    }

}
