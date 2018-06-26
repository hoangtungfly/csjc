<?php

namespace application\aiem\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webmain/public';
    public $baseUrl = LINK_PUBLIC;
    public $version_css = VERSION_CSS;
    public $version_js = VERION_JS;
    
    public function init() {
        $css = [
            'css/bootstrap.min.css',
            'css/font-awesome.min.css',
            'css/chosen.min.css',
            'css/owl.carousel.min.css',
            'css/owl.theme.min.css',
            'css/owl.transitions.min.css',
            'css/main.css',
            'css/styles.css',
            'css/home.css',
            'css/nivo-slider.css',
        ];
       $js = [
            'js/jquery-1.9.1.min.js',
            'js/bootstrap.min.js',
            'js/main.min.js',
            'js/owl.carousel.min.js',
            'js/chosen.jquery.min.js',
        ];
        if(ENABLE_VERSION) {
            if(count($css)) {
                foreach($css as $k => $v) {
                    $css[$k] = $v .'?v='.$this->version_css.time();
                }
            }
            
            if(count($js)) {
                foreach($js as $k => $v) {
                    $js[$k] = $v .'?v='.$this->version_js.time();
                }
            }
        }
        $this->css= $css;
        $this->js = $js;
        return parent::init();
    }
    
    public $depends = [
//        'yii\web\YiiAsset',
        'frontend\assets\CommonAsset',
    ];
}
