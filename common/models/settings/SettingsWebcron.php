<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "settings_webcron".
 *
 * @property integer $id
 * @property string $name
 * @property string $directory
 * @property string $link
 * @property string $domain
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $show_log
 * @property string $content_file
 * @property integer $type
 * @property string $layout
 * @property string $rewrite
 */
class SettingsWebcron extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_webcron';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['link', 'show_log', 'content_file', 'layout', 'rewrite'], 'string'],
            [['created_time', 'modified_time', 'created_by', 'modified_by', 'type'], 'integer'],
            [['name', 'directory', 'domain'], 'string', 'max' => 255]
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
            'directory' => 'Directory',
            'link' => 'Link',
            'domain' => 'Domain',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'show_log' => 'Show Log',
            'content_file' => 'Content File',
            'type' => 'Type',
            'layout' => 'Layout',
            'rewrite' => 'Rewrite',
        ];
    }
}
