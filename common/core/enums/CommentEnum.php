<?php

use common\core\enums\base\GlobalEnumBase;

class CommentEnum extends GlobalEnumBase{
    const ClOSE = 0;
    const OPEN = 1;
    
    public static function getLabletitle(){
        return array(
            self::ClOSE => 'close', 
            self::OPEN  => 'open',
        );
    }
}