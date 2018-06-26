<?php

namespace common\core\enums\product;

use common\core\enums\base\GlobalEnumBase;

class ProductEnum extends GlobalEnumBase {
    const SELECT = 'id,name,alias,price,price_old,image,image2,product.category_id,product.created_time';
    
    public static function arrange() {
        return [
            'id desc'  => 'SẢN PHẨM MỚI',
            'id'  => 'SẢN PHẨM CŨ',
            'name'  => 'TÊN A-Z',
            'name desc'  => 'TÊN Z-A',
//            'price'  => 'GIÁ',
        ];
    }
    
    public static function show() {
        return [
            9    => 9,
            15   => 15,
            21   => 21,
            27   => 27,
            30   => 30,
            40   => 40,
        ];
    }
    
    public static function productStatus() {
        return [
            0       => 'Hết hàng',
            1       => 'Còn hàng',
            2       => 'Hàng mới về',
        ];
    }
    
    public static function productSize() {
        $a = [];
        for($i = 1; $i < 50;$i++) {
            $a[$i] = $i;
        }
        return $a;
    }
}
