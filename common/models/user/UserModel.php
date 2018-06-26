<?php

namespace common\models\user;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $email
 * @property string $password
 * @property integer $gender
 * @property integer $status
 * @property string $avartar
 * @property string $access_token
 * @property string $auth_key
 * @property integer $app_type
 * @property string $birthday
 * @property integer $created_time
 * @property integer $modified_time
 * @property string $display_name
 * @property string $phone
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $firstname
 * @property string $lastname
 * @property integer $plan_id
 * @property string $address
 */
class UserModel extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['gender', 'status', 'app_type', 'created_time', 'modified_time', 'created_by', 'modified_by', 'plan_id'], 'integer'],
            [['email', 'password'], 'string', 'max' => 100],
            [['avartar', 'access_token', 'auth_key', 'firstname', 'lastname', 'address'], 'string', 'max' => 255],
            [['birthday'], 'string', 'max' => 20],
            [['display_name'], 'string', 'max' => 500],
            [['phone'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'gender' => 'Gender',
            'status' => 'Status',
            'avartar' => 'Avartar',
            'access_token' => 'Access Token',
            'auth_key' => 'Auth Key',
            'app_type' => 'App Type',
            'birthday' => 'Birthday',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'display_name' => 'Display Name',
            'phone' => 'Phone',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'plan_id' => 'Plan ID',
            'address' => 'Address',
        ];
    }
}
