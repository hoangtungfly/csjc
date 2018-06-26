<?php

namespace common\models\store;

use Yii;

/**
 * This is the model class for table "store_params".
 *
 * @property integer $id
 * @property string $store_name
 * @property string $store_params
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $adword_type
 * @property integer $adword_parent_type
 */
class StoreParams extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_params';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_params'], 'string'],
            [['created_time', 'modified_time', 'created_by', 'modified_by', 'adword_type', 'adword_parent_type'], 'integer'],
            [['store_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'store_name' => 'Store Name',
            'store_params' => 'Store Params',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'adword_type' => 'Adword Type',
            'adword_parent_type' => 'Adword Parent Type',
        ];
    }
}
