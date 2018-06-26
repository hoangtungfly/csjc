<?php

/**
 * This file contains constants and shortcut functions that are commonly used.
 * Please only include functions are most widely used because this file
 * is included for every request. Functions are less often used are better
 * encapsulated as static methods in helper classes that are loaded on demand.
 */

/**
 * This is the shortcut to Yii::app()->clientScript->registerCssFile
 */
use yii\web\View;

function regCssFile($files, $addBaseUrl = true, $pos = View::POS_HEAD) {
    if (!is_array($files))
        $files = array($files);
    $path = $addBaseUrl ? Yii::$app->request->baseUrl . '/' : null;
    $path = DIRECTORY_MAIN;
    if (MIN_MEDIA_FILES && !MAIN_ROUTE) {
        foreach ($files as $file) {
            if (!preg_match('/landingpages/', $file))
                $file = str_replace(".css", MIN_MEDIA_FILES . '.css', $file);
            cs()->registerCssFile($path . $file . '?v=' . VERSION, ['depends' => null]);
        }
    } else {
        foreach ($files as $file) {
            cs()->registerCssFile($path . $file . '?v=' . VERSION, ['depends' => null]);
        }
    }
}

/**
 * This is the shotcut to Yii::app()->clientScript->registerCoreScript
 */
function regCoreFile($files) {
    if (!is_array($files))
        $files = array($files);
    foreach ($files as $file)
        cs()->registerCoreScript($file);
}

/**
 * This is the shortcut to Yii::app()->clientScript->registerScriptFile
 */
function regJsFile($files, $addBaseUrl = true, $pos = View::POS_END) {
    if (!is_array($files))
        $files = array($files);
    $path = $addBaseUrl ? Yii::$app->request->baseUrl . DIRECTORY_MAIN_2 . '/' : null;
    if (MIN_MEDIA_FILES) {
        foreach ($files as $file) {
            $file = str_replace(".js", MIN_MEDIA_FILES . '.js', $file);
            cs()->registerJsFile($path . $file . '?v=' . VERSION, ['depends' => null, 'position' => $pos]);
        }
    } else {
        foreach ($files as $file) {
            cs()->registerJsFile($path . $file . '?v=' . VERSION, ['depends' => null, 'position' => $pos]);
        }
    }
}

/**
 * Shortcut to display Icon image
 * @param string $img image file
 * @param string $size
 * @param string $options
 */
function icon($img, $size = '48', $options = array()) {
    return img(bu('/images/icons/' . $size . '/' . $img), '', $size, null, $options);
}

/**
 * Displays a variable.
 * This method achieves the similar functionality as var_dump and print_r
 * but is more robust when handling complex objects such as Yii controllers.
 * @param mixed variable to be dumped
 * @param integer maximum depth that the dumper should go into the variable. Defaults to 10.
 * @param boolean whether the result should be syntax-highlighted
 */
function dump($target, $depth = 10, $highlight = true) {
    echo CVarDumper::dumpAsString($target, $depth, $highlight);
}

/**
 * This is the shortcut to CHtml::encode
 */
function h($text, $limit = 0) {
    if ($limit && strlen($text) > $limit && ($pos = strrpos(substr($text, 0, $limit), ' ')) !== false)
        $text = substr($text, 0, $pos) . ' ...';
    return htmlspecialchars($text, ENT_QUOTES, Yii::app()->charset);
}

/**
 * This is the shortcut to nl2br(CHtml::encode())
 * @param string the text to be formatted
 * @param integer the maximum length of the text to be returned. If 0, it means no truncation.
 * @param string the label of the "read more" button if $limit is greater than 0.
 * Set this to be false if the "read more" button should not be displayed.
 * @return string the formatted text
 */
function nh($text, $limit = 0, $readMore = 'read more') {
    if ($limit && strlen($text) > $limit) {
        if (($pos = strpos($text, ' ', $limit)) !== false)
            $limit = $pos;
        $ltext = substr($text, 0, $limit);
        if ($readMore !== false) {
            $rtext = substr($text, $limit);
            return nl2br(htmlspecialchars($ltext, ENT_QUOTES, Yii::app()->charset))
                    . ' ' . l(h($readMore), '#', array('class' => 'read-more', 'onclick' => '$(this).hide().next().show(); return false;'))
                    . '<span style="display:none;">'
                    . nl2br(htmlspecialchars($rtext, ENT_QUOTES, Yii::app()->charset))
                    . '</span>';
        } else
            return nl2br(htmlspecialchars($ltext . ' ...', ENT_QUOTES, Yii::app()->charset));
    } else
        return nl2br(htmlspecialchars($text, ENT_QUOTES, Yii::app()->charset));
}

/**
 * This is the shortcut to CHtmlPurifier::purify().
 */
function ph($text) {
    static $purifier;
    if ($purifier === null)
        $purifier = new CHtmlPurifier;
    return $purifier->purify($text);
}

/**
 * Converts a markdown text into purified HTML
 */
function mh($text) {
    static $parser;
    if ($parser === null)
        $parser = new MarkdownParser;
    return $parser->safeTransform($text);
}

/**
 * This is the shortcut to CHtml::link()
 */
function l($text, $url = '#', $htmlOptions = array()) {
    return CHtml::link($text, $url, $htmlOptions);
}

/**
 * Generates an image tag.
 * @param string $url the image URL
 * @param string $alt the alt text for the image. Images should have the alt attribute, so at least an empty one is rendered.
 * @param integer the width of the image. If null, the width attribute will not be rendered.
 * @param integer the height of the image. If null, the height attribute will not be rendered.
 * @param array additional HTML attributes (see {@link tag}).
 * @return string the generated image tag
 */
function img($url, $alt = '', $width = null, $height = null, $htmlOptions = array()) {
    $htmlOptions['src'] = $url;
    if ($alt !== null)
        $htmlOptions['alt'] = $alt;
    else
        $htmlOptions['alt'] = '';
    if ($width !== null)
        $htmlOptions['width'] = $width;
    if ($height !== null)
        $htmlOptions['height'] = $height;
    return yii\helpers\Html::img($url, $htmlOptions);
}

/**
 * This is the shortcut to Yii::t().
 * Note that the category parameter is removed from the function.
 * It defaults to 'application'. If a different category needs to be specified,
 * it should be put as a prefix in the message, separated by '|'.
 * For example, t('backend|this is a test').
 */
function t($message, $params = array(), $source = null, $language = null) {
    if (($pos = strpos($message, '|')) !== false) {
        $category = substr($message, 0, $pos);
        $message = substr($message, $pos + 1);
    } else
        $category = 'application';
    return Yii::t($category, $message, $params, $source, $language);
}

/**
 * This is the shortcut to Yii::app()->createUrl()
 */
function url($route, $params = array(), $ampersand = '&') {
    return Yii::$app->getUrlManager()->createUrl($route, $params, $ampersand);
}

/**
 * This is the shortcut to Yii::app()->request->baseUrl
 * If the parameter is given, it will be returned and prefixed with the app baseUrl.
 */
function bu($url = '') {
    static $baseUrl;
    if ($baseUrl === null)
        $baseUrl = Yii::$app->request->baseUrl;
    return $baseUrl . '/' . ltrim($url, '/');
}

/**
 * Returns the named application parameter.
 * This is the shortcut to Yii::app()->params[$name].
 */
function param($name) {
    return Yii::$app->params[$name];
}

/**
 * This is the shortcut to Yii::app()->user.
 * @return WebUser
 */
function user() {
    return Yii::$app->user;
}

/**
 * This is the shortcut to Yii::app()
 * @return \yii\console\Application|\yii\web\Application the application instance
 */
function app() {
    return Yii::$app;
}

/**
 * This is the shortcut to Yii::app()->session
 * @return \yii\web\Session
 */
function session() {
    return Yii::$app->session;
}

/**
 * This is the shortcut to Yii::app()->clientScript
 * @return CClientScript
 */
function cs() {
    return Yii::$app->view;
}

/**
 * This is the shortcut to Yii::app()->db
 * @return CDbConnection
 */
function db() {
    return Yii::$app->db;
}

/**
 * This is the shortcut to Yii::app()->getRequest
 * @return CHttpRequest object
 */
function r() {
    return Yii::$app->getRequest();
}

/**
 * This is the shortcut to Yii::app()->user->checkAccess().
 */
function allow($operation, $params = array(), $allowCaching = true) {
    return Yii::$app->user->checkAccess($operation, $params, $allowCaching);
}

/**
 * Ensures the current user is allowed to perform the specified operation.
 * An exception will be thrown if not.
 * This is similar to {@link access} except that it does not return value.
 */
function ensureAllow($operation, $params = array(), $allowCaching = true) {
    if (!Yii::app()->user->checkAccess($operation, $params, $allowCaching))
        throw new CHttpException(403, Yii::t('error', 'You are not allowed to perform this operation.'));
    return true;
}

/**
 * Shortcut to Yii::app()->format (utilities for formatting structured text)
 */
function format() {
    return Yii::$app->format;
}

/**
 * Shortcut for json_encode
 * NOTE: json_encode exists in PHP > 5.2, so it's safe to use it directly without checking
 * @param array $json the PHP array to be encoded into json array
 * @param int $opts Bitmask consisting of JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_FORCE_OBJECT.
 */
function je($json, $opts = null) {
    //return function_exists('json_encode')? json_encode($json) : CJSON::encode($json);
    return json_encode($json, $opts);
}

/**
 * Shortcut for json_decode
 * NOTE: json_encode exists in PHP > 5.2, so it's safe to use it directly without checking
 * @param string $json the PHP array to be decoded into json array
 * @param bool $assoc when true, returned objects will be converted into associative arrays.
 * @param int $depth User specified recursion depth.
 * @param int $opts Bitmask of JSON decode options. 
 * 	Currently only JSON_BIGINT_AS_STRING is supported 
 * 	(default is to cast large integers as floats)
 */
function jd($json, $assoc = null, $depth = 512, $opts = 0) {
    return json_decode($json, $assoc, $depth);
}

/**
 * Adds trailing dots to a string if exceeds the length specified
 * @param string $txt the text to cut
 * @param integer $length the length
 * @param string $encoding the encoding type if multibyte, null otherwise
 * @return string 
 */
function trail($txt, $length, $encoding = 'utf-8') {
    if (strlen($txt) > $length) {
        if (null != $encoding) {
            $txt = mb_substr($txt, 0, $length - 3, $encoding);
            $pos = mb_strrpos($txt, ' ', null, $encoding);
            $txt = mb_substr($txt, 0, $pos, $encoding) . '...';
        } else {
            $txt = substr($txt, 0, $length - 3);
            $pos = strrpos($txt, ' ');
            $txt = substr($txt, 0, $pos) . '...';
        }
    }
    return $txt;
}

/**
 * Email obfuscator script 2.1 by Tim Williams, University of Arizona.
 * Random encryption key feature by Andrew Moulden, Site Engineering Ltd
 * PHP version coded by Ross Killen, Celtic Productions Ltd
 * This code is freeware provided these six comment lines remain intact
 * A wizard to generate this code is at http://www.jottings.com/obfuscator/
 * The PHP code may be obtained from http://www.celticproductions.net/\n\n";
 * 
 * @param string $address the email address to obfuscate
 * @return string 
 */
function obfuscateEmail($address) {
    $address = strtolower($address);
    $coded = "";
    $unmixedkey = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.@";
    $inprogresskey = $unmixedkey;
    $mixedkey = "";
    $unshuffled = strlen($unmixedkey);
    for ($i = 0; $i <= strlen($unmixedkey); $i++) {
        $ranpos = rand(0, $unshuffled - 1);
        $nextchar = substr($inprogresskey, $ranpos, 1);
        $mixedkey .= $nextchar;
        $before = substr($inprogresskey, 0, $ranpos);
        $after = substr($inprogresskey, $ranpos + 1, $unshuffled - ($ranpos + 1));
        $inprogresskey = $before . '' . $after;
        $unshuffled -= 1;
    }
    $cipher = $mixedkey;

    $shift = strlen($address);

    $txt = "<script type=\"text/javascript\" language=\"javascript\">\n" .
            "<!-" . "-\n";

    for ($j = 0; $j < strlen($address); $j++) {
        if (strpos($cipher, $address{$j}) == -1) {
            $chr = $address{$j};
            $coded .= $chr;
        } else {
            $chr = (strpos($cipher, $address{$j}) + $shift) % strlen($cipher);
            $coded .= $cipher{$chr};
        }
    }


    $txt .= "\ncoded = \"" . $coded . "\"\n" .
            "  key = \"" . $cipher . "\"\n" .
            "  shift=coded.length\n" .
            "  link=\"\"\n" .
            "  for (i=0; i<coded.length; i++) {\n" .
            "    if (key.indexOf(coded.charAt(i))==-1) {\n" .
            "      ltr = coded.charAt(i)\n" .
            "      link += (ltr)\n" .
            "    }\n" .
            "    else {     \n" .
            "      ltr = (key.indexOf(coded.charAt(i))-
shift+key.length) % key.length\n" .
            "      link += (key.charAt(ltr))\n" .
            "    }\n" .
            "  }\n" .
            "document.write(\"<a href='mailto:\"+link+\"'>\"+link+\"</a>\")\n" .
            "\n" .
            "//-" . "->\n" .
            "<" . "/script><noscript>N/A" .
            "<" . "/noscript>";
    return $txt;
}

/**
 * @author phong pham hong<phongbro1805@gmail.com>
 * get only numeric in an array 
 * @param array
 * @return array
 */
function getNumericInArray(&$array) {
    $newt = array();
    foreach ($array as $item) {
        if (is_numeric($item)) {
            $newt[] = intval($item);
        }
    }
    $array = $newt;
    return;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * return link url of file or image from other serve
 * @path: DIR file
 * @file: file name
 * @return string
 */
function rFile($path, $file) {
    $path = trim($path);
    $file = trim($file);
    if ($file == '')
        return '';
    $path = rtrim($path, '/');
    $path = ltrim($path, '/');
    $string = $path . DS . $file;
    return HOST_MEDIA . preg_replace('/\\/+/', '/', $string);
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * get only element that has value in array
 * @param array
 * @return array
 */
function getElementHasValueArray($array) {
    $result = array();
    foreach ($array as $key => $item) {
        if (is_array($item) && count($item) > 0) {
            $result[$key] = $item;
        } else if (trim($item) != '') {
            $result[$key] = $item;
        }
    }
    unset($array);
    return $result;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * get only element that has value or key is null or empty
 * @param array
 * @return array
 */
function rmvEmptyKeyValue(&$array) {
    $result = array();
    foreach ($array as $key => $item) {
        if (is_array($item) && count($item) > 0) {
            $result[$key] = $item;
        } else if (trim($item) != '' && trim($key) != '') {
            $result[$key] = yii\helpers\Html::encode(yii\helpers\Html::decode($item));
        }
    }
    $array = $result;
    return $result;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * get only element that has value or key is null or empty
 * @param array
 * @return array
 */
function rmvEmptyKey(&$array) {
    $result = array();
    foreach ($array as $key => $item) {
        if (trim($key) != '') {
            $result[$key] = $item;
        }
    }
    $array = $result;
    return $result;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * check array has value is null or not
 * if has->return false
 * else true
 * @param array
 * @return boolen
 */
function checkArrayHasNullValue($array) {
    if (is_array($array)) {
        foreach ($array as $item) {
            if (!$item || (is_string($item) && trim($item) == '')) {
                return false;
            }
        }
        return true;
    }
    return false;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * 
 * compare two array: an element in this array is belong to another array
 * @param array $arrone
 * @param array @arrtwo
 * @return boolen
 */
function compareTwoArray($arrOne, $arrTwo) {
    $a = array_intersect($arrOne, $arrTwo);
    if (count($a) > 0)
        return true;
    else
        return false;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * 
 * compare two array follow sorting
 * EX: array1 = (1,2,3,4);
 *     array2 = (2,3,1,4);
 *     result: array1 # array2
 * Dont care about number of element
 * Convert to string to compare
 * @param array $arrOne
 * @param array $arrTwo
 * @return boolen
 */
function compareTwoArrayFollowSort($arrOne, $arrTwo) {
    if (!is_array($arrOne) || !is_array($arrTwo)) {
        return false;
    }
    $strArrOne = '_' . trim(implode('_', $arrOne)) . '_';
    $strarrTwo = '_' . trim(implode('_', $arrTwo)) . '_';

    if (strpos($strArrOne, $strarrTwo) !== false || strpos($strarrTwo, $strArrOne) !== false) {
        return true;
    }
    return false;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * check a variable is array or not
 * if it is not array, set to array empty
 * @param type 
 * @return array
 */
function forceToArray(&$value) {
    if (!isset($value) || !is_array($value)) {
        $value = array();
    }
    return $value;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * set isset for an variable
 * @param type $value
 * @param type $default value that will be set for variable
 * @return type $value
 */
function setIsset(&$value, $default = null) {
    $value = isset($value) ? $value : $default;
    return $value;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * 
 * base64_encode for url 
 * @param string $url
 */
function base64encodeUrl(&$url) {
    $url = base64_encode($url);
    return $url;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * 
 * base64_decode for url 
 * @param string $url
 */
function base64decodeUrl(&$url) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    $url = base64_decode(str_replace($entities, $replacements, $url));
    return $url;
}

/**
 * add param into url 
 * @param type $url
 */
function addParamsUrl(&$url, $params = []) {
    $url = $url . (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . http_build_query($params);
    return $url;
}

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * 
 * rewrite trim function with reference
 * @param string $string
 * @return $string
 */
function reTrim(&$string) {
    $string = trim($string);
    return $string;
}

/**
 * @author: Phong Pham Hong<phongbro1805@gmail.com>
 * 
 * array alice follow key, not length
 * @parma array	$arr. Specifies an array
  @param start	Optional. Numeric value. Specifies where the function will start the slice. 0 = the first element. If this value is set to a negative number, the function will start slicing that far from the last element. -2 means start at the second last element of the array.
  @param key	Required. Specifies the key of the element
  @param preserve	Optional. Specifies if the function should preserve or reset the keys. Possible values:
  true -  Preserve keys
  false - Default. Reset keys
 */
function array_slice_until_key(&$arr, $key, $start = 0, $preserve = true) {
    $length = array_search($key, array_keys($arr));
    $arr = array_slice($arr, $start, $length, $preserve);
    return $arr;
}

/**
 * @author: Phong Pham Hong<phongbro1805@gmail.com>
 * 
 * remove elements from start to key of this array
 * @param array $arr 
 * @param $key Required: Key of the element, elements that have position before this key will be removed
 * @param boolen $removeKey: remove this key or not
 */
function array_rmv_until_key(&$arr, $key, $removeKey = false) {
    foreach ($arr as $k => $v) {
        if ($k == $key) {
            break;
        } else {
            unset($arr[$k]);
        }
    }
    if ($removeKey === true && isset($arr[$key])) {
        unset($arr[$key]);
    }
    return $arr;
}

/**
 * @author Phong Pham Hong
 * 
 * get an unique key from value to make key 
 * 
 * @param array $data
 * @return array
 */
function convert_key_for_array(&$data, $field) {
    $result = array();
    foreach ($data as $item) {
        if (isset($item[$field])) {
            $result[$item[$field]] = $item;
        }
    }
    $data = $result;
    return $result;
}

/**
 * @author Phong Pham Hong
 * 
 * get an key and value from array
 * @return type
 */
function get_value_array($key, $array, $default = null) {
    if (is_array($array)) {
        return isset($array[$key]) ? $array[$key] : $default;
    }
    return $default;
}

/**
 * @author Phong Pham Hong
 * get cache object
 * 
 * @return \common\core\dbConnection\GlobalRedis
 */
function cache_object() {
    return Yii::$app->redis;
}

/**
 * @author Phong Pham Hong
 * get cookie value
 * 
 * @param type $key
 * @return mixed value
 */
function getcookie($key) {
    if (isset($_COOKIE[$key])) {
        return $_COOKIE[$key];
    }
    return null;
}

/**
 * casting a variable to boolen type variable
 * 
 * @param value
 * @return boolen
 */
function boolenval($value) {
    return $value ? true : false;
}

/**
 * Function to Get All Apps Available
 * */
function getAllApps() {
//    $cache_id = 'gxchelpers-apps';
//    $apps = Yii::app()->cache->get($cache_id);
//    if ($apps === false) {
//        $apps = array();
//        $folders_app = get_subfolders_name(Yii::getPathOfAlias('common') . DIRECTORY_SEPARATOR . '..');
//        foreach ($folders_app as $folder) {
//            if (file_exists(Yii::getPathOfAlias('common') . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'main.php') && $folder != 'common')
//                $apps[] = $folder;
//        }
//        Yii::app()->cache->set($cache_id, $apps, 7200);
//    }
//
//    return $apps;

    return array('advertiser', 'backoffice');
}

/**
 * @author Phong Pham Hong
 * 
 * render jqeury mobile link without ajax option
 * @param string $text
 * @param string @url
 * @param array $htmlOption
 */
function mlink($text, $url = '#', $htmlOptions = array()) {
//    if (!isset($htmlOptions['rel'])) {
//        //$htmlOptions['rel'] = 'external';
//    }
    return yii\helpers\Html::a($text, $url, $htmlOptions);
}

/**
 * @author Phong Pham Hong
 * 
 * render jqeury mobile link with ajax option
 * @param string $text
 * @param string @url
 * @param array $htmlOption
 */
function majaxlink($text, $url = '#', $htmlOptions = array()) {
    return yii\helpers\Html::a($text, $url, $htmlOptions);
}

function encode($content, $doubleEncode = true) {
    return yii\helpers\Html::encode($content, $doubleEncode);
}

function decode($content) {
    return htmlspecialchars_decode($content, ENT_QUOTES);
}

/**
 * @author Phong Pham Hong
 * 
 * make value to key of an array
 * @param array $array
 * @return array
 */
function makeValueToKey(array &$array) {
    $result = array();
    foreach ($array as $item) {
        $result[$item] = $item;
    }
    $array = $result;
    return $result;
}

function flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) {
        $return[] = $a;
    });
    return $return;
}

/*
 * @author Dung Nguyen Anh
 * check attributes new and old
 * If attributes new # old return new
 * @param array $attributesOld
 * @param array $attributesNew
 * @return array
 */

function checkAttributesNewAndOld($attributesOld, $attributesNew) {
    $result = $attributesNew;

    if (is_array($attributesOld) && is_array($attributesNew) && count($attributesOld) > 0 && count($attributesNew)) {
        $result = array();
        if (isset($attributesOld['modified_time']))
            unset($attributesOld['modified_time']);
        if (isset($attributesNew['modified_time']))
            unset($attributesNew['modified_time']);
        foreach ($attributesNew as $key => $value) {
            if (array_key_exists($key, $attributesOld)) {
                if ($attributesOld[$key] != $value) {
                    $result[$key] = $value;
                }
            } else {
                $result[$key] = $value;
            }
        }
    }
    if (isset($result['created_by']))
        unset($result['created_by']);
    if (isset($result['created_time']))
        unset($result['created_time']);
    if (isset($result['modified_time']))
        unset($result['modified_time']);
    if (isset($result['modified_by']))
        unset($result['modified_by']);
    return $result;
}

function ordinal($number) {
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if ((($number % 100) >= 11) && (($number % 100) <= 13))
        return $number . 'th';
    else
        return $number . $ends[$number % 10];
}

function json_encodeurl($array) {
    if (count($array)) {
        foreach ($array as $k => $v) {
            if (!is_array($v))
                $array[$k] = urlencode($v);
        }
    }
    return json_encode($array);
}

function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 1);
    return curl_exec($ch);
    curl_close($ch);
}

function url_get_contents($Url) {
    if (!function_exists('curl_init')) {
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function filegetcontents($link, $use_include_path = false, $context = null, $offset = -1, $maxlen = null) {
    $link = str_replace(' ', '%20', $link);
    $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
    $content = @file_get_contents($link,false, stream_context_create($arrContextOptions));
    return $content;
//    return @file_get_contents('http://dev457visa.com/filecontent.php?link=' . $link);
}

function phpself($server) {
    $php_self_array = explode('/', $server['PHP_SELF']);
    if (count($php_self_array) > 2) {
        unset($php_self_array[count($php_self_array) - 1]);
        return implode('/', $php_self_array);
    } else {
        return '';
    }
}

function phpdomain($server) {
    return $server['HTTP_HOST'];
    $arrayHost = explode('.', $server['HTTP_HOST']);
    if (count($arrayHost) > 2) {
        unset($arrayHost[0]);
    }
    return implode('.', $arrayHost);
}

function phpwebtype() {
    if (WEB_TYPE != "") {
        return WEB_TYPE == 'admin' ? '/backend/web' : '/frontend/web';
    } else {
        return '';
    }
}

function phpwebname($server) {
    $REQUEST_URI = $server['REQUEST_URI'];
    $array = explode('/', $REQUEST_URI);
    if (isset($array[1]) || (isset($array[2]) && $array[1] == 'application')) {
        $listDir = scandir(APPLICATION_PATH . '/application');
        unset($listDir[0], $listDir[1]);
        $listDir = array_flip($listDir);
        if (isset($listDir[$array[1]])) {
            return $array[1];
        }
        if (isset($array[2]) && isset($listDir[$array[2]])) {
            return $array[2];
        }
    }
    return false;
}

function updateUpperFirstCharacter($str) {
    $str{0} = strtoupper($str{0});
    return $str;
}

function className($className) {
    if (!is_string($className)) {
        $className = get_class($className);
    }
    $array = explode('\\', $className);
    return $array[count($array) - 1];
}

function rmkdir($path, $mode = 0775) {
    return is_dir($path) || ( rmkdir(dirname($path), $mode) && _mkdir($path, $mode) );
}

function _mkdir($path, $mode = 0775, $owner = 'apache') {
    $old = umask(0);
    $res = @mkdir($path, $mode);
    @chown($path, $owner);
    umask($old);
    return $res;
}

function substring_index($subject, $delim, $count) {
    if ($count < 0) {
        return implode($delim, array_slice(explode($delim, $subject), $count));
    } else {
        return implode($delim, array_slice(explode($delim, $subject), 0, $count));
    }
}

function getFileLinkNew($link) {
    $extension = pathinfo($link, PATHINFO_EXTENSION);
    $name = pathinfo($link, PATHINFO_FILENAME);
    $basename = pathinfo($link, PATHINFO_BASENAME);
    $count = 1;
    $link_new = $link;
    while (is_file($link_new)) {
        $nameNew = $name . '(' . $count . ')';
        $link_new = str_replace($basename, $nameNew . '.' . $extension, $link);
        $count++;
    }
    return $link_new;
}

function D_vardump($item) {
    echo '<pre>';
    print_r($item);
    echo '</pre>';
}

function is_main() {
    return $_SERVER['REQUEST_URI'] == '/';
}


function getLinkContentDir($link) {
    $content = file_get_contents($link);
    $content = preg_replace('/[^~]+?(<table)/','<table',$content);
    $content = preg_replace('/<\/table>[^~]+/','</table>',$content);
    preg_match_all('/href="([^"]+)"/', $content,$matches);
    $rs = [];
    if(isset($matches[1]) && count($matches[1])) {
        foreach($matches[1] as $key => $value) {
            if(!in_array($value{0}, ['?','/'])) {
                $rs[] = $value;
            }
        }
    }
    return $rs;
}

function tel($so) {
    if(!\common\utilities\UtilityUrl::isMobile()) {
        if($so && $so{0} == '0') {
            $so{0} = '+';
        }
        if($so && $so{0} != '+') {
            $so = '+' . $so;
        }
    }
    return $so;
}