<?php

namespace common\models\store;

use Yii;

/**
 * This is the model class for table "store_to_apache_kylin".
 *
 * @property integer $id
 * @property integer $search_engine_type
 * @property integer $report_type
 * @property integer $convtrack
 * @property string $store_name
 * @property string $template
 * @property string $template_two
 * @property string $template_three
 * @property string $segment
 * @property integer $compare
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $other
 * @property string $v_saletype
 * @property string $condition
 * @property string $template_parent
 */
class StoreToApacheKylin extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'store_to_apache_kylin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['search_engine_type', 'report_type', 'convtrack', 'compare', 'created_time', 'modified_time', 'created_by', 'modified_by'], 'integer'],
            [['store_name'], 'required'],
            [['template', 'template_two', 'template_three', 'template_parent'], 'string'],
            [['store_name', 'segment', 'other', 'v_saletype', 'condition'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'search_engine_type' => 'Search Engine Type',
            'report_type' => 'Report Type',
            'convtrack' => 'Convtrack',
            'store_name' => 'Store Name',
            'template' => 'Template',
            'template_two' => 'Template Two',
            'template_three' => 'Template Three',
            'segment' => 'Segment',
            'compare' => 'Compare',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'other' => 'Other',
            'v_saletype' => 'V Saletype',
            'condition' => 'Condition',
            'template_parent' => 'Template Parent',
        ];
    }
}
