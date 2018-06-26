<?php

/**
 * Some function for process url
 *
 * @author phongphamhong
 * @date 11/22/2013
 */

namespace common\utilities;

use Yii;
use yii\helpers\ArrayHelper;

class UtilityArray {

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

    public static function ArrayPC($arrayMenu, $fixe = '') {
        $arrayParent = array();
        if (count($arrayMenu) > 0) {
            foreach ($arrayMenu as $key => $item) {
                if (is_array($item)) {
                    $pid = $item['pid'];
                    $id = $item['id'];
                } else {
                    $pid = $item->pid;
                    $id = $item->id;
                }
                if ($fixe) {
                    $pid = $fixe . $pid;
                    $id = $fixe . $id;
                }
                $arrayParent[$pid][$id] = $item;
            }
        }
        return $arrayParent;
    }

    public static function arrayLevel(&$result, &$arrayMenu, $type = false, $key = 0, $conmma = '-', $level = 1) {
        $conmma1 = $conmma;
        if ($level > 1) {
            if ($level > 2) {
                $conmma1 .= $conmma . ' ';
            } else {
                $conmma1 .= ' ';
            }
        } else {
            $conmma1 = '';
        }
        if (isset($arrayMenu[$key])) {
            foreach ($arrayMenu[$key] as $item) {
                $id = $type ? $item['id'] : $item->id;
                $name = $type ? $item['name'] : $item->name;
                if ($type) {
                    $arrayMenu[$key][$id]['name'] = $conmma1 . $name;
                } else {
                    $item->name = $conmma1 . $name;
                }
                $result[$id] = $conmma1 . $name;
                if (isset($arrayMenu[$id])) {
                    self::arrayLevel($result, $arrayMenu, $type, $id, $conmma, $level + 1);
                }
            }
        }
    }

    /* convert string to array 
     * theo mảng dấu
     */

    public static function getArraySource($str, $commo = array('||', '|')) {
        $array = array();
        $array1 = explode($commo[0], $str);
        foreach ($array1 as $key => $value) {
            $v = explode($commo[1], $value);
            if (isset($v[1]))
                $array[$v[0]] = $v[1];
        }
        return $array;
    }

    public static function getNameInArrayTableNotAlias($tableName, $arrayDelete = array('id', 'modified_by')) {
        $list = app()->db->createCommand('DESCRIBE `' . $tableName.'`')->queryAll();
        $listData = ArrayHelper::map($list, 'Field', 'Field');
        return self::ua($arrayDelete, $listData);
    }

    /**
     * delete array item in array
     * @param type $delete
     * @param type $array
     * @return $array
     */
    public static function ua($delete, $array) {
        if (is_array($delete) && count($delete) > 0) {
            foreach ($delete as $key => $value) {
                unset($array[$value]);
            }
        }
        return $array;
    }

    /**
     * list Class to array id,name
     * @param array $Class
     * @param string $k
     * @param string $v
     * @return array
     */
    public static function ClassToArray($Class, $k = 'id', $v = 'name') {
        $array = array();
        if (count($Class) > 0) {
            foreach ($Class as $key => $item) {
                $array[$item->$k] = $item->$v;
            }
        }
        return $array;
    }

    /**
     * getNameInArrayTable
     * @param string $tableName
     * @param array $arrayDelete
     * @param alias $alias
     * @return type
     */
    public static function getNameInArrayTable($tableName, $arrayDelete = array('id', 'modified_by')) {
        $arrayList = app()->db->createCommand('DESCRIBE `' . $tableName.'`')->queryAll();
        foreach ($arrayList as $key => $item) {
            $arrayList[$key]['Field'] = "`$tableName`." . $item['Field'];
        }
        return self::ua($arrayDelete, ArrayHelper::map($arrayList, 'Field', 'Field'));
    }

    public static function getTable() {
        $dsn = app()->components['db']['dsn'];
        $array = explode('=', $dsn);
        $db_name = $array[count($array) - 1];
        $db_name = $array[count($array) - 1];
        $idStr = 'Tables_in_' . $db_name;
        return ArrayHelper::map(app()->db->createCommand('SHOW TABLES')->queryAll(), $idStr, $idStr);
    }

    /* replace mảng các key định dạng {key} với value và replace đường dẫn 
     * 2 biến, biến 1 là mảng biến 2 là xâu
     * mảng key replace thành mảng value
     */

    public static function replaceArray($array, $str) {
        $array1 = array();
        $array2 = array();
        $array1[] = '{url}';
        $array2[] = HOST_PUBLIC;
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array1[] = '{{' . $key . '}}';
                $array2[] = $value;
            }
        }
        return str_replace($array1, $array2, $str);
    }

    /* convert string to array 
     * theo dấu , là 1 và dấu || là 2
     */

    public static function convertStringToArrayByConmmaAndOr($str) {
        $array = explode('|', $str);
        if (count($array) > 0)
            foreach ($array as $key => $item)
                $array[$key] = explode(',', $item);
        return $array;
    }

    public static function callFunction($str) {
        $function = $str;
        $a = create_function('', $function);
        return $a();
    }

    public static function searchArray($array, $value) {
        foreach ($array as $key => $item) {
            if ($value == $item)
                return true;
        }
        return false;
    }

    public static function changeStatusLabel(&$arrayLabel) {
        foreach ($arrayLabel as $key => $value) {
            $arrayLabel[$key] = '<div class="lbl_check_radio"> ' . $value . ' </div>';
        }
    }

    /* convert string to array 
     * theo mảng dấu
     */

    public static function getArraySourceString($str, $commo = array('||', '|')) {
        $array = array();
        $array1 = explode($commo[0], $str);
        foreach ($array1 as $key => $value) {
            $v = explode($commo[1], $value);
            if (isset($v[1]))
                $array[$v[0]] = $v[1];
        }
        return self::printArray($array);
    }

    public static function printArray($array) {
        $string = '';
        if (count($array)) {
            $string .= "[\n";
            foreach ($array as $key => $value) {
                $string .= "\t" . (is_int($key) ? $key : "'{$key}'") . " => ";
                if (is_array($value)) {
                    self::printArray($value);
                } else if (is_int($value) || preg_match('/\$data|\$model/', $value)) {
                    $string .= $value;
                } else {
                    $string .= "'" . str_replace("'", "\\'", $value) . "'";
                }
                $string .= ",\n";
            }
            $string .= "]";
        }
        return $string;
    }

    public static function jsonEncodeValidateAngular($model) {
        $model->validate();
        $array = $model->getErrors();
        $result = [];
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $result[] = [
                    'field' => $key,
                    'message' => implode('<br>', $value),
                ];
            }
        }
        return $result;
    }

    public static function unsetNull($array) {
        $result = [];
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if ($value !== null) {
                    $result[$key] = $value;
                }
            }
        } else {
            $result = [$array];
        }
        return $result;
    }

    public static function getAllFileByDirectory($dir = '/models', $name_fixed = 'common', $result = array()) {
        $list = UtilityDirectory::scandir(Yii::getAlias('@common') . $dir);
        foreach ($list as $value) {
            if (strpos($value, '.') === false) {
                $result[$value] = self::getAllFileByDirectory($dir . '/' . $value);
            } else {
                $name = str_replace('/', '\\', preg_replace('/\.(.*)$/', '', $name_fixed . $dir . '/' . $value));
                $result[$name] = $name;
            }
        }
        return $result;
    }

    public static function trim($array, $flagNotFalse = false) {
        $result = array();
        if (is_array($array) && count($array)) {
            foreach ($array as $key => $value) {
                $value = trim($value);
                if ($flagNotFalse) {
                    if ($value)
                        $result[$key] = $value;
                } else {
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }

    public static function addArray($array1, $array2) {
        foreach ($array2 as $key => $value) {
            $array1[] = $value;
        }
        return $array1;
    }

    public static function getDirectoryTemplate() {
        $listDir = scandir(APPLICATION_PATH . '/application');
        unset($listDir[0], $listDir[1]);
        $result = [];
        foreach ($listDir as $value) {
            if ($value != '.svn')
                $result[$value] = $value;
        }
        return $result;
    }

    public static function getValueByKey($list, $value) {
        $arrayValue = [];
        if (strpos($value, ',') !== null) {
            $array = explode(',', $value);
            foreach ($array as $vl) {
                $vl = trim($vl);
                if ($vl != "") {
                    $arrayValue[] = $vl;
                }
            }
        } else {
            $arrayValue[] = $value;
        }
        $result = [];
        if (count($arrayValue)) {
            foreach ($arrayValue as $v) {
                if (isset($list[$v])) {
                    $result[] = ' ' . preg_replace('/^[-]+ /', '', $list[$v]);
                }
            }
        }
        return count($result) ? implode(',', $result) : '';
    }

    public static function prinJson($value) {
        $html .= '';
        if ($value != "") {
            $array = (array) json_decode($value);
            foreach ($array as $key => $v) {
                $html .= "<p>$key => $v</p>";
            }
        }
        return $html;
    }

    public static function prinArrayJson($value) {
        $html = '';
        if ($value != "") {
            $array = (array) json_decode($value);
            foreach ($array as $key => $valueItem) {
                $valueItem = (array) $valueItem;
                $html .= '<p>';
                foreach ($valueItem as $k => $v) {
                    $html .= " $k => $v ";
                }
                $html .= '</p>';
            }
        }
        return $html;
    }

    public static function removeValueNull($attr) {
        $attributes = [];
        foreach ($attr as $key => $vl) {
            if ($vl !== NULL) {
                $attributes[$key] = $vl;
            }
        }
        return count($attributes) ? $attributes : false;
    }
    
    public static function getValueDiffrentToArrayOldNew($array_domain_id_old, $array_domain_id_new) {
        $array_new = [];
        if(count($array_domain_id_new)) {
            foreach($array_domain_id_new as $key => $value) {
                if(!in_array($value, $array_domain_id_old)) {
                    $array_new[] = $value;
                }
            }
        }
        $array_old = [];
        if(count($array_domain_id_old)) {
            foreach($array_domain_id_old as $key => $value) {
                if(!in_array($value, $array_domain_id_new)) {
                    $array_old[] = $value;
                }
            }
        }
        return [$array_old,$array_new];
    }
    
    public static function getLinkAndParam($linkUrl) {
        $a = explode('?',$linkUrl);
        $link = $a[0];
        $paramsLink = [];
        if(isset($a[1])) {
            $a1 = explode('&',$a[1]);
            foreach($a1 as $a2) {
                $a3 = explode('=',$a2);
                if(isset($a3[1])) {
                    $paramsLink[$a3[0]] = $a3[1];
                }
            }
        }
        return ['link' => $link, 'params' => $paramsLink];
    }
    
    public static function sortKeysDescGOOD(&$arrNew) {
        uksort($arrNew, function($a, $b) {
            $lenA = strlen($a);
            $lenB = strlen($b);
            if ($lenA == $lenB) {
                // If equal length, sort again by descending
                $arrOrig = array($a, $b);
                $arrSort = $arrOrig;
                rsort($arrSort);
                if ($arrOrig[0] !== $arrSort[0])
                    return 1;
            } else {
                // If not equal length, simple
                return $lenB - $lenA;
            }
        });
    }

    public static function getAttrSelectTrue($str) {
        $str = trim(preg_replace('/(FROM|from)[ ]*$/', '', $str));
        $array_select_attr_get = self::trim(self::explodeTrue($str));
        $rs = [];
        /* THIẾU TRƯỜNG HỢP - + * / */
        foreach ($array_select_attr_get as $value) {
            $value = preg_replace('/( as | AS )[^~]+/', '', $value);
            $value = str_replace('`','',$value);
            preg_match_all('/[a-zA-Z0-9_]+\.([a-zA-Z0-9_]+)/', $value, $matches_1);
            if (count($matches_1[1])) {
                foreach ($matches_1[1] as $k1 => $v1) {
                    $rs[$v1] = $v1;
                }
            } 
            preg_match_all('/\([ ]*([a-zA-Z0-9_]+)[ ]*\)/', $value, $matches_2);
            if (count($matches_2[1])) {
                foreach ($matches_2[1] as $k1 => $v1) {
                    $rs[$v1] = $v1;
                }
            }
            if(!count($matches_1[1]) && !count($matches_2[1])) {
                if(preg_match('/^[a-zA-Z0-9_]+$/', $value)) {
                    $rs[$value] = $value;
                }
            }
        }
        return array_keys($rs);
    }

    public static function getAttrSelectReplaceTrue($str) {
        $str = trim(preg_replace('/(^(SELECT|select))|((FROM|from)[ ]*$)/', '', $str));
        $array_select_attr_get = self::trim(self::explodeTrue($str));
        $rs = [];
        foreach ($array_select_attr_get as $value) {
            $value = str_replace('`','',$value);
            $value = preg_replace('/[^~]+( as | AS )/', '', $value);
            
            $rs[$value] = $value;
        }
        return array_keys($rs);
    }
    
    public static function getDongMO($content,$f) {
        $f_strtolower = strtolower($f);
        $f_strtoupper = strtoupper($f);
        $content = preg_replace("/($f_strtolower|$f_strtoupper|$f)[ ]*\(/",$f_strtoupper.'(',$content);
        $strpos = strpos($content,$f_strtoupper.'(');
        $str_p = $f_strtoupper.'(';
        $rs = [];
        while(($strpos = strpos($content,$str_p)) !== false) {
            $sub_strpos = strpos($content, ')',$strpos);
            $str_sub = substr($content,$strpos,$sub_strpos - $strpos + 1);
            while(count(explode('(',$str_sub)) != count(explode(')',$str_sub))) {
                $sub_strpos = strpos($content, ')',$sub_strpos + 1);
                $str_sub = substr($content,$strpos,$sub_strpos - $strpos + 1);
            }
            $replace = preg_replace('/('.$f_strtoupper.'\()|,[ ]*[a-zA-Z0-9_]+[ ]*\)$/', '', $str_sub);
            $rs[] = $str_sub;
            $content = str_replace($str_sub,$replace,$content);
        }
        return $rs;
    }
    
    public static function replaceDongMO($content,$f) {
        $f_strtolower = strtolower($f);
        $f_strtoupper = strtoupper($f);
        $content = preg_replace("/($f_strtolower|$f_strtoupper|$f)[ ]*\(/",$f_strtoupper.'(',$content);
        $strpos = strpos($content,$f_strtoupper.'(');
        $str_p = $f_strtoupper.'(';
        while(($strpos = strpos($content,$str_p)) !== false) {
            $sub_strpos = strpos($content, ')',$strpos);
            $str_sub = substr($content,$strpos,$sub_strpos - $strpos + 1);
            while(count(explode('(',$str_sub)) != count(explode(')',$str_sub))) {
                $sub_strpos = strpos($content, ')',$sub_strpos + 1);
                $str_sub = substr($content,$strpos,$sub_strpos - $strpos + 1);
            }
            $replace = preg_replace('/('.$f_strtoupper.'\()|,[ ]*[a-zA-Z0-9_]+[ ]*\)$/', '', $str_sub);
            $content = str_replace($str_sub,$replace,$content);
        }
        return $content;
    }
    
    public static function optimazeStrToDate($content) {
        $array_str_to_date = self::getDongMO($content, 'str_to_date');
        $rs = [];
        foreach($array_str_to_date as $key => $value) {
            $value_new = preg_replace('/STR_TO_DATE\(|\'|"|\%|\)$/','',$value);
            $a = explode(',',$value_new);
            $rs[$value] = UtilityDateTime::getIntFromDate($a[0],$a[1]);
            $rs[str_replace('STR_TO_DATE','str_to_date',$value)] = $rs[$value];
        }
        $content = self::replace($rs, $content);
        $content = preg_replace_callback('/DateStats[ ]+(\>\=|\<\=)[ ]+([0-9]{8,10})/',function($matches){
            return 'DateStats '.$matches[1].' '.$matches[2];
        },$content);
        return $content;
    }
    
    public static function optimazeDatediff($content) {
        $array_date_diff = self::getDongMO($content, 'datediff');
        $rs = [];
        foreach($array_date_diff as $key => $value) {
            $value_new = preg_replace('/DATEDIFF\(|\'|"|\%|\)$/','',$value);
            $a = explode(',',$value_new);
            if(preg_match('/[0-9]{8,10}/',$a[1])) {
                if(strtolower($a[0]) == 'datestats') {
                    $a[0] = 'DateStats';
                }
                $rs[$value] = '('.$a[0].' - '.$a[1].') / 86400 ';
                $rs[str_replace('DATEDIFF','datediff',$value)] = $rs[$value];
            }
        }
        $content = self::replace($rs, $content);
        return $content;
    }
    
    public static function getSelectAttrInSql($content) {
        preg_match_all('/(SELECT|select)([^~]+?(FROM|from))/',$content,$matches);
        $rs = false;
        if(count($matches)) {
            $rs = [];
            foreach($matches[2] as $key => $value) {
                $select_attr_str = preg_replace('/(FROM|from)$/','',$value);
                $rs = array_merge($rs,self::explodeTrue($select_attr_str));
            }
        }
        return $rs;
    }
    
    public static function removeDivide($content) {
        if($array_divide = self::getSelectAttrInSql($content)) {
            $rs = [];
            foreach($array_divide as $key => $value) {
                if(strpos($value, '/') > 0) {
                    $rs[$value.','] = '';
//                    $rs[$value] = '';
                }
            }
            $content = self::replace($rs, $content);
        }
        return $content;
    }
    
    public static function optimazeApacheKylin($content) {
        $content = self::replaceDongMO($content, 'ifnull');
        $content = self::replaceDongMO($content, 'Round');
        $content = preg_replace('/Position|position|POSITION/', 'avgposition', $content);
        $content = str_replace('!=',' <> ',$content);
        $content = self::optimazeStrToDate($content);
        $content = self::optimazeDatediff($content);
        $content = preg_replace_callback('/([a-zA-Z0-9_]+)\.([a-zA-Z0-9_]+)[ ]+(as|AS)[ ]+([a-zA-Z0-9_]+)[ ]*,/',function($matches){
            if($matches[2] == $matches[4]) {
                return $matches[1].'.'.$matches[4].',';
            }
            return $matches[0];
        },$content);
        
        $array_replace = [
            'mtx_google_shard_5684612545_2016'  => 'mtx_google_5684612545',
            'tbl_google_stats_keywords_summary' => 'TBL_GOOGLE_KEYWORD_STATS_DEFAULT_ORC_2',
            'tbl_google_adgroup'                => 'TBL_GOOGLE_ADGROUP_NEW_ORC',
            'tbl_google_campaign'               => 'TBL_GOOGLE_CAMPAIGN_NEW_ORC',
            'tbl_google_keyword'                => 'TBL_GOOGLE_KEYWORD_NEW_ORC',
        ];
        
        $content = self::replace($array_replace, $content);
        $content = str_replace('`','',$content);
        return $content;
    }

}
