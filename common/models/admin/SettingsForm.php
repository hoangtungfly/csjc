<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_form".
 *
 * @property integer $form_id
 * @property string $form_name
 * @property string $form_description
 * @property string $fields
 * @property integer $table_id
 * @property string $created_time
 * @property string $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $status
 * @property integer $hidden
 * @property integer $line
 * @property integer $odr
 * @property integer $multi_add
 */
class SettingsForm extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fields'], 'string'],
            [['table_id', 'created_time', 'modified_time', 'created_by', 'modified_by', 'status', 'hidden', 'line', 'odr', 'multi_add'], 'integer'],
            [['form_name'], 'string', 'max' => 255],
            [['form_description'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'form_id' => 'Form ID',
            'form_name' => 'Form Name',
            'form_description' => 'Form Description',
            'fields' => 'Fields',
            'table_id' => 'Table ID',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'status' => 'Status',
            'hidden' => 'Hidden',
            'line' => 'Line',
            'odr' => 'Odr',
            'multi_add' => 'Multi Add',
        ];
    }
}
