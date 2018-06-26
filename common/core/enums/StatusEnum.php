<?php

/**
 * define status Ex: actived, deleted...
 * 
 * @author Phong Pham Hong
 */

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;

class StatusEnum extends GlobalEnumBase {

    /**
     * const status
     */
    const STATUS_ACTIVED = 1;
    const STATUS_DEACTIVED = 0;
    const STATUS_REMOVED = 20;
    const DEFAULT_SIZE = 5;
    const DEFAULT_SIZE_BACKEND = 10;
    const DEFAULT_SIZE_MAX_BACKEND = 200;
    const STATUS_IS_CHANGED = 1;
    /*
     * type of device
     */
    const TYPE_DEVICE_DESKTOP= 'desktop';
    const TYPE_DEVICE_MOBILE= 'mobile';
    
    protected $enumLabels = [
        self::STATUS_ACTIVED => 'Active',
        self::STATUS_DEACTIVED => 'InActive',
        self::STATUS_REMOVED => 'Removed',
    ];

}
