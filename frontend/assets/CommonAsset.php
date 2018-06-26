<?php
namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class CommonAsset extends AssetBundle
{
    public $basePath = '@webroot/frontend/web/';
    public $baseUrl = '/frontend/web/';
    public $baseMin = DEV_MIN;
    
    public function init(){
        $this->css = [
            "css/common{$this->baseMin}.css",
            "js/notifit/notifit.min.css",
        ];
            
        $this->js =[
            "js/jquery.history.min.js",
            "js/cart{$this->baseMin}.js",
            "js/wishlist{$this->baseMin}.js",
            "js/productcompare{$this->baseMin}.js",
            "js/common{$this->baseMin}.js",
            "js/common_event{$this->baseMin}.js",
            "js/notifit/notifit.min.js",
        ];
        parent::init();
    }
    
    public $jsOptions = [
        'position' => View::POS_END,
    ];
}