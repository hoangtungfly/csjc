<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\web;
class WebModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\web\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
