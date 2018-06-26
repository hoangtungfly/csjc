<?php

namespace common\models\product;

use common\core\enums\ProductcompareEnum;
use common\core\enums\StatusEnum;
use common\utilities\UtilityHtmlFormat;
use common\utilities\UtilityUrl;

class ProductCompareSearch extends ProductCompare {
    public static function addProductcompare($id) {
        $id = (int) $id;
        $session_id = session()->get(ProductcompareEnum::WISHLIST_KEY);
        $modelProduct = ProductSearch::findOne($id);
        if ($modelProduct) {
            $model = self::findOne(['product_id' => $id, 'session_id' => $session_id]);
            if (!$model) {
                $model = new ProductCompareSearch();
                $model->product_id = $id;
                $model->session_id = $session_id;
            }
            $model->status = StatusEnum::STATUS_ACTIVED;
            $model->save(false);
            return $modelProduct;
        }
        return false;
    }

    public static function deleteProductcompare($id) {
        $id = (int) $id;
        $session_id = session()->get(ProductcompareEnum::WISHLIST_KEY);
        $model = self::findOne(['product_id' => $id, 'session_id' => $session_id, 'status' => StatusEnum::STATUS_ACTIVED]);
        if ($model) {
            $model->status = StatusEnum::STATUS_REMOVED;
            $model->save(false);
        }
    }

    public static function viewProductcompare() {
        $session_id = session()->get(ProductcompareEnum::WISHLIST_KEY);
        if ($session_id) {
            $list = self::find()->select("product_compare.product_id,product_compare.id,product.image,product.name,product.alias")
                            ->joinWith('product')->where([
                        'cart.session_id' => $session_id,
                        'cart.status' => StatusEnum::STATUS_ACTIVED,
                    ])->asArray()->all();
            if ($list) {
                $sub_total = 0;
                foreach ($list as $key => $item) {
                    if ($item['image']) {
                        $item['image'] = ProductSearch::getimage([30, 30], $item['image']);
                    }
                    if ($item['alias']) {
                        $item['link_main'] = UtilityUrl::createUrl('/' . WEBNAME . '/product/index', [
                            'alias' => $item['alias']
                        ]);
                    }
                    $item['price_str'] = UtilityHtmlFormat::numberFormat($item['price']);
                    $list[$key] = $item;
                }
                $total = $sub_total;
                return [
                    'product_compare_count' => count($list),
                    'Productcompares' => $list,
                ];
            }
        }
        return false;
    }
}