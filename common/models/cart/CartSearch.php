<?php

namespace common\models\cart;

use common\core\enums\CartEnum;
use common\core\enums\StatusEnum;
use common\models\order\OrderProductSearch;
use common\models\product\ProductSearch;
use common\models\settings\MailSettingsSearch;
use common\utilities\UtilityHtmlFormat;
use common\utilities\UtilityUrl;

class CartSearch extends Cart {

    public static function addCart($id, $count = 1) {
        $id = (int) $id;
        $session_id = session()->get(CartEnum::CART_KEY);
        $modelProduct = ProductSearch::findOne($id);
        if ($modelProduct) {
            $model = self::findOne(['product_id' => $id, 'session_id' => $session_id, 'status' => StatusEnum::STATUS_DEACTIVED]);
            if (!$model) {
                $model = new CartSearch();
                $model->product_id = $id;
                $model->session_id = $session_id;
                $model->count = $count;
                $model->price = $modelProduct->price;
                $model->status = StatusEnum::STATUS_DEACTIVED;
            } else {
                if ($model->status == StatusEnum::STATUS_REMOVED) {
                    $model->status = StatusEnum::STATUS_DEACTIVED;
                    $model->count = $count;
                } else {
                    $model->count += $count;
                }
            }
            $model->save(false);
            return $modelProduct;
        }
        return false;
    }

    public static function changeCart($id, $count = 1) {
        $id = (int) $id;
        $session_id = session()->get(CartEnum::CART_KEY);
        $modelProduct = ProductSearch::findOne($id);
        if ($modelProduct) {
            $model = self::findOne(['product_id' => $id, 'session_id' => $session_id, 'status' => StatusEnum::STATUS_DEACTIVED]);
            if ($model) {
                $model->count = $count;
                $model->status = StatusEnum::STATUS_DEACTIVED;
                $model->save(false);
            }
        }
        return $modelProduct;
    }

    public static function deleteCart($id) {
        $id = (int) $id;
        $session_id = session()->get(CartEnum::CART_KEY);
        $model = self::findOne(['product_id' => $id, 'session_id' => $session_id, 'status' => StatusEnum::STATUS_DEACTIVED]);
        if ($model) {
            $model->status = StatusEnum::STATUS_REMOVED;
            $model->save(false);
        }
        return ProductSearch::findOne($id);
    }

    public static function viewCart() {
        $session_id = session()->get(CartEnum::CART_KEY);
        if ($session_id) {
            $list = self::find()->select("cart.product_id,cart.id,cart.price,cart.count,product.image,product.name,product.alias,product.color,product.size,product.status_product")
                            ->joinWith('product')->where([
                        'cart.session_id' => $session_id,
                        'cart.status' => StatusEnum::STATUS_DEACTIVED,
                    ])->asArray()->all();
            if ($list) {
                $sub_total = 0;
                foreach ($list as $key => $item) {
                    if ($item['image']) {
                        $item['image'] = ProductSearch::getimage([98, 98], $item['image']);
                    }
                    if ($item['alias']) {
                        $item['link_main'] = UtilityUrl::createUrl('/' . WEBNAME . '/product/index', [
                                    'alias' => $item['alias']
                        ]);
                    }
                    $item['total'] = $item['count'] * $item['price'];
                    $sub_total += $item['total'];
                    $item['price_str'] = UtilityHtmlFormat::numberFormatPrice($item['price']);
                    $item['total_str'] = UtilityHtmlFormat::numberFormatPrice($item['total']);
                    $list[$key] = $item;
                }
                $total = $sub_total;
                return [
                    'cart_count' => count($list),
                    'Carts' => $list,
                    'sub_total' => UtilityHtmlFormat::numberFormatPrice($sub_total),
                    'total' => UtilityHtmlFormat::numberFormatPrice($total),
                    'total_float' => $total,
                    'sub_total_float' => $sub_total,
                ];
            }
        }
        return [
                'cart_count' => 0,
                'Carts' => false,
                'sub_total' => '',
                'total' => '',
                'total_float' => '',
                'sub_total_float' => '',
            ];
    }

    public static function insertCart($model) {
        $session_id = session()->get(CartEnum::CART_KEY);
        $order_id = (int) $model->id;
        if ($order_id && $session_id) {
            $list = self::find()->where([
                        'session_id' => $session_id,
                        'status' => StatusEnum::STATUS_DEACTIVED,
                    ])->all();
            if ($list) {
                $sub_total = 0;
                foreach ($list as $key => $item) {
                    $orderProduct = new OrderProductSearch();
                    $orderProduct->count = $item->count;
                    $orderProduct->order_id = $order_id;
                    $orderProduct->price = $item->price;
                    $orderProduct->product_id = $item->product_id;
                    $orderProduct->save(false);
                    $item->status = StatusEnum::STATUS_ACTIVED;
                    $item->save();
                }
                MailSettingsSearch::sendOrderMailler($model);
            }
        }
    }

    public static function countCart() {
        return self::find()->joinWith('product')->where([
                    'cart.session_id' => session()->get(CartEnum::CART_KEY),
                    'cart.status' => StatusEnum::STATUS_DEACTIVED,
                ])->count();
    }

    public static function priceCart() {
        $model = self::find()->select('`cart`.product_id,`cart`.id,sum(`cart`.`price` * `cart`.`count`) as `price`')->joinWith('product')->where([
                    'cart.session_id' => session()->get(CartEnum::CART_KEY),
                    'cart.status' => StatusEnum::STATUS_DEACTIVED,
                ])->asArray()->one();
        if($model) {
            return number_format($model['price']);
        }
        return 0;
    }

}
