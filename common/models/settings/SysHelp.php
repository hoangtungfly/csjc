<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "sys_help".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modidfied_by
 */
class SysHelp extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_help';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['created_time', 'created_by', 'modified_time', 'modidfied_by'], 'integer'],
            [['name'], 'string', 'max' => 255]
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
            'content' => 'Content',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modidfied_by' => 'Modidfied By',
        ];
    }
}
