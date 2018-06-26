<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\lib;
class LibModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\lib\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
