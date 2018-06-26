<?php

namespace common\models\user;

use Yii;

/**
 * This is the model class for table "user_sendmail".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $type
 * @property integer $status
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property string $subject
 * @property string $content
 * @property string $day_send_email
 */
class UserSendmail extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_sendmail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'created_time', 'created_by', 'modified_time', 'modified_by'], 'integer'],
            [['subject', 'content', 'day_send_email'], 'string'],
            [['type','day_send_email'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'subject' => 'Subject',
            'content' => 'Content',
            'day_send_email' => 'Day Send Email',
        ];
    }
}
