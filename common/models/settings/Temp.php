<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "temp".
 *
 * @property integer $id
 * @property string $array
 * @property string $arrayjson
 * @property string $json
 * @property string $text
 * @property string $password
 * @property integer $checkbox
 */
class Temp extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'temp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['checkbox'], 'integer'],
            [['array', 'arrayjson', 'json', 'text', 'password'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'array' => 'Array',
            'arrayjson' => 'Arrayjson',
            'json' => 'Json',
            'text' => 'Text',
            'password' => 'Password',
            'checkbox' => 'Checkbox',
        ];
    }
}
