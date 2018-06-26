<?php

/**
 * Some function for process url
 *
 * @author phongphamhong
 * @date 11/22/2013
 */

namespace common\utilities;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;

class UtilityUrl {

    /**
     * detect request from mobile or not
     *  
     * @var boolen 
     */
    public static $isMobile = null;

    /**
     * store object for singeleton design pattern
     * 
     * @var UtilityUrl 
     */
    private static $instance;

    /**
     * 
     * @return \ClaUrl
     */
    public static function instance() {
        if (!self::$instance) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    /**
     * get current url with param
     * @param boolen $createUrl createUrl or not
     * @param array $param get with param or not
     */
    public static function getCurrentUrl(array $params = [], $scheme = false) {
        return \yii\helpers\BaseUrl::current($params, $scheme);
    }

    /**
     * get current url without param
     * @param boolen $createUrl createUrl or not
     * @param array $param get with param or not
     */
    public static function getCurrentUrlBase(array $params = [], $absoulute = false) {
        if ($absoulute) {
            return self::createAbsoluteUrl('/' . app()->controller->getRoute(), $params);
        }
        return self::createUrl('/' . app()->controller->getRoute(), $params);
    }

    public static function detectErrorFrom() {
        
    }

    /**
     * get sub domain of host
     * @return string
     */
    public static function getSubDommain() {
        $host = explode('.', parse_url(app()->getRequest()->hostInfo)['host']);
        if (count($host) > 2) {
            return array_shift($host);
        }
        return '';
    }

    /**
     * detect request from mobile device or not
     * 
     * @return boolen
     */
    public static function isMobile() {
        if (self::$isMobile === null) {
//            $subdomain = self::getSubDommain();
//            if ($subdomain == SUBDOMAIN_MEMBERMOBILE || $subdomain == SUBDOMAIN_PARTNERMOBILE) {
//                self::$isMobile = true;
//                return true;
//            }
            $v = (int) preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell'
                                . '|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|'
                                . 'j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|'
                                . 'philips|Windows Phone|sagem|sharp|sie-|smartphone|sony|symbian|'
                                . 't-mobile|telus|up\.browser|up\.link|vodafone|'
                                . 'wap|webos|wireless|xda|xoom|zte)/i', app()->getRequest()->userAgent);
            self::$isMobile = boolenval($v);
        }
        return self::$isMobile;
    }

    /**
     * redirect login page
     * @author Dung Nguyen Anh <dungnguyenanh@orenj.com>
     * @since version 1.0 16/09/2015
     * @param mixed $currenUrl : 
     *  false: disable get currenUrl
     *  string: url will be return
     * @param type $params
     */
    public static function redirectLoginPage($params = [], $urlreturn = '', $redirect = true) {
        $urlb = '';
        if ($urlreturn !== false) {
            if ($urlreturn == '') {
                $urlreturn = UtilityUrl::getCurrentUrl($params, true);
            }
            base64encodeUrl($urlreturn);
            $urlb = self::createUrl('/home/login', ['urlb' => $urlreturn]);
        }
        if($redirect){
            app()->getResponse()->redirect($urlb);
            app()->end();
        }
        else
            return $urlb;
    }

    /**
     * get module ID, controller ID, action ID
     * @return array array(module,controller,action)
     */
    public static function getControllerInfo() {
        $result['modules'] = '';
        $result['module'] = '';
        $result['controller'] = '';
        $result['action'] = '';
        if(app()->controller) {
            if(app()->controller->module->module && app()->controller->module->module->id && app()->controller->module->module->id != 'app-frontend') {
                $result['modules'] = app()->controller->module->module->id;
            } else {
                unset($result['modules']);
            }
            $result['module'] = app()->controller->module ? app()->controller->module->id : null;
            $result['controller'] = app()->controller->id;
            $result['action'] = app()->controller->action->id;
        }
        return $result;
    }

    /**
     * @author Phong Pham Hong
     * 
     * return link to view opp detail in public page
     * 
     * @param UserCommonModel $model
     * @param array $param
     * @param boolen $absoulute
     */
    public static function genUserProfileUrl($model, $param = array(), $absoulute = false) {
        if ($model) {
            $p = array_merge(array('udo' => $model['domain']), $param);
            $link = '/member/profile/view/detail';
            if ($absoulute) {
                return (UtilityUrl::isMobile() ? HOST_PUBLIC_MOBILE : HOST_PUBLIC) . '/members/' . $model['domain'] . '/detail';
            } else {
                return self::createUrl([$link] + $p);
            }
        }
        return self::createUrl('/');
    }
    
    public static function genmyinterestsurl($model, $param = array(), $absoulute = false){
        if($model){
            $p = array_merge(array('udo' => $model->domain), (array) $param);
            return self::createUrl(['/member/profile/interests/index'] + $p);
        }
        return self::createUrl('/');
    }

        /**
     * @author Phong Pham Hong
     * 
     * return link to view opp detail in public page
     * 
     * @param \common\models\user\UserModel $model
     * @param array $param
     * @param boolen $absoulute
     */
    public static function genUserMobileProfileUrl($model, $param = array(), $absoulute = false) {
        if ($model) {
            $p = array_merge(array('udo' => $model['domain']), $param);
            $link = '/membermobile/profile/view/detail';
            if ($absoulute) {
                return HOST_PUBLIC_MOBILE. '/members/' . $model['domain'] . '/detail';
            }
            return self::createUrl([$link] + $p);
        }
        return self::createUrl('/');
    }

    /**
     * @author Phong Pham Hong
     * 
     * return link to view opp detail in public page
     * 
     * @param \common\models\user\UserModel $model
     * @param array $param
     * @param boolen $absoulute
     */
    public static function genUserPublicUrlMobile($model, $param = array(), $absoulute = false) {
        if ($model) {
            $link = '/membermobile/profile/view/index';
            $p = array_merge(array('udo' => $model['domain']), $param);
            return $absoulute ? self::createAbsoluteUrl([$link] + $p) : self::createUrl([$link] + $p);
        }
        return self::createUrl('/');
    }

    /**
     * @author Phong Pham Hong
     * 
     * return link to view opp detail in public page
     * 
     * @param UserCommonModel $model
     * @param array $param
     * @param boolen $absoulute
     */
    public static function genMyProfileUrl($param = array(), $absoulute = false) {
        $model = \common\models\user\UserModel::getUserLogin();
        $p = array_merge(array('udo' => $model['domain']), (array) $param);
        return self::createUrl(['/member/profile/detail/index'] + $p);
    }

    /*
     * get "my send verify email" link of user
     */
    public static function gensendverifyemailurl($model= null, $param = array(), $absoulute = false){
        if(!$model){
            $model = \common\models\user\UserModel::getUserLogin();
            $p = array_merge(array('udo' => $model->domain), (array) $param);
            return self::createUrl(['/member/profile/sendverifyemail/index'] + $p);
        }
        return self::createUrl('/');
    }
    
    /*
     * get "my profile" link of user
     */
    public static function genmyprofileofuserurl($model, $param = array(), $absoulute = false){
        if($model){
            $p = array_merge(array('udo' => $model->domain), (array) $param);
            return self::createUrl(['/member/profile/detail/index'] + $p);
        }
        return self::createUrl('/');
    }

    /**
     * @author Phong Pham Hong
     * 
     * return link to view org detail in public page
     * 
     * @param OrgProfile $model
     * @param array $param
     * @param boolen $absoulute
     */
    public static function genPartnerProfileUrl($model, $param = array(), $absoulute = false) {
        if ($model) {
            $p = array_merge(array('do' => $model['domain']), $param);
            $link = '/member/orgprofile/detail/view';
            if ($absoulute) {
                return (UtilityUrl::isMobile() ? HOST_PUBLIC_MOBILE : HOST_PUBLIC) . '/partners/' . $model['domain'];
            } else {
                return self::createUrl([$link] + $p);
            }
        }
        return self::createUrl('/');
    }

    public static function genPartnerMobileProfileUrl($model, $param = array(), $absoulute = false) {
        if ($model) {
            $p = array_merge(array('do' => $model['domain']), $param);
            $link = '/membermobile/orgprofile/detail/view';
            if ($absoulute) {
                return HOST_PUBLIC_MOBILE . '/partners/' . $model['domain'];
            } else {
                return self::createUrl([$link] + $p);
            }
        }
        return self::createUrl('/');
    }

    /**
     * @author Phong Pham Hong
     * 
     * return link to view opp detail in public page
     * 
     * @param OppDetails $model
     * @param array $param
     * @param boolen $absoulute
     */
    public static function genOppDetailUrl($model, $param = array(), $absoulute = false) {
        if ($model) {
            $p = array('do' => $model['domain']);
            $p['org'] = $model->org->domain;
            $p = array_merge($p, $param);
            $link = '/member/opportunity/detail/index';
            return $absoulute ? self::createAbsoluteUrl([$link] + $p) : self::createUrl([$link] + $p);
        }
        return self::createUrl('/');
    }

    public static function genOppDetailMobileUrl($model, $param = array(), $absoulute = false) {
        if ($model) {
            $p = array('do' => $model['domain']);
            $p['org'] = $model->org->domain;
            $p = array_merge($p, $param);
            $link = '/membermobile/opportunity/detail/index';
            return $absoulute ? self::createAbsoluteUrl([$link] + $p) : self::createUrl([$link] + $p);
        }
        return self::createUrl('/');
    }

    /**
     * @author Tu Nguyen Anh
     * 
     * return link to view app detail in public page
     * 
     * @param UsrApplication $model
     * @param type $param
     * @param type $absoulute
     * @return type
     */
    public static function genAppDetailUrl($model, $param = array(), $absoulute = false) {
        if ($model) {
            $opp_name = isset($model['opp_name']) ? $model['opp_name'] : $model->opp->opp_name;
            $p = array('id' => $model['app_id'], 'opp_name' => UtilityHtmlFormat::parseToAlias($opp_name));
            $p = array_merge($param, $p);
            $link = '/member/opportunity/own/index';
            return $absoulute ? self::createAbsoluteUrl([$link] + $p) : self::createUrl([$link] + $p);
        }
        return self::createUrl('/');
    }

    public static function genAppDetailMobileUrl($model, $param = array(), $absoulute = false) {
        if ($model) {
            $opp_name = isset($model['opp_name']) ? $model['opp_name'] : $model->opp->opp_name;
            $p = array('id' => $model['app_id'], 'opp_name' => UtilityHtmlFormat::parseToAlias($opp_name));
            $p = array_merge($p, $param);
            $link = '/membermobile/opportunity/essential/detail';
            return $absoulute ? self::createAbsoluteUrl([$link] + $p) : self::createUrl([$link] + $p);
        }
        return self::createUrl('/');
    }

    /**
     * return base domain
     * @return type
     */
    public static function getBaseDomain() {
        $ssl = isset($_SERVER["HTTP_USESSSL"]) ? trim(strtolower($_SERVER["HTTP_USESSSL"])) : null;
        if ($ssl) {
            return 'https://' . $_SERVER['SERVER_NAME'];
        }
        //$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
        //return 'https://' . $_SERVER['SERVER_NAME'];
    }

    public static function checkBrowserSafari() {
        $user_agent = app()->getRequest()->userAgent;
        if (strpos($user_agent, 'Safari') !== false && strpos($user_agent, 'Windows') !== false && strpos($user_agent, 'Safari') === false) {
            return true;
        }
        return false;
    }

    /**
     * @author Phong Pham Hong
     * 
     * return link that redirect to advertiser
     */
    public static function genAdvertiserSiteUrl($url, $param = array()) {
        return HOST_ADVERTISER . $url . ($param ? '?' . http_build_query($param) : '');
    }

    /**
     * @author Phong Pham Hong
     * 
     * return link that redirect to advertiser
     */
    public static function genAdvertiserSiteMobileUrl($url, $param = array()) {
        return HOST_ADVERTISER_MOBILE . $url . ($param ? '?' . http_build_query($param) : '');
    }

    /**
     * Creates a URL for the given route.
     *
     * This method will use [[\yii\web\UrlManager]] to create a URL.
     *
     * You may specify the route as a string, e.g., `site/index`. You may also use an array
     * if you want to specify additional query parameters for the URL being created. The
     * array format must be:
     *
     * ```php
     * // generates: /index.php?r=site/index&param1=value1&param2=value2
     * ['site/index', 'param1' => 'value1', 'param2' => 'value2']
     * ```
     *
     * If you want to create a URL with an anchor, you can use the array format with a `#` parameter.
     * For example,
     *
     * ```php
     * // generates: /index.php?r=site/index&param1=value1#name
     * ['site/index', 'param1' => 'value1', '#' => 'name']
     * ```
     *
     * A route may be either absolute or relative. An absolute route has a leading slash (e.g. `/site/index`),
     * while a relative route has none (e.g. `site/index` or `index`). A relative route will be converted
     * into an absolute one by the following rules:
     *
     * - If the route is an empty string, the current [[\yii\web\Controller::route|route]] will be used;
     * - If the route contains no slashes at all (e.g. `index`), it is considered to be an action ID
     *   of the current controller and will be prepended with [[\yii\web\Controller::uniqueId]];
     * - If the route has no leading slash (e.g. `site/index`), it is considered to be a route relative
     *   to the current module and will be prepended with the module's [[\yii\base\Module::uniqueId|uniqueId]].
     *
     * Below are some examples of using this method:
     *
     * ```php
     * // /index?r=site/index
     * echo Url::toRoute('site/index');
     *
     * // /index?r=site/index&src=ref1#name
     * echo Url::toRoute(['site/index', 'src' => 'ref1', '#' => 'name']);
     *
     * // http://www.example.com/index.php?r=site/index
     * echo Url::toRoute('site/index', true);
     *
     * // https://www.example.com/index.php?r=site/index
     * echo Url::toRoute('site/index', 'https');
     * ```
     *
     * @param string|array $route use a string to represent a route (e.g. `index`, `site/index`),
     * or an array to represent a route with query parameters (e.g. `['site/index', 'param1' => 'value1']`).
     * @return string the generated URL
     * @throws InvalidParamException a relative route is given while there is no active controller
     */
    public static function createUrl($route, $params = []) {
        if (!is_array($route)) {
            return Url::toRoute((array) $route + $params, false);
        }
        return Url::toRoute($route, false);
    }

    public static function createAbsoluteUrl($route, $params = []) {
        if (!is_array($route)) {
            return Url::toRoute((array) $route + $params, true);
        }
        return Url::toRoute($route, true);
    }

    /**
     * @authro Phong Pham Hong
     * 
     * append GET params to url
     * @param string url
     * @param array param
     * @return string
     */
    public static function appendParamsToUrl($url, $param = array()) {
        $ap = '?';
        if (strpos($url, '?') !== false) {
            $ap = '&';
        }
        return $url . $ap . http_build_query($param);
    }

    /**
     * create Url to view message in public page
     * 
     * @param UserCommonModel $user
     * @return string
     */
    public static function publicUrlViewList($userModel = null, $param = array(), $_prefix = 'member') {
        $userModel = $userModel ? $userModel : new common\models\user\UserModel;
        $p = array('udo' => $userModel->domain);
        return self::createUrl(["/{$_prefix}/sysprivatemail/main"] + $p + $param);
    }

    /**
     * 
     * @param type $userModel
     * @param type $param
     * @param type $_prefix
     * @return type
     */
    public static function renderLinkStr($str) {
        if ($str == '') {
            return $str;
        }
        $parts = parse_url($str);
        $query = [];
        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
        }
        $uri = $parts['path']{0} == "/" ? $parts['path'] :  '/' . $parts['path'];
        return self::createUrl([$uri] + $query);
        
    }
    /*
     * get http  referer
     */
    public static function getHttpReferer() {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    }
    
    public static function realURL() {
        return SCHEME . '://' . DOMAIN . REQUEST_URI;
    }
    
    public static function getDomainReferer() {
        $server = $_SERVER;
        if(isset($server['HTTP_REFERER'])) {
            $http_referer = $server['HTTP_REFERER'];
            $array = explode('/',$http_referer);
            if(isset($array[2])) {
                return $array[2];
            }
        }
        return false;
    }
    
    
    
    public static function calCurlPost($url, $params) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $errno = curl_errno($ch);
        curl_close($ch);
        return $result;
    }
    
    public static $check_mac = null;
    
    public static function checkMac() {
        if(self::$check_mac === null) {
            self::$check_mac = false;
            if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT']) {
                if(strpos($_SERVER['HTTP_USER_AGENT'], "Mac") !== FALSE) {
                    self::$check_mac = true;
                }
            }
        }
        return self::$check_mac;
    }

}
