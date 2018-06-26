<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "mail_settings".
 *
 * @property integer $id
 * @property string $mail_key
 * @property string $mail_title
 * @property string $mail_subject
 * @property string $mail_msg
 * @property string $mail_attribute
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 */
class MailSettings extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mail_key', 'mail_subject', 'mail_msg'], 'required'],
            [['mail_msg', 'mail_attribute'], 'string'],
            [['created_time', 'created_by', 'modified_time', 'modified_by'], 'integer'],
            [['mail_key', 'mail_title', 'mail_subject'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mail_key' => 'Mail Key',
            'mail_title' => 'Mail Title',
            'mail_subject' => 'Mail Subject',
            'mail_msg' => 'Mail Msg',
            'mail_attribute' => 'Mail Attribute',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
        ];
    }
}
