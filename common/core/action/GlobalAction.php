<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */

namespace common\core\action;

use common\utilities\UtilityUrl;
use Yii;
use yii\helpers\Html;
use yii\rest\Action;
use yii\web\Controller;

class GlobalAction extends Action {

    /**
     * @phongph
     * get single param
     * for POST, GET method
     * @param string $name Description
     * @return string
     */
    public function getParam($name) {
        if (($get = $this->getGET($name)) !== null) {
            return $get;
        } else if (($post = $this->getPOST($name)) !== null) {
            return $post;
        }
        return null;
    }

    /**
     * @phongph
     * 
     * get all params
     * @return array
     */
    public function getParams($type = 'GET') {
        if (strtolower($type) == 'get')
            return $this->getGET(null);
        else
            return $this->getPOST(null);
    }

    /**
     * @phongph
     * 
     * get POST param
     * for only POST method
     * @param string $name
     * @return type
     */
    public function getPOST($name = null) {
        $value = Yii::$app->request->post($name);
        if (!is_array($value)) {
            $value = trim($value);
            if ($value == '') {
                $value = NULL;
            }
        }
        return $value;
    }

    /**
     * @phongph
     * 
     * get GET param
     * for only GET method
     * @param string $name
     * @return type
     */
    public function getGET($name = null) {
        if ($name === null) {
            return Yii::$app->request->getQueryParams();
        }
        $value = Yii::$app->request->getQueryParam($name);

        return $value;
    }

    /**
     * @author Phongph
     * check ajax request or not
     */
    public function isAjax() {
        return Yii::$app->request->getIsAjax();
    }

    public function createUrl($route, $params = []) {
        return UtilityUrl::createUrl($route, $params);
    }

    public function createAbsoluteUrl($route, $params = []) {
        return UtilityUrl::createAbsoluteUrl($route, $params);
    }
    
    public function ARender($view,$params = []) {
        $r = r()->get();
        if(isset($r['partials'])) {
            $html = $this->renderPartial($view,$params);
            if(ANGULARJS) {
                @file_put_contents(DIR_LINKPUBLIC . $view . '.html',Html::decode($html));
            }
            return $html;
        } else {
            return $this->renderContent(null);
        }
    }
}
