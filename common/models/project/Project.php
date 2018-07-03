<?php

namespace common\models\project;

use Yii;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $start_date
 * @property integer $end_date
 * @property string $range_date
 * @property double $estimation_budget
 * @property integer $user_id
 * @property string $description
 * @property string $content
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keyword
 * @property string $image
 * @property string $alias
 */
class Project extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_by', 'modified_by', 'created_time', 'modified_time', 'start_date', 'end_date', 'user_id'], 'integer'],
            [['estimation_budget'], 'number'],
            [['content'], 'string'],
            [['name', 'description'], 'string', 'max' => 255],
            [['range_date'], 'string', 'max' => 30],
            [['meta_title', 'meta_description', 'meta_keyword', 'image', 'alias'], 'string', 'max' => 100]
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
            'status' => 'Status',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'range_date' => 'Range Date',
            'estimation_budget' => 'Estimation Budget',
            'user_id' => 'User ID',
            'description' => 'Description',
            'content' => 'Content',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keyword' => 'Meta Keyword',
            'image' => 'Image',
            'alias' => 'Alias',
        ];
    }
}
