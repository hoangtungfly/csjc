<?php
/**
 * define status Ex: actived, deleted...
 * 
 * @author Phong Pham Hong
 */

namespace common\core\enums\admin;

use common\core\enums\base\GlobalEnumBase;

class AdminEnum extends GlobalEnumBase {
    
    public static function lineNInN() {
        return array(
            0 => '1 line',
            1 => '2 in 1',
            2 => '3 in 1',
            3 => '4 in 1',
        );
    }
    
    
    /* BEGIN FORM LINE */
    const FORM_LINE_2IN1 = 1;
    const FORM_LINE_3IN1 = 2;
    const FORM_LINE_4IN1 = 3;
    /* END FORM LINE */
    
    public static function messageFile() {
        $link = \Yii::getAlias('@common').'/messages/en/';
        $dir = scandir($link);
        unset($dir[0]);unset($dir[1]);
        $result = array();
        foreach($dir as $key => $value) {
            $result[$value] = $value;
        }
        return $result;
    }
}