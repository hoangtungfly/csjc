<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "system_page".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property integer $status
 * @property string $link
 * @property string $module
 * @property string $controller
 * @property string $action
 */
class SystemPage extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description', 'content'], 'string'],
            [['created_time', 'created_by', 'modified_time', 'modified_by', 'status'], 'integer'],
            [['title'], 'string', 'max' => 200],
            [['meta_title', 'meta_keyword', 'meta_description', 'link', 'module', 'controller', 'action'], 'string', 'max' => 255]
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
            'description' => 'Description',
            'content' => 'Content',
            'meta_title' => 'Meta Title',
            'meta_keyword' => 'Meta Keyword',
            'meta_description' => 'Meta Description',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'status' => 'Status',
            'link' => 'Link',
            'module' => 'Module',
            'controller' => 'Controller',
            'action' => 'Action',
        ];
    }
}
