<?php
namespace common\core\enums;
use common\core\enums\base\GlobalEnumBase;

class CartEnum extends GlobalEnumBase{
    const CART_KEY  = 'shopping_cart';
    const PAYMENT_KEY  = 'data_payment';
    const BOOKING_KEY = 'booking';
    const MIN_ORDER = 1;
    const MAX_ORDER = 999;
    
}
