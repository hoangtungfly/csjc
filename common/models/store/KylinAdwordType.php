<?php

namespace common\models\store;

use Yii;

/**
 * This is the model class for table "kylin_adword_type".
 *
 * @property integer $id
 * @property string $v_fact_table
 * @property string $v_fact_table_click
 * @property string $v_fact_table_goal
 * @property string $v_fact_table_conversion
 * @property string $v_dimensions_in
 * @property string $v_lookup_inner_table
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property integer $status
 * @property integer $search_engine
 * @property string $v_dimensions_on
 * @property string $v_id
 * @property integer $type_by
 * @property integer $type
 */
class KylinAdwordType extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kylin_adword_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['v_fact_table', 'type'], 'required'],
            [['v_lookup_inner_table'], 'string'],
            [['created_time', 'created_by', 'modified_time', 'modified_by', 'status', 'search_engine', 'type_by', 'type'], 'integer'],
            [['v_fact_table', 'v_fact_table_click', 'v_fact_table_goal', 'v_fact_table_conversion', 'v_dimensions_in', 'v_dimensions_on', 'v_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'v_fact_table' => 'V Fact Table',
            'v_fact_table_click' => 'V Fact Table Click',
            'v_fact_table_goal' => 'V Fact Table Goal',
            'v_fact_table_conversion' => 'V Fact Table Conversion',
            'v_dimensions_in' => 'V Dimensions In',
            'v_lookup_inner_table' => 'V Lookup Inner Table',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'status' => 'Status',
            'search_engine' => 'Search Engine',
            'v_dimensions_on' => 'V Dimensions On',
            'v_id' => 'V ID',
            'type_by' => 'Type By',
            'type' => 'Type',
        ];
    }
}
