<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_cron".
 *
 * @property integer $id
 * @property string $name
 * @property string $table_name
 * @property string $attr_in
 * @property string $attr_out
 * @property string $link_cron_out
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $status
 * @property string $tag_out
 * @property integer $attr_id
 * @property string $tag_parent_out
 * @property string $page_format
 * @property string $class_name
 * @property string $content_log
 * @property string $condition_save
 * @property integer $cron
 */
class SettingsCron extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_cron';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_name'], 'required'],
            [['attr_in', 'attr_out', 'link_cron_out', 'content_log', 'condition_save'], 'string'],
            [['created_time', 'modified_time', 'created_by', 'modified_by', 'status', 'attr_id', 'cron'], 'integer'],
            [['name', 'tag_out', 'tag_parent_out', 'page_format', 'class_name'], 'string', 'max' => 255],
            [['table_name'], 'string', 'max' => 50]
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
            'table_name' => 'Table Name',
            'attr_in' => 'Attr In',
            'attr_out' => 'Attr Out',
            'link_cron_out' => 'Link Cron Out',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'status' => 'Status',
            'tag_out' => 'Tag Out',
            'attr_id' => 'Attr ID',
            'tag_parent_out' => 'Tag Parent Out',
            'page_format' => 'Page Format',
            'class_name' => 'Class Name',
            'content_log' => 'Content Log',
            'condition_save' => 'Condition Save',
            'cron' => 'Cron',
        ];
    }
}
