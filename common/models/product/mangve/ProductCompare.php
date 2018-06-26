<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product_compare".
 *
 * @property integer $id
 * @property integer $product_id
 * @property string $session_id
 * @property string $ip
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $status
 */
class ProductCompare extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_compare';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'session_id'], 'required'],
            [['product_id', 'created_time', 'modified_time', 'created_by', 'modified_by', 'status'], 'integer'],
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
            'ip' => 'Ip',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'status' => 'Status',
        ];
    }
}
