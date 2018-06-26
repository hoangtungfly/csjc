<?php

namespace common\models\web;

use Yii;

/**
 * This is the model class for table "settings_web_template".
 *
 * @property integer $id
 * @property string $name
 * @property string $content_html
 * @property integer $web_id
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property string $component_id
 * @property integer $type
 * @property string $content_js
 * @property string $content_php
 * @property string $findtag
 */
class SettingsWebTemplate extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_web_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['content_html', 'content_js', 'content_php'], 'string'],
            [['web_id', 'created_time', 'created_by', 'modified_time', 'modified_by', 'type'], 'integer'],
            [['name', 'component_id', 'findtag'], 'string', 'max' => 255]
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
            'content_html' => 'Content Html',
            'web_id' => 'Web ID',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'component_id' => 'Component ID',
            'type' => 'Type',
            'content_js' => 'Content Js',
            'content_php' => 'Content Php',
            'findtag' => 'Findtag',
        ];
    }
}
