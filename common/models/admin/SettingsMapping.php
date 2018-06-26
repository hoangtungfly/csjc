<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_mapping".
 *
 * @property integer $mapping_id
 * @property string $mapping_name
 * @property string $select_id
 * @property string $select_name
 * @property string $table_name
 * @property string $where
 * @property string $created_time
 * @property string $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $status
 * @property string $odr
 * @property string $cal_func
 * @property string $group_by
 * @property string $class
 */
class SettingsMapping extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_mapping';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_name', 'where'], 'string'],
            [['created_time', 'modified_time', 'created_by', 'modified_by', 'status'], 'integer'],
            [['mapping_name', 'select_id', 'select_name', 'odr', 'cal_func', 'group_by', 'class'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mapping_id' => 'Mapping ID',
            'mapping_name' => 'Mapping Name',
            'select_id' => 'Select ID',
            'select_name' => 'Select Name',
            'table_name' => 'Table Name',
            'where' => 'Where',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'status' => 'Status',
            'odr' => 'Odr',
            'cal_func' => 'Cal Func',
            'group_by' => 'Group By',
            'class' => 'Class',
        ];
    }
}
