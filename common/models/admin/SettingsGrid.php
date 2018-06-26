<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_grid".
 *
 * @property integer $grid_id
 * @property string $attribute
 * @property string $label
 * @property string $headeroptions
 * @property string $value
 * @property string $filter
 * @property integer $enablesorting
 * @property integer $table_id
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $mapping_id
 * @property string $format
 * @property string $link
 * @property integer $choice
 * @property integer $status
 * @property integer $odr
 * @property string $template
 * @property string $countsql
 * @property integer $update
 * @property string $contentoptions
 * @property string $sortlinkoptions
 * @property string $alias_attribute
 * @property string $link_updatefast
 * @property string $check_status
 */
class SettingsGrid extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_grid';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enablesorting', 'table_id', 'created_time', 'modified_time', 'created_by', 'modified_by', 'mapping_id', 'choice', 'status', 'odr', 'update'], 'integer'],
            [['attribute', 'label', 'value', 'link', 'template', 'countsql', 'alias_attribute', 'link_updatefast', 'check_status'], 'string', 'max' => 255],
            [['headeroptions', 'contentoptions', 'sortlinkoptions'], 'string', 'max' => 500],
            [['filter', 'format'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'grid_id' => 'Grid ID',
            'attribute' => 'Attribute',
            'label' => 'Label',
            'headeroptions' => 'Headeroptions',
            'value' => 'Value',
            'filter' => 'Filter',
            'enablesorting' => 'Enablesorting',
            'table_id' => 'Table ID',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'mapping_id' => 'Mapping ID',
            'format' => 'Format',
            'link' => 'Link',
            'choice' => 'Choice',
            'status' => 'Status',
            'odr' => 'Odr',
            'template' => 'Template',
            'countsql' => 'Countsql',
            'update' => 'Update',
            'contentoptions' => 'Contentoptions',
            'sortlinkoptions' => 'Sortlinkoptions',
            'alias_attribute' => 'Alias Attribute',
            'link_updatefast' => 'Link Updatefast',
            'check_status' => 'Check Status',
        ];
    }
}
