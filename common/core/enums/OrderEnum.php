<?php

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;

class OrderEnum extends GlobalEnumBase {
    const ORDER_PENDING = 0;
    const ORDER_SUCCESS = 1;
    const ORDER_CANCEL = 2;
    public static function getOrderStatusLabel() {
        return [
        self::ORDER_PENDING => 'Chưa mua hàng',
        self::ORDER_SUCCESS => 'Mua hàng thành công',
        self::ORDER_CANCEL => 'Hủy đơn hàng',
        ];
    }
}

