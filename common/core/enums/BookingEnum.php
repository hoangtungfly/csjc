<?php

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;
class BookingEnum extends GlobalEnumBase{
    const TITLE_DEFAULT = 'mr';
    public static function getTitle(){
        return [
            'mr' => 'Mr',
            'mrs' => 'Mrs',
            'ms' => 'Ms',
        ];
    }
}
