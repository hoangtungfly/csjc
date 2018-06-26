<?php

namespace common\models\plan;

use Yii;

/**
 * This is the model class for table "user_plan".
 *
 * @property string $user_id
 * @property integer $plan_id
 * @property integer $created_time
 * @property integer $end_time
 * @property integer $status
 * @property integer $payment_status
 */
class UserPlan extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'plan_id'], 'required'],
            [['user_id', 'plan_id', 'created_time', 'end_time', 'status', 'payment_status'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'plan_id' => 'Plan ID',
            'created_time' => 'Created Time',
            'end_time' => 'End Time',
            'status' => 'Status',
            'payment_status' => 'Payment Status',
        ];
    }
}
