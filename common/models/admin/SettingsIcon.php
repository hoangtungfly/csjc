<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_icon".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $created_time
 * @property integer $modified_time
 */
class SettingsIcon extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_icon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'modified_by', 'created_time', 'modified_time'], 'integer'],
            [['name'], 'string', 'max' => 50]
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
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
        ];
    }
}
