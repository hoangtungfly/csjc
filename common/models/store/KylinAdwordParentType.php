<?php

namespace common\models\store;

use Yii;

/**
 * This is the model class for table "kylin_adword_parent_type".
 *
 * @property integer $id
 * @property integer $type
 * @property string $v_id
 * @property string $v_fact_table
 * @property string $v_lookup_inner_table
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property integer $status
 * @property integer $search_engine
 */
class KylinAdwordParentType extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kylin_adword_parent_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'created_time', 'created_by', 'modified_time', 'modified_by', 'status', 'search_engine'], 'integer'],
            [['v_fact_table'], 'required'],
            [['v_lookup_inner_table'], 'string'],
            [['v_id', 'v_fact_table'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'v_id' => 'V ID',
            'v_fact_table' => 'V Fact Table',
            'v_lookup_inner_table' => 'V Lookup Inner Table',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'status' => 'Status',
            'search_engine' => 'Search Engine',
        ];
    }
}
