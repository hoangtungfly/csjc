<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\admin;
class AdminModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\admin\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
