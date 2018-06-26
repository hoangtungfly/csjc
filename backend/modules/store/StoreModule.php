<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\store;
class StoreModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\store\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
