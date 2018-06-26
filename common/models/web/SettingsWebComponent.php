<?php

namespace common\models\web;

use Yii;

/**
 * This is the model class for table "settings_web_component".
 *
 * @property integer $id
 * @property string $name
 * @property string $class
 * @property string $select
 * @property string $where
 * @property string $join
 * @property string $order
 * @property string $limit
 * @property string $offset
 * @property string $function_name
 * @property integer $static
 * @property integer $by_command
 * @property string $command
 * @property string $join_with
 * @property integer $join_width_or
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property string $argv
 * @property string $group_by
 * @property integer $all_one
 * @property integer $cache
 * @property string $array_helper_map
 * @property integer $status
 * @property string $select_str
 * @property integer $parent
 * @property string $fixe
 * @property string $flag_key_id
 * @property string $w_h
 */
class SettingsWebComponent extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_web_component';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class', 'function_name'], 'required'],
            [['where', 'join', 'command', 'argv', 'select_str'], 'string'],
            [['static', 'by_command', 'join_width_or', 'created_time', 'created_by', 'modified_time', 'modified_by', 'all_one', 'cache', 'status', 'parent'], 'integer'],
            [['name', 'class', 'select', 'order', 'offset', 'function_name', 'join_with', 'group_by', 'array_helper_map'], 'string', 'max' => 255],
            [['limit'], 'string', 'max' => 200],
            [['fixe', 'flag_key_id', 'w_h'], 'string', 'max' => 20]
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
            'class' => 'Class',
            'select' => 'Select',
            'where' => 'Where',
            'join' => 'Join',
            'order' => 'Order',
            'limit' => 'Limit',
            'offset' => 'Offset',
            'function_name' => 'Function Name',
            'static' => 'Static',
            'by_command' => 'By Command',
            'command' => 'Command',
            'join_with' => 'Join With',
            'join_width_or' => 'Join Width Or',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'argv' => 'Argv',
            'group_by' => 'Group By',
            'all_one' => 'All One',
            'cache' => 'Cache',
            'array_helper_map' => 'Array Helper Map',
            'status' => 'Status',
            'select_str' => 'Select Str',
            'parent' => 'Parent',
            'fixe' => 'Fixe',
            'flag_key_id' => 'Flag Key ID',
            'w_h' => 'W H',
        ];
    }
}
