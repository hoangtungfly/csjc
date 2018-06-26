<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_statistical".
 *
 * @property integer $id
 * @property string $name
 * @property string $sql
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $created_time
 * @property integer $modified_time
 * @property string $icon
 * @property string $link
 * @property integer $odr
 * @property integer $status
 */
class SettingsStatistical extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_statistical';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'modified_by', 'created_time', 'modified_time', 'odr', 'status'], 'integer'],
            [['name', 'sql', 'link'], 'string', 'max' => 255],
            [['icon'], 'string', 'max' => 50]
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
            'sql' => 'Sql',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'icon' => 'Icon',
            'link' => 'Link',
            'odr' => 'Odr',
            'status' => 'Status',
        ];
    }
}
