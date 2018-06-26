<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "color".
 *
 * @property integer $id
 * @property string $name
 * @property string $cl
 * @property integer $status
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property integer $odr
 */
class Color extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'color';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_time', 'created_by', 'modified_time', 'modified_by', 'odr'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['cl'], 'string', 'max' => 10]
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
            'cl' => 'Cl',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'odr' => 'Odr',
        ];
    }
}
