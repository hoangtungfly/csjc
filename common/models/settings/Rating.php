<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "rating".
 *
 * @property integer $id
 * @property integer $did
 * @property string $table_name
 * @property integer $point
 * @property integer $created_time
 * @property integer $created_by
 * @property string $ip
 */
class Rating extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['did', 'table_name'], 'required'],
            [['did', 'point', 'created_time', 'created_by'], 'integer'],
            [['table_name'], 'string', 'max' => 255],
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
            'did' => 'Did',
            'table_name' => 'Table Name',
            'point' => 'Point',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'ip' => 'Ip',
        ];
    }
}
