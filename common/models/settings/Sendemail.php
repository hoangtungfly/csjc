<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "sendemail".
 *
 * @property integer $id
 * @property string $email
 * @property string $title
 * @property string $content
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property string $link
 */
class Sendemail extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sendemail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'title', 'content'], 'required'],
            [['email', ], 'email'],
            [['content'], 'string'],
            [['created_time', 'created_by', 'modified_time', 'modified_by'], 'integer'],
            [['title', 'link', 'email'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'title' => 'Title',
            'content' => 'Content',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'link' => 'Link',
        ];
    }
}
