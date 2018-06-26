<?php

namespace common\core\controllers;

use common\models\settings\SystemSettingSearch;
use common\utilities\UtilityFile;
use common\utilities\UtilityUrl;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;

class GlobalController extends Controller {
    use \common\core\traitphp\ControllerViewTrait;

    public $pageModel = null;
    public $sys_user_type;
    public $homeUrl = '/';
    public $alias;
    public $contact;
    public $meta_title = false;
    public $meta_keyword = false;
    public $meta_description = false;
    
    public function beforeAction($action) {
        $this->setSessionLanguage();
        return parent::beforeAction($action);
    }
    
    public function setSessionLanguage() {
        if(isset($_REQUEST['lang'])) {
            session()->set('lang', $_REQUEST['lang']);
            app()->language = $_REQUEST['lang'];
        } else {
            if(session()->has('lang')) {
                app()->language = session()->get('lang');
            } else {
                app()->language = app()->params['language'];
            }
        }
    }
    
    public function setAtrrbuteConfigByContext($config) {
        if(isset($config['meta_title']) && isset($this->meta_title) && $this->meta_title) {
            $config['meta_title'] = $this->meta_title;
        }
        if(isset($config['meta_keyword']) && isset($this->meta_keyword) && $this->meta_keyword) {
            $config['meta_keyword'] = $this->meta_keyword;
        }
        if(isset($config['meta_description']) && isset($this->meta_description) && $this->meta_description) {
            $config['meta_description'] = $this->meta_description;
        }
        return $config;
    }
    
    public function setSeoAttribute($array, $model = false) {
        if(isset($array['meta_title']) && $array['meta_title']) {
            $this->meta_title = $array['meta_title'];
        }
        if(isset($array['meta_keyword']) && $array['meta_keyword']) {
            $this->meta_keyword = $array['meta_keyword'];
        }
        if(isset($array['meta_description']) && $array['meta_description']) {
            $this->meta_description = $array['meta_description'];
        }
        if(isset($array['image']) && $array['image'] && $model) {
            $this->logo_facebook = $model->getimage([600,315],$model->image);
        }
    }

    public function init() {
        $this->homeUrl = MAIN_ROUTE . '/';
        parent::init();
    }

    public function redirectMobile() {
        return false;
    }

    public function _getStatusCodeMessage($status) {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Oops! Something has gone wrong and the page you were looking for could not be found! Try the <a href="" class="color-blue">home page.</a>',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => "We're sorry because something went wrong, please try again!",
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
        );
        return (isset($codes[$status])) ? $codes[$status] : 'Unknown Result';
    }

    public function pageNotFound($mess = '') {
        $this->renderPageError(404, $mess);
    }

    public function renderPageError($code = null, $message = '') {
        # return json string if request is ajax
        if ($this->isAjax()) {
            $this->jsonResponse($code, $message);
        }
        # return homeurl if app is not inited
        if (Yii::$app->controller === null) {
            $this->redirect(HOST_PUBLIC);
        }
//        $this->layout = "@app/modules/candidate/views/layouts/main";
        if (preg_match('/backend/', Yii::getAlias('@app'))) {
            $this->layout = "@app/views/layouts/main";
        }
        # set pagetitle
        if ($this->getParam('swtohome')) {
            $this->redirect(Yii::$app->homeUrl);
            app()->end();
        }
        echo parent::render('@common/views/error_handel', array('code' => $code, 'message' => $message));
        app()->end();
    }

    public function pageDenied($mess = '') {
        $this->renderPageError(403, $mess);
    }

    public function sendResponse($status = 200, $body = '', $contentType = 'application/json') {
        // Set the status
        $statusHeader = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($statusHeader);
        // Set the content type
        header('Content-type: ' . $contentType);

        echo $body;
        Yii::$app->end();
    }

    public function jsonResponse($code, $messages = null) {
        $this->layout = false;
        header('Content-type: application/json');
        $m = is_string($messages) ? trim($messages) : $messages;
        $m = $m === null ? $this->_getStatusCodeMessage($code) : $m;
        echo json_encode(array('code' => $code, 'data' => $m));
        Yii::$app->end();
    }

    public function jsonResponseNotFound($messages = '') {
        echo $this->jsonResponse(404, $messages);
    }

    public function jsonResponsePageDenied($messages = '') {
        echo $this->jsonResponse(403, $messages);
    }

    public function jsonResponseSuccess($messages = '') {
        $this->jsonResponse(200, $messages);
    }

    public function getParam($name) {
        if (($get = $this->getGET($name)) !== null) {
            return $get;
        } else if (($post = $this->getPOST($name)) !== null) {
            return $post;
        }
        return null;
    }

    public function getParams($type = 'GET') {
        if (strtolower($type) == 'get')
            return $this->getGET(null);
        else
            return $this->getPOST(null);
    }

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

    public function getGET($name = null) {
        if ($name === null) {
            return Yii::$app->request->getQueryParams();
        }
        $value = Yii::$app->request->getQueryParam($name);

        return $value;
    }

    public function validateModelAjax($model, $formId = '') {
        $ajaxF = $this->getPOST('ajax');
        if ($this->isAjax() && $ajaxF && (
                $formId == '' || ($ajaxF == $formId)
                )) {
            $model->load($this->getPOST());
            $this->validateModel($model, true);
        }
    }

    public function showErrorModelAjax($model) {
        $result = [];
        if (count($model->getErrors())) {
            $result = [];
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[Html::getInputId($model, $attribute)] = $errors;
            }
        }
        echo Json::encode($result);
        Yii::$app->end();
    }

    public function validateModel($model, $die = true) {
        $model->validate();
        if (count($model->getErrors())) {
            $result = [];
            foreach ($model->getErrors() as $attribute => $errors) {
                $result[Html::getInputId($model, $attribute)] = $errors;
            }
            if ($die) {
                echo Json::encode($result);
                Yii::$app->end();
            }
            return Json::encode($result);
        }
        return false;
    }
    
    public function jsonValidate($model) {
        echo json_encode(ActiveForm::validate($model));
        Yii::$app->end();
    }

    public function isAjax() {
        return Yii::$app->request->getIsAjax();
    }

    public function createUrl($route, $params = []) {
        return UtilityUrl::createUrl($route, $params);
    }

    public function createAbsoluteUrl($route, $params = []) {
        return UtilityUrl::createAbsoluteUrl($route, $params);
    }

    public function getPageTitleModel($param = array(), $controller = '') {
        if (!$this->pageModel) {
            $this->pageModel = new PageTitle();
            $this->pageModel->app_id = $this->sys_user_type;
            $this->pageModel->getPageData($param);
            return $this->pageModel;
        }
        return $this->pageModel;
    }

    public function Prender($view, $params = [], $code = 0) {
        if ($this->isAjax()) {
            $html = $this->renderAjax($view, $params);
            if ($code) {
                $this->jsonResponse($code, $html);
            } else {
                echo $html;
                app()->end();
            }
        } else {
            return $this->render($view, $params);
        }
    }

    public function pageLogin() {
        if (Yii::$app->request->isAjax) {
            return $this->redirect($this->createUrl('/home/login'));
        }
        return UtilityUrl::redirectLoginPage();
    }

    public function ARender($view, $params = []) {
        $r = r()->get();
        if (isset($r['partials'])) {
            $html = $this->renderPartial($view, $params);
            if (ANGULARJS_WRITEFILE) {
                UtilityFile::fileputcontents(DIR_LINKPUBLIC_PARTIAL . app()->controller->id . '/' . $view . '.html', Html::decode($html));
            }
            return $html;
        } else {
            if ($this->isAjax()) {
                $html = $this->renderAjax($view, $params);
                if ($code) {
                    $this->jsonResponse($code, $html);
                } else {
                    echo $html;
                    app()->end();
                }
            } else {
                return $this->render('@frontend/views/layouts/index');
            }
        }
    }

    public $a_config = false;

    public function array_config($type = 'system_settings_common') {
        if (!$this->a_config) {
            $list = SystemSettingSearch::getAll($type);
            $model = new SystemSettingSearch();
            foreach ($list as $key => $value) {
                if (in_array($key, ['logo', 'logo_footer','favico']) || preg_match('/image$/', $key)) {
                    $list[$key] = $model->getimage([], $value);
                }
                if (preg_match('/^\[\{"/', $value)) {
                    $value = json_decode($value);
                    if (is_array($value)) {
                        foreach ($value as $item) {
                            if (isset($item->swf)) {
                                $item->swf = $model->getimage([], $item->swf);
                            }
                            if (isset($item->image)) {
                                $item->image = $model->getimage([], $item->image);
                            }
                        }
                    }
                    $list[$key] = $value;
                }
            }
            $this->a_config = $list;
        }

        return $this->a_config;
    }


    public function jsonencode($result) {
        echo json_encode($result);
        app()->end();
    }
    
    
    

}
