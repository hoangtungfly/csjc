<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\project;
class ProjectModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\project\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
