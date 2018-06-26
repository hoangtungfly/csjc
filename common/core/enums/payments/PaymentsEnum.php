<?php

/**
 * define status Ex: actived, deleted...
 * 
 */

namespace common\core\enums\payments;

use common\core\enums\base\GlobalEnumBase;

class PaymentsEnum extends GlobalEnumBase {

    const PAYMENT_STATUS_ACTIVE = 1;
    const PAYMENT_STATUS_DEACTIVE = 0;
    const PAYMENT_TEST_ACTIVE = 1;
    const PAYMENT_TEST_DEACTIVE = 0;
    const DEFAULT_PAYMENT_GETWAY = 1;
    const IS_PRIMARY = 1;
    
    const PREFIX_ORDER_ID_TEXT = 'webadmanager_';
}
