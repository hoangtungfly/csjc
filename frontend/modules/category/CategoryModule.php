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
namespace frontend\modules\category;
class CategoryModule extends \yii\base\Module{
    public $controllerNamespace = 'fontend\modules\category\controllers';
    
    public function init() {
        parent::init();
        $this->layout = '@app/views/layouts/main';
    }
}