<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\settings;
class SettingsModule extends \yii\base\Module{
    public $controllerNamespace = 'backend\modules\settings\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}
