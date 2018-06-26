<?php
namespace common\core\enums;
use common\core\enums\base\GlobalEnumBase;

class EmailEnum extends GlobalEnumBase{
    const ADMIN_CONFIRMATION  = 'admin_confirmation';
    const CUSTOMER_CONFIRMATION = 'customer_confirmation';
    const CUSTOMER_VOUCHER = 'customer_voucher';
    const FRIEND_VOUCHER = 'friend_voucher';
    const CUSTOMER_FRIEND_VOUCHER ='customer_friend_voucher';
}
