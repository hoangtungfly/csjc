<?php

namespace common\models\customer;

use common\core\dbConnection\GlobalActiveRecord;
use common\models\libs\Cities;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "customers".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $phone
 * @property string $city_id
 * @property string $district
 * @property string $address
 * @property string $created_time
 * @property integer $state_id
 *
 * @property TblOrders[] $tblOrders
 */
class Customers extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_id', 'created_time', 'state_id'], 'integer'],
            [['firstname', 'lastname', 'email', 'phone', 'district'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'phone' => 'Phone',
            'city_id' => 'City ID',
            'district' => 'District',
            'address' => 'Address',
            'created_time' => 'Created Time',
            'state_id' => 'State ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getTblOrders()
    {
        return $this->hasMany(TblOrders::className(), ['customer_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCities()
    {
        return $this->hasOne(Cities::className(), ['city_id' => 'city_id']);
    }
}
