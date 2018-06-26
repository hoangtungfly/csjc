<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\common;
class CommonModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\common\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
