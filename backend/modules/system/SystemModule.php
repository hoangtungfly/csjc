<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\system;
class SystemModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\system\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
