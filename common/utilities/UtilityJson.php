<?php

namespace common\utilities;

class UtilityJson {

    
    public static function printJson($array) {
        $string = '';
        $count = count($array);
        if($count) {
            $string .= "{\n";
            $i = 1;
            foreach($array as $key => $value) {
                $string .= "\t\"$key\" : ";
                if(is_array($value)) {
                    self::printArray($value);
                } else if(is_int($value)) {
                    $string .= $value;
                } else {
                    $string .= "\"".  str_replace("'", "\\'", $value)."\"";
                }
                if($i < $count) {
                    $string .= ",";
                }
                $string .= "\n";
                $i++;
            }
            $string .= "}";
        }
        return $string;
    }
    
}