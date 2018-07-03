<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_video".
 *
 * @property string $id
 * @property string $name
 * @property string $link
 * @property string $type
 * @property string $base_url
 * @property string $table_name
 * @property integer $created_by
 * @property integer $created_time
 * @property integer $modified_by
 * @property integer $modified_time
 * @property integer $status
 */
class SettingsVideo extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'created_time', 'modified_by', 'modified_time', 'status'], 'integer'],
            [['name', 'link', 'base_url'], 'string', 'max' => 255],
            [['type', 'table_name'], 'string', 'max' => 100]
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
            'link' => 'Link',
            'type' => 'Type',
            'base_url' => 'Base Url',
            'table_name' => 'Table Name',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'modified_by' => 'Modified By',
            'modified_time' => 'Modified Time',
            'status' => 'Status',
        ];
    }
}
