<?php

namespace common\models\web;

use Yii;

/**
 * This is the model class for table "settings_web_template_component".
 *
 * @property integer $id
 * @property integer $template_id
 * @property integer $component_id
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 */
class SettingsWebTemplateComponent extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_web_template_component';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_id', 'component_id'], 'required'],
            [['template_id', 'component_id', 'created_time', 'created_by', 'modified_time', 'modified_by'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_id' => 'Template ID',
            'component_id' => 'Component ID',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
        ];
    }
}
