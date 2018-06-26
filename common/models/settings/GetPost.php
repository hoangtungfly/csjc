<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "get_post".
 *
 * @property integer $id
 * @property string $get_content
 * @property string $post_content
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 */
class GetPost extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'get_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['get_content', 'post_content'], 'string'],
            [['created_time', 'created_by', 'modified_time', 'modified_by'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'get_content' => 'Get Content',
            'post_content' => 'Post Content',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
        ];
    }
}
