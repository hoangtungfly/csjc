<?php

namespace common\models\product;

use common\core\enums\StatusEnum;
use common\core\enums\WishlistEnum;
use common\utilities\UtilityHtmlFormat;
use common\utilities\UtilityUrl;

class WishlistSearch extends Wishlist {

    public static function addWishlist($id) {
        $id = (int) $id;
        $session_id = session()->get(WishlistEnum::WISHLIST_KEY);
        $modelProduct = ProductSearch::findOne($id);
        if ($modelProduct) {
            $model = self::findOne(['product_id' => $id, 'session_id' => $session_id]);
            if (!$model) {
                $model = new WishlistSearch();
                $model->product_id = $id;
                $model->session_id = $session_id;
            }
            $model->status = StatusEnum::STATUS_ACTIVED;
            $model->save(false);
            return $modelProduct;
        }
        return false;
    }

    public static function deleteWishlist($id) {
        $id = (int) $id;
        $session_id = session()->get(WishlistEnum::WISHLIST_KEY);
        $model = self::findOne(['product_id' => $id, 'session_id' => $session_id, 'status' => StatusEnum::STATUS_ACTIVED]);
        if ($model) {
            $model->status = StatusEnum::STATUS_REMOVED;
            $model->save(false);
        }
        return ProductSearch::findOne($id);
    }

    public static function viewWishlist($w_h = [30, 30]) {
        $session_id = session()->get(WishlistEnum::WISHLIST_KEY);
        if ($session_id) {
            $list = self::find()->select("wishlist.product_id,wishlist.id,product.image,product.name,product.alias,product.price,product.color,product.size,product.description,product.status_product")
                            ->joinWith('product')->where([
                        'wishlist.session_id' => $session_id,
                        'wishlist.status' => StatusEnum::STATUS_ACTIVED,
                    ])->asArray()->all();
            if ($list) {
                $sub_total = 0;
                foreach ($list as $key => $item) {
                    if ($item['image']) {
                        $item['image'] = ProductSearch::getimage($w_h, $item['image']);
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
                    'wishlist_count' => count($list),
                    'Wishlists' => $list,
                ];
            }
        }
        return [];
    }
    
    

    /**
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProductSearch::className(), ['id' => 'product_id']);
    }

}
