<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\video;
class VideoModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\video\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
