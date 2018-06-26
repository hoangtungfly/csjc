<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\metrixa;
class MetrixaModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\metrixa\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
