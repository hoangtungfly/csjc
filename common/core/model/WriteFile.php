<?php

namespace common\core\model;

use Yii;
class WriteFile {
    
    public static function writeFileDropDownList($array,$file) {
        $data = "<?php \nreturn ";
        self::writeFileArray($array, $data);
        $data .= ";";
        self::writeFile($file, $data);
    }
    
    
    public static function writeFileArray($array,&$data,$count = 0) {
        $data .= "[";
//        $data .= "\n";
        $count++;
        if($array && count($array)) {
            foreach($array as $key => $value) {
//                for($i = 0; $i < $count; $i++) {
//                    $data .= "\t";
//                }
                $data .= is_int($key) ? $key : "'$key'";
                $data .= ' => ';
                if(is_int($value)) {
                    $data .= $value; 
                } else if(is_array($value)) {
                    self::writeFileArray($value, $data, $count);
                } else {
                    $data .= "'".  str_replace("'", "\\'", trim($value))."'";
                }
                $data .= ",";
                
//                $data .= "\n";
            }
        }
//        for($i = 1; $i < $count; $i++) {
//            $data .= "\t";
//        }
        $data .= "]";
    }
    
    public static function getFile($file) {
        if(self::isFile($file)) {
            return require Yii::getAlias('@cache') . '/' . $file.'.php';
        } else {
            return false;
        }
        
    }
    
    public static function isFile($file) {
        if(is_file(Yii::getAlias('@cache') . '/' . $file.'.php')) {
            return true;
        } else {
            return false;
        }
    }
    
    public static function writeFile($file,$data) {
        if(!preg_match('/\./', $file))
                $file .= '.php';
        file_put_contents(Yii::getAlias('@cache') . '/' . $file, $data);
    }
    
    public static function deleteFile($file) {
        if(!is_array($file)) {
            $file = [$file];
        }
        foreach($file as $value) {
            if(self::isFile($value)) {
                unlink(Yii::getAlias('@cache') . '/' . $value.'.php');
            }
        }
    }
}