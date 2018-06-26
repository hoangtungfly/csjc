<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "subscriber".
 *
 * @property string $id
 * @property string $subscriber_email
 */
class Subscriber extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscriber';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscriber_email'], 'string', 'max' => 255],
            [['subscriber_email'], 'email'],
            [['subscriber_email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subscriber_email' => 'Subscriber Email',
        ];
    }
}
