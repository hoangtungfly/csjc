<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $name
 * @property string $email
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property string $table_name
 * @property integer $did
 * @property integer $status
 */
class Comment extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'name', 'email'], 'required'],
            [['content'], 'string'],
            [['created_time', 'created_by', 'modified_time', 'modified_by', 'did', 'status'], 'integer'],
            [['title', 'name', 'email'], 'string', 'max' => 255],
            [['table_name'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'name' => 'Name',
            'email' => 'Email',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'table_name' => 'Table Name',
            'did' => 'Did',
            'status' => 'Status',
        ];
    }
}
