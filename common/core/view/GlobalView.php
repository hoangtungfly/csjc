<?php

/**
 * view base class
 * 
 * @author Nguyen Anh Dung
 */

namespace common\core\view;

use common\utilities\UtilityFile;
use common\utilities\UtilityUrl;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\AssetBundle;
use yii\web\View;

class GlobalView extends View {
    use \common\core\traitphp\ControllerViewTrait;
    public function createUrl($route, $param = []) {
        return UtilityUrl::createUrl($route, $param);
    }

    public function createAbsoluteUrl($route, $param = []) {
        return UtilityUrl::createAbsoluteUrl($route, $param);
    }

    /**
     * get current url with param
     * @param boolen $createUrl createUrl or not
     * @param array $param get with param or not
     */
    public static function currentUrl(array $params = [], $scheme = false) {
        return UtilityUrl::getCurrentUrl($params, $scheme);
    }

    /**
     * get current url without param
     * @param boolen $createUrl createUrl or not
     * @param array $param get with param or not
     */
    public static function currentUrlBase(array $params = [], $absoulute = false) {
        return UtilityUrl::getCurrentUrlBase($params, $absoulute);
    }

    /**
     * @phongph
     * get single param
     * for POST, GET method
     * @param string $name Description
     * @return string
     */
    public function getParam($name) {
        return $this->context->getParam($name);
    }

    /**
     * @phongph
     * 
     * get all params
     * @return array
     */
    public function getParams($type = 'GET') {
        return $this->context->getParams($type);
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
        return $this->context->getPOST($name);
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
        return $this->context->getGET($name);
    }

    /**
     * @author Phongph
     * check ajax request or not
     */
    public function isAjax() {
        return $this->context->isAjax();
    }

    /**
     * get pagetitle
     * 
     * @return \common\models\page\PageTitlte
     */
    public function getPageTitleModel($params = []) {
        return $this->context->getPageTitleModel($params);
    }

    public function registerJsFile($url, $options = [], $key = null) {
        if (!preg_match('/^http/', $url)) {
            $url = str_replace(PHP_SELF, '', $url);
            $url = HTTP_HOST . $url;
        }
        $key = $key ? : Yii::getAlias($url);
        $depends = ArrayHelper::remove($options, 'depends', []);

        if (empty($depends)) {
            $position = ArrayHelper::remove($options, 'position', self::POS_END);
            $this->jsFiles[$position][$key] = Html::jsFile($url, $options);
        } else {
            $this->getAssetManager()->bundles[$key] = new AssetBundle([
                'baseUrl' => '',
                'js' => [strncmp($url, '//', 2) === 0 ? $url : ltrim($url, '/')],
                'jsOptions' => $options,
                'depends' => (array) $depends,
            ]);
            $this->registerAssetBundle($key);
        }
    }

    public function registerCssFile($url, $options = [], $key = null) {
        if (!preg_match('/^http/', $url)) {
            $url = str_replace(PHP_SELF, '', $url);
            $url = HTTP_HOST . $url;
        }
        $key = $key ? : Yii::getAlias($url);
        $depends = ArrayHelper::remove($options, 'depends', []);
        if (empty($depends)) {
            $this->cssFiles[$key] = Html::cssFile($url, $options);
        } else {
            $this->getAssetManager()->bundles[$key] = new AssetBundle([
                'baseUrl' => '',
                'css' => [strncmp($url, '//', 2) === 0 ? $url : ltrim($url, '/')],
                'cssOptions' => $options,
                'depends' => (array) $depends,
            ]);
            $this->registerAssetBundle($key);
        }
    }

    protected function renderHeadHtml() {
        $lines = [];
        if (!empty($this->metaTags)) {
            $lines[] = implode("\n", $this->metaTags);
        }
        if (!empty($this->linkTags)) {
            $lines[] = implode("\n", $this->linkTags);
        }
        if (!empty($this->cssFiles) && app()->controller->module->id != WEBNAME) {
            $lines[] = implode("\n", $this->cssFiles);
        }
        if (!empty($this->css)) {
            $lines[] = implode("\n", $this->css);
        }
        if (!empty($this->jsFiles[self::POS_HEAD])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_HEAD]);
        }
        if (!empty($this->js[self::POS_HEAD])) {
            $lines[] = Html::script(implode("\n", $this->js[self::POS_HEAD]), ['type' => 'text/javascript']);
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }


    protected function renderBodyBeginHtml() {
        $lines = [];
        if(app()->controller->module->id == WEBNAME) {
            if($this->cssFiles && count($this->cssFiles)) {
                $lines[] = "<noscript>".implode("\n", $this->cssFiles)."</noscript>";
            }
        }
        if (!empty($this->jsFiles[self::POS_BEGIN])) {
            $lines[] = implode("\n", $this->jsFiles[self::POS_BEGIN]);
        }
        if (!empty($this->js[self::POS_BEGIN])) {
            $lines[] = Html::script(implode("\n", $this->js[self::POS_BEGIN]), ['type' => 'text/javascript']);
        }

        return empty($lines) ? '' : implode("\n", $lines);
    }

    protected function renderBodyEndHtml($ajaxMode) {
        $lines = [];

        if (!empty($this->jsFiles[self::POS_END])) {
            $script_begin = [];
            $script_middle = [];
            $script_end = [];
            foreach ($this->jsFiles[self::POS_END] as $key => $value) {
                if (preg_match('/jquery(|-1\.9\.1\.min)\.js|dienlanh/', $value)) {
                    $script_begin[] = $value;
                    unset($this->jsFiles[self::POS_END][$key]);
                } else if (preg_match('/yii/', $value)) {
                    $script_middle[] = $value;
                    unset($this->jsFiles[self::POS_END][$key]);
                } else if (preg_match('/common|notifit|history|cart|wishlist/', $value)) {
                    $script_end[] = $value;
                    unset($this->jsFiles[self::POS_END][$key]);
                }
            }
            $lines[] = implode("\n", $script_begin);
            $lines[] = implode("\n", $script_middle);
            $lines[] = implode("\n", $this->jsFiles[self::POS_END]);
            $lines[] = implode("\n", $script_end);
        }

        if ($ajaxMode) {
            $scripts = [];
            if (!empty($this->js[self::POS_END])) {
                $scripts[] = implode("\n", $this->js[self::POS_END]);
            }
            if (!empty($this->js[self::POS_READY])) {
                $scripts[] = implode("\n", $this->js[self::POS_READY]);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $scripts[] = implode("\n", $this->js[self::POS_LOAD]);
            }
            if (!empty($scripts)) {
                $lines[] = Html::script(implode("\n", $scripts), ['type' => 'text/javascript']);
            }
        } else {
            if (!empty($this->js[self::POS_END])) {
                $lines[] = Html::script(implode("\n", $this->js[self::POS_END]), ['type' => 'text/javascript']);
            }
            if (!empty($this->js[self::POS_READY])) {
                $js = "jQuery(document).ready(function () {\n" . implode("\n", $this->js[self::POS_READY]) . "\n});";
                $lines[] = Html::script($js, ['type' => 'text/javascript']);
            }
            if (!empty($this->js[self::POS_LOAD])) {
                $js = "jQuery(window).load(function () {\n" . implode("\n", $this->js[self::POS_LOAD]) . "\n});";
                $lines[] = Html::script($js, ['type' => 'text/javascript']);
            }
        }
        if (!empty($this->cssFiles) && app()->controller->module->id == WEBNAME) {
            $css_file = '<script>
            var array_head_css = ["' . implode('","', array_keys($this->cssFiles)) . '"];
            var tag_head = document.getElementsByTagName("head");
            for (var i = 0; i< array_head_css.length;i++) {
                var head_link = document.createElement("link");
                head_link.href = array_head_css[i];
                head_link.rel = "stylesheet";
                tag_head[0].appendChild(head_link);
            }
            </script>
            ';
            $script_end_body = '<script>$(document).ready(function(e){if(document.getElementsByClassName("overlay-bg").length){document.getElementsByClassName("overlay-bg")[0].remove()};});</script>';
//            return $css_file . (empty($lines) ? '' : implode("\n", $lines)) . $script_end_body;
            return implode("\n", $this->cssFiles) . (empty($lines) ? '' : implode("\n", $lines)) . $script_end_body;
        } else {
            return empty($lines) ? '' : implode("\n", $lines);
        }
    }

    public function renderCache($view, $params = array(), $context = null) {
        $v = explode('/', $view);
        $v = $v[count($v) - 1];
        $view_cache = APPLICATION_PATH . '/cache/file/' . $v . '.php';
        if (is_file($view_cache)) {
            return parent::render('@cache/file/' . $v);
        } else {
            $content = parent::render($view, $params, $context);
            UtilityFile::fileputcontents($view_cache, $content);
            return $content;
        }
    }

}
