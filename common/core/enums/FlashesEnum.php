<?php

/**
 * define status Ex: actived, deleted...
 * 
 * @author Phong Pham Hong
 */

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;

class FlashesEnum extends GlobalEnumBase {

    const KEY_ERROR = 'error';
    const KEY_SUCCESS = 'success';
    const KEY_INFO = 'info';
    const KEY_WARRING = 'warring';

}
