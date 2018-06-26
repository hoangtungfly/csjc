<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class LoginAsset extends AssetBundle {

    public $basePath = APPLICATION_PATH . '/backend/web/';
    public $baseUrl = "/";

    public function __construct($config = array()) {
        $this->baseUrl .= DIRECTORY_MAIN_2;
        $this->basePath .= DIRECTORY_MAIN_2;
        parent::__construct($config);
    }

    public $css = [
        'css/bootstrap.min.css',
        'css/font-awesome/4.2.0/css/font-awesome.min.css',
        'css/fonts/fonts.googleapis.com.css',
        'css/ace.min.css',
        'css/ace-rtl.min.css',
    ];
    
    public function init() {
        regJsFile([
            'js/jquery.2.1.1.min.js',
        ],true, View::POS_HEAD);
    }

}
