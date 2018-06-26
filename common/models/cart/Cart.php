<?php

namespace common\models\cart;

use common\core\dbConnection\GlobalActiveRecord;
use common\models\product\Product;
use common\models\product\ProductSearch;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "cart".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $session_id
 * @property double $price
 * @property integer $count
 * @property integer $status
 * @property string $ip
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 *
 * @property Product $product
 */
class Cart extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'session_id', 'price'], 'required'],
            [['product_id', 'count', 'status', 'created_time', 'modified_time', 'created_by', 'modified_by'], 'integer'],
            [['price'], 'number'],
            [['session_id'], 'string', 'max' => 50],
            [['ip'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'session_id' => 'Session ID',
            'price' => 'Price',
            'count' => 'Count',
            'status' => 'Status',
            'ip' => 'Ip',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
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
