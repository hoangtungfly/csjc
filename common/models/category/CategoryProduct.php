<?php

namespace common\models\category;

use Yii;

/**
 * This is the model class for table "category_product".
 *
 * @property integer $category_id
 * @property integer $product_id
 * @property integer $created_by
 * @property integer $created_time
 */
class CategoryProduct extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'product_id'], 'required'],
            [['category_id', 'product_id', 'created_by', 'created_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'product_id' => 'Product ID',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
        ];
    }
}
