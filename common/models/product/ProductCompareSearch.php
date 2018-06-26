<?php

namespace common\models\product;

use common\core\enums\product\ProductEnum;
use common\core\enums\ProductcompareEnum;
use common\core\enums\StatusEnum;
use common\utilities\UtilityHtmlFormat;
use common\utilities\UtilityUrl;
use yii\helpers\ArrayHelper;
use yii\redis\ActiveQuery;

class ProductCompareSearch extends ProductCompare {

    public static function addProductCompare($id) {
        $id = (int) $id;
        $session_id = session()->get(ProductcompareEnum::PRODUCTCOMPARE_KEY);
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

    public static function deleteProductCompare($id) {
        $id = (int) $id;
        $session_id = session()->get(ProductcompareEnum::PRODUCTCOMPARE_KEY);
        $model = self::findOne(['product_id' => $id, 'session_id' => $session_id, 'status' => StatusEnum::STATUS_ACTIVED]);
        if ($model) {
            $model->status = StatusEnum::STATUS_REMOVED;
            $model->save(false);
        }
        return ProductSearch::findOne($id);
    }

    public static function viewProductCompare($w_h = [30,30]) {
        $session_id = session()->get(ProductcompareEnum::PRODUCTCOMPARE_KEY);
        if ($session_id) {
            $productStatus = ProductEnum::productStatus();
            $color = ArrayHelper::map(ColorSearch::getAll(),'id','name');
            $manufacturer = ArrayHelper::map(ManufacturerSearch::getAll(),'id','name');
            $select = "product_compare.product_id,product_compare.id,product.image,product.name,product.alias,product.code,product.manufacturer";
            $select .= ",product.description,product.price,product.price_old,product.color,product.status_product,product.size";
            $list = self::find()->select($select)
                            ->joinWith('product')->where([
                        'product_compare.session_id' => $session_id,
                        'product_compare.status' => StatusEnum::STATUS_ACTIVED,
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
                    } else {
                        $item['link_main'] = '';
                    }
                    $item['status_product'] = isset($productStatus[$item['status_product']]) ? $productStatus[$item['status_product']] : '';
                    $item['price_str'] = UtilityHtmlFormat::numberFormatPrice($item['price']);
                    $item['price_old_str'] = UtilityHtmlFormat::numberFormat($item['price_old']);
                    $item['manufacturer'] = isset($manufacturer[$item['manufacturer']]) ? $manufacturer[$item['manufacturer']] : '';
                    $item['color_old'] = $item['color'];
                    if($item['color']) {
                        $a = explode(',',$item['color']);
                        foreach($a as $k => $vl) {
                            $a[$k] = isset($color[$vl]) ? $color[$vl] : '';
                        }
                        $item['color'] = implode(', ',$a);
                    }
                    $list[$key] = $item;
                }
                $total = $sub_total;
                return [
                    'product_compare_count' => count($list),
                    'ProductCompares' => $list,
                ];
            }
        }
        return [
            'product_compare_count' => 0,
            'ProductCompares' => [],
        ];
    }
    
    

    /**
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProductSearch::className(), ['id' => 'product_id']);
    }

}