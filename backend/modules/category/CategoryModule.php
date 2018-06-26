<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\category;
class CategoryModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\category\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
