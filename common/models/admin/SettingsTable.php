<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_table".
 *
 * @property integer $table_id
 * @property string $name
 * @property string $table_name
 * @property string $created_time
 * @property string $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $status
 * @property string $condition
 * @property string $orderby
 * @property string $attrsearch
 * @property string $attrarange
 * @property integer $checkview
 * @property integer $checksearch
 * @property string $attrchoice
 * @property string $join
 * @property string $excel
 * @property integer $beginimport
 * @property integer $columncheck
 * @property integer $columnaction
 * @property integer $columnid
 * @property string $class
 * @property string $groupby
 */
class SettingsTable extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_table';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_time', 'modified_time', 'created_by', 'modified_by', 'status', 'checkview', 'checksearch', 'beginimport', 'columncheck', 'columnaction', 'columnid'], 'integer'],
            [['attrsearch', 'attrarange', 'attrchoice', 'excel'], 'string'],
            [['name', 'condition', 'orderby', 'class', 'groupby'], 'string', 'max' => 255],
            [['table_name'], 'string', 'max' => 30],
            [['join'], 'string', 'max' => 400]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'table_id' => 'Table ID',
            'name' => 'Name',
            'table_name' => 'Table Name',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'status' => 'Status',
            'condition' => 'Condition',
            'orderby' => 'Orderby',
            'attrsearch' => 'Attrsearch',
            'attrarange' => 'Attrarange',
            'checkview' => 'Checkview',
            'checksearch' => 'Checksearch',
            'attrchoice' => 'Attrchoice',
            'join' => 'Join',
            'excel' => 'Excel',
            'beginimport' => 'Beginimport',
            'columncheck' => 'Columncheck',
            'columnaction' => 'Columnaction',
            'columnid' => 'Columnid',
            'class' => 'Class',
            'groupby' => 'Groupby',
        ];
    }
}
