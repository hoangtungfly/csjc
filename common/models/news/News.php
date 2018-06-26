<?php

namespace common\models\news;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $content
 * @property string $category_id
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $status
 * @property integer $hot
 * @property string $image
 * @property string $alias
 * @property string $tags
 * @property integer $count
 * @property string $cron_id
 * @property integer $category_id1
 * @property integer $category_id2
 */
class News extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id'], 'required'],
            [['description', 'content'], 'string'],
            [['created_by', 'modified_by', 'created_time', 'modified_time', 'status', 'hot', 'count', 'category_id1', 'category_id2'], 'integer'],
            [['name', 'category_id', 'meta_title', 'meta_keyword', 'meta_description', 'image', 'alias', 'tags', 'cron_id'], 'string', 'max' => 255]
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
            'description' => 'Description',
            'content' => 'Content',
            'category_id' => 'Category ID',
            'meta_title' => 'Meta Title',
            'meta_keyword' => 'Meta Keyword',
            'meta_description' => 'Meta Description',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'status' => 'Status',
            'hot' => 'Hot',
            'image' => 'Image',
            'alias' => 'Alias',
            'tags' => 'Tags',
            'count' => 'Count',
            'cron_id' => 'Cron ID',
            'category_id1' => 'Category Id1',
            'category_id2' => 'Category Id2',
        ];
    }
}
