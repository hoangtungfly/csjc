<?php

namespace common\models\order;

use common\models\product\ProductSearch;
use yii\db\ActiveQuery;

class OrderProductSearch extends OrderProduct
{

    /**
     * @return ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(OrderSearch::className(), ['id' => 'order_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ProductSearch::className(), ['id' => 'product_id']);
    }
}
