<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\payments;
class PaymentsModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\payments\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
