<?php
namespace common\core\traitphp;

use Yii;
use yii\web\Cookie;

trait ControllerViewTrait{
    
    public function getCookie($name) {
        $cookies_reading = Yii::$app->request->cookies;
        return isset($cookies_reading[$name]) ? $cookies_reading[$name]->value : null;
    }
    
    public function setCookie($name,$value) {
        $cookies_response = Yii::$app->response->cookies;
        $cookies_response->add(new Cookie([
            'name' => $name,
            'value' => $value,
        ]));
    }
}