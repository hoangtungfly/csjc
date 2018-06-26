<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $count
 * @property integer $created_time
 * @property integer $created_by
 */
class Tags extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias'], 'required'],
            [['count', 'created_time', 'created_by'], 'integer'],
            [['name', 'alias'], 'string', 'max' => 255]
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
            'alias' => 'Alias',
            'count' => 'Count',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
        ];
    }
}
