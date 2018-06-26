<?php

namespace common\models\booking;

use Yii;

/**
 * This is the model class for table "booking".
 *
 * @property integer $id
 * @property integer $people
 * @property integer $meal_id
 * @property integer $time
 * @property integer $customer_id
 * @property string $request
 * @property integer $process_status
 * @property string $booking_code
 * @property integer $created_by
 * @property integer $created_time
 * @property integer $modified_by
 * @property integer $modified_time
 *
 * @property TblMeal $meal
 * @property TblCustomers $customer
 */
class Booking extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'booking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['people', 'meal_id', 'time', 'customer_id', 'process_status', 'created_by', 'created_time', 'modified_by', 'modified_time'], 'integer'],
            [['request', 'booking_code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'people' => 'People',
            'meal_id' => 'Meal ID',
            'time' => 'Time',
            'customer_id' => 'Customer ID',
            'request' => 'Request',
            'process_status' => 'Process Status',
            'booking_code' => 'Booking Code',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'modified_by' => 'Modified By',
            'modified_time' => 'Modified Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMeal()
    {
        return $this->hasOne(TblMeal::className(), ['id' => 'meal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(TblCustomers::className(), ['id' => 'customer_id']);
    }
}
