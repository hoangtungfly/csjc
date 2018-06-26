<?='<?php'?>

/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\<?=$model->module?>;
class <?=$module?>Module extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\<?=$model->module?>\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
