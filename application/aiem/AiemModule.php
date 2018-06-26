<?php

namespace application\aiem;

use Yii;

class AiemModule extends \yii\base\Module
{
    public $controllerNamespace = 'application\aiem\controllers';
    
    public function init()
    {
        parent::init();
        $this->modules = [
        ];
    }
}