<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "sys_language".
 *
 * @property integer $language_id
 * @property string $language_key
 * @property string $language_vi
 * @property string $language_en
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property string $language_name
 */
class SysLanguage extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_language';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_key', 'language_name', 'language_en'], 'required'],
            [['created_time', 'created_by', 'modified_time', 'modified_by'], 'integer'],
            [['language_key', 'language_name'], 'string', 'max' => 255],
            [['language_vi', 'language_en'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language_id' => 'Language ID',
            'language_key' => 'Language Key',
            'language_vi' => 'Language Vi',
            'language_en' => 'Language En',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'language_name' => 'Language Name',
        ];
    }
}
