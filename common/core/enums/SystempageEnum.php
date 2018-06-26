<?php

/**
 * define status Ex: actived; deleted...
 * 
 * @author Phong Pham Hong
 */

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;

class SystempageEnum extends GlobalEnumBase {

    const SIGNUP = 1;
    const LOGIN = 2;
    const RESET_PASSWORD = 3;
    const FORGOT_PASSWORD = 4;
    const PROFILE = 5;
    const CHANGE_PASSWORD = 6;
    const RELEASE_NOTE = 7;
    const PAYMENT = 8;
    const CHANGE_PLAN_PAYMENT = 9;
}