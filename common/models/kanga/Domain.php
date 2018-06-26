<?php

namespace common\models\kanga;

use Yii;

/**
 * This is the model class for table "domain".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $modified_time
 * @property string $name
 */
class Domain extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'domain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_time', 'created_by', 'modified_by', 'modified_time'], 'integer'],
            [['name'], 'required'],
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
            'status' => 'Status',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'modified_time' => 'Modified Time',
            'name' => 'Name',
        ];
    }
}
