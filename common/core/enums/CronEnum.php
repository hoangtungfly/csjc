<?php

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;
class CronEnum extends GlobalEnumBase{
    const TYPE_TEXT = 'text';
    const TYPE_IMAGE = 'image';
    const TYPE_IMAGES = 'images';
    const TYPE_CONTENT = 'content';
    const TYPE_DATETIME_DANTRI = 'datetime_dantri';
    const TYPE_TAG = 'tag';
    const TYPE_PRICE = 'price';
    const TYPE_DATETIME_PHAPLUAT = 'datetime_phapluat';
    
    public static function cronTypeLabel(){
        return [
            self::TYPE_TEXT => 'Text',
            self::TYPE_IMAGE => 'Image',
            self::TYPE_IMAGES => 'Images',
            self::TYPE_CONTENT => 'Content',
            self::TYPE_DATETIME_DANTRI => 'Datetime dantri',
            self::TYPE_TAG => 'Tag',
            self::TYPE_DATETIME_PHAPLUAT => 'Datetime phapluat',
            self::TYPE_PRICE => 'Price',
        ];
    }
}