<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\company;
class CompanyModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\company\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
