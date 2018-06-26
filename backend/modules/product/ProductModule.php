<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\product;
class ProductModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\product\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
