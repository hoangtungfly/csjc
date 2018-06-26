<?php

namespace common\models\settings;

use Yii;

/**
 * This is the model class for table "thank".
 *
 * @property integer $id
 * @property integer $comment_id
 * @property string $ip
 * @property integer $status
 * @property integer $created_time
 * @property integer $created_by
 */
class Thank extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'thank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'status', 'created_time', 'created_by'], 'integer'],
            [['ip'], 'required'],
            [['ip'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment_id' => 'Comment ID',
            'ip' => 'Ip',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
        ];
    }
}
