<?php

namespace common\models\notification;

use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property string $name
 * @property integer $day
 * @property string $link
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $user_id
 * @property integer $read
 * @property string $content
 * @property string $type
 * @property integer $customer_id
 */
class Notification extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['day'], 'required'],
            [['day', 'created_time', 'created_by', 'user_id', 'read', 'customer_id'], 'integer'],
            [['content'], 'string'],
            [['name', 'link'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 20]
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
            'day' => 'Day',
            'link' => 'Link',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'user_id' => 'User ID',
            'read' => 'Read',
            'content' => 'Content',
            'type' => 'Type',
            'customer_id' => 'Customer ID',
        ];
    }
}
