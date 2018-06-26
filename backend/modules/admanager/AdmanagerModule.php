<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\admanager;
class AdmanagerModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\admanager\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
