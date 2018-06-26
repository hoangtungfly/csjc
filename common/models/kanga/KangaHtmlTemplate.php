<?php

namespace common\models\kanga;

use Yii;

/**
 * This is the model class for table "kanga_html_template".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property integer $modified_time
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $status
 */
class KangaHtmlTemplate extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kanga_html_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content'], 'required'],
            [['content'], 'string'],
            [['modified_time', 'created_time', 'created_by', 'modified_by', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255]
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
            'content' => 'Content',
            'modified_time' => 'Modified Time',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'status' => 'Status',
        ];
    }
}
