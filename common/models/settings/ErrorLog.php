<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "error_log".
 *
 * @property integer $error_id
 * @property string $link
 * @property string $content
 * @property integer $code
 * @property integer $created_time
 * @property integer $created_by
 * @property string $link_prev
 * @property string $message
 * @property string $error_line
 * @property string $error_ip
 * @property string $device
 */
class ErrorLog extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'error_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['code', 'created_time', 'created_by'], 'integer'],
            [['link', 'link_prev', 'error_line'], 'string', 'max' => 500],
            [['message'], 'string', 'max' => 255],
            [['error_ip', 'device'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'error_id' => 'Error ID',
            'link' => 'Link',
            'content' => 'Content',
            'code' => 'Code',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'link_prev' => 'Link Prev',
            'message' => 'Message',
            'error_line' => 'Error Line',
            'error_ip' => 'Error Ip',
            'device' => 'Device',
        ];
    }
}
