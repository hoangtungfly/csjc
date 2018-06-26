<?php

namespace application\aiem\components;

use common\core\controllers\GlobalController;

class AiemController extends GlobalController {
    public $layout = '@webmain/views/layouts/main';
    public $alias;
    public $contact = 0;
    
    public function beforeAction($action) {
        $this->setSessionLanguage();
        return parent::beforeAction($action);
    }
}