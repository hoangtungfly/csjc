<?php

namespace common\models\lib;

use Yii;

/**
 * This is the model class for table "lib_timezones".
 *
 * @property integer $id
 * @property string $name
 * @property string $display
 * @property string $gmt
 * @property string $city
 * @property string $minutes
 * @property boolean $iddst
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 */
class LibTimezones extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lib_timezones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'minutes', 'created_time', 'created_by', 'modified_time', 'modified_by'], 'integer'],
            [['iddst'], 'boolean'],
            [['name'], 'string', 'max' => 61],
            [['display'], 'string', 'max' => 91],
            [['gmt'], 'string', 'max' => 50],
            [['city'], 'string', 'max' => 85]
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
            'display' => 'Display',
            'gmt' => 'Gmt',
            'city' => 'City',
            'minutes' => 'Minutes',
            'iddst' => 'Iddst',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
        ];
    }
}
