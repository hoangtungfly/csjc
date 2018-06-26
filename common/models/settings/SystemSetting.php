<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "system_setting".
 *
 * @property integer $id
 * @property integer $created_by
 * @property integer $created_time
 * @property string $option_key
 * @property string $option_value
 * @property string $lang
 * @property string $type
 */
class SystemSetting extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_setting';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'created_time'], 'integer'],
            [['option_key'], 'required'],
            [['option_value'], 'string'],
            [['option_key', 'type'], 'string', 'max' => 255],
            [['lang'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'option_key' => 'Option Key',
            'option_value' => 'Option Value',
            'lang' => 'Lang',
            'type' => 'Type',
        ];
    }
}
