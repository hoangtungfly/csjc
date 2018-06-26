<?php

/**
 * define status Ex: actived, deleted...
 * 
 */

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;

class SystemTokenEnum extends GlobalEnumBase {

    //type for register user
    const TOKEN_REGISTER_USER = 1;
    const TOKEN_RESET_PW_USER = 2;

    /* expired time default */
    const EXPIRED_TIME_DEFAULT = 86400; // 1 month

}
