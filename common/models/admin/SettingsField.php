<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_field".
 *
 * @property integer $field_id
 * @property integer $form_id
 * @property string $field_name
 * @property integer $mapping_id
 * @property string $label
 * @property string $field_type
 * @property integer $required
 * @property string $field_options
 * @property string $cid
 * @property integer $table_id
 * @property string $created_time
 * @property integer $created_by
 * @property integer $status
 * @property string $js
 * @property integer $multi_add
 */
class SettingsField extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id', 'mapping_id', 'required', 'table_id', 'created_time', 'created_by', 'status', 'multi_add'], 'integer'],
            [['field_name', 'label', 'field_options'], 'required'],
            [['label', 'field_options'], 'string'],
            [['field_name'], 'string', 'max' => 50],
            [['field_type', 'cid'], 'string', 'max' => 20],
            [['js'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'field_id' => 'Field ID',
            'form_id' => 'Form ID',
            'field_name' => 'Field Name',
            'mapping_id' => 'Mapping ID',
            'label' => 'Label',
            'field_type' => 'Field Type',
            'required' => 'Required',
            'field_options' => 'Field Options',
            'cid' => 'Cid',
            'table_id' => 'Table ID',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'status' => 'Status',
            'js' => 'Js',
            'multi_add' => 'Multi Add',
        ];
    }
}
