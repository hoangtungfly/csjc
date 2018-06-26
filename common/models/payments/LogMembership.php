<?php

namespace common\models\payments;

use Yii;
use common\core\dbConnection\GlobalActiveRecord;
use common\models\payments\UsrCards;

/**
 * This is the model class for table "log_membership".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $employer_id
 * @property string $action_type
 * @property string $subscription_id
 * @property integer $created_time
 * @property double $price
 */
class LogMembership extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_membership';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'employer_id', 'created_time'], 'integer'],
            [['price'], 'number'],
            [['action_type', 'subscription_id'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'employer_id' => 'Employer ID',
            'action_type' => 'Action Type',
            'subscription_id' => 'Subscription ID',
            'created_time' => 'Created Time',
            'price' => 'Price',
        ];
    }
    
    /**
     * 
     * @param array $dataLog
     */
    public static function insertLog($dataLog) {
        if(count($dataLog)) {
            $model = new LogMembership();
            $model->setAttributes($dataLog);
            $model->save(false);
        }
    }
}
