<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BackendAsset
 *
 * @author hanguyenhai
 */
namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;
class BackendAsset extends AssetBundle{
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
        'js/notifit/notifit.css',
        'js/chosen/chosen.min.css',
        "css/bootstrap-multiselect.min.css",
        "css/datepicker.min.css",
        "css/bootstrap-timepicker.min.css",
        "css/daterangepicker.min.css",
        "css/colorpicker.min.css",
        'js/formbuilder/vendor/css/vendor.css',
        'js/formbuilder/dist/formbuilder.css',
        'js/perfect-scrollbar/perfect-scrollbar.css',
    ];
    public $js = [
        'js/bootstrap.min.js',
//        'js/ace-elements.min.js',
//        'js/ace.min.js',
        'js/notifit/notifit.js',
        'js/chosen/chosen.jquery.min.js',
        'js/jquery.history.min.js',
        
        'js/bootstrap-multiselect.min.js',
        'js/jquery.inputlimiter.1.3.1.min.js',
        'js/bootstrap-datepicker.min.js',
        'js/bootstrap-timepicker.min.js',
        'js/moment.min.js',
        'js/daterangepicker.min.js',
        'js/bootstrap-datetimepicker.min.js',
        'js/bootstrap-colorpicker.min.js',
        'js/ckeditor/ckeditor.js',
        'js/stickytooltip.js',
        'js/jquery.nestable.min.js',
        'js/landingpage.js',
        'js/function.js',
        'js/fn.js',
        'js/main.js',
        'js/admin.js',
        'js/buildform.js',
        'js/perfect-scrollbar/perfect-scrollbar.js',
        'http://maps.googleapis.com/maps/api/js?key=AIzaSyA78XuWkWfMAWYdi3A5DGFLPAjmzpFl3JU',
        'js/map.js',
    ];
    public $depends = [
    ];
    
    public function init() {
        regJsFile([
            'js/jquery.2.1.1.min.js',
            'js/ace-extra.min.js',
            'js/jquery-ui.min.js',
        ],true,  View::POS_HEAD);
        regJsFile([
            "js/formbuilder/vendor/js/vendor.js",
            "js/formbuilder/dist/formbuilder.js",
            "js/formbuilder/owl.js",
        ],true,  View::POS_BEGIN);
        parent::init();
    }
}