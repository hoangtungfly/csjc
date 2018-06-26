<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "manufacturer".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property integer $odr
 * @property integer $category_id
 */
class Manufacturer extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'manufacturer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_time', 'created_by', 'modified_time', 'modified_by', 'category_id', 'odr'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'odr' => 'Odr',
            'category_id' => 'Category Id',
        ];
    }
}
