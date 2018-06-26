<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_message".
 *
 * @property integer $id
 * @property string $name
 * @property string $message_key
 * @property string $message_value
 * @property string $lang
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $modified_time
 */
class SettingsMessage extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'message_key', 'message_value'], 'required'],
            [['message_value'], 'string'],
            [['created_time', 'created_by', 'modified_by', 'modified_time'], 'integer'],
            [['name', 'message_key'], 'string', 'max' => 255],
            [['lang'], 'string', 'max' => 5]
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
            'message_key' => 'Message Key',
            'message_value' => 'Message Value',
            'lang' => 'Lang',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'modified_time' => 'Modified Time',
        ];
    }
}
