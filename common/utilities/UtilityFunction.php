<?php

namespace common\utilities;

class UtilityFunction {

    public static function getFunction($contentFile, $action) {
        $array = [];
        $result = false;
        if (preg_match("/public(.*)?(function)(\s)+{$action}[^~]+?{/", $contentFile, $array)) {
            $startLine = $array[0];
            $arrayExplode = explode($startLine, $contentFile);
            $arrayExplode = explode('}', $arrayExplode[1]);
            $result = $startLine;
            $count = 1;
            foreach ($arrayExplode as $key => $value) {
                $array = [];
                if (preg_match_all('/\{/', $value, $array)) {
                    $count += count($array[0]);
                }
                $count--;
                $result .= $value . '}';
                if (!$count)
                    break;
            }
        }
        return $result;
    }
    
    public static function getFunctionAction($contentFile, $action) {
        return self::getFunction($contentFile, 'action' . updateUpperFirstCharacter($action));
    }

    public static function getFunctionControllerApp($contentFile, $name) {
        $name = strtolower(trim($name));
        if (!$name)
            return false;
        $name{0} = strtoupper($name{0}); // 
        $array = [];
        $result = false;
        if (preg_match("/controllers\.controller\(('|\"){$name}Controller[^~]+?{/", $contentFile, $array)) {
            $startLine = $array[0];
            $arrayExplode = explode($startLine, $contentFile);
            $arrayExplode = explode(']);', $arrayExplode[1]);
            $result = $startLine;
            $count = 1;
            foreach ($arrayExplode as $key => $value) {
                $array = [];
                if (preg_match_all('/\]\);/', $value, $array)) {
                    $count += count($array[0]);
                }
                $count--;
                $result .= $value . ']);';
                if (!$count)
                    break;
            }
        }
        return $result;
    }

    public static function getFunctionDirectiveApp($contentFile, $name) {
        $name = strtolower(trim($name));
        if (!$name)
            return false;
        $array = [];
        $result = false;
        if (preg_match("/app\.directive\('{$name}Ang[^~]+?{/", $contentFile, $array)) {
            $startLine = $array[0];
            $arrayExplode = explode($startLine, $contentFile);
            $arrayExplode = explode(']);', $arrayExplode[1]);
            $result = $startLine;
            $count = 1;
            foreach ($arrayExplode as $key => $value) {
                $array = [];
                if (preg_match_all('/\]\);/', $value, $array)) {
                    $count += count($array[0]);
                }
                $count--;
                $result .= $value . ']);';
                if (!$count)
                    break;
            }
        }
        return $result;
    }
    
    public static function getLang() {
        if(session()->has('lang')) {
            return session()->get('lang');
        }
        $lang = app()->language;
        switch ($lang) {
            case 'vi-VI' :
            case 'vi' : 
                $lang = 'vi';
            default :
                $lang = 'vi';
        }
        session()->set('lang', $lang);
        return $lang;
    }
}
