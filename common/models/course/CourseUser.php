<?php

namespace common\models\course;

use Yii;

/**
 * This is the model class for table "course_user".
 *
 * @property integer $id
 * @property string $fullname
 * @property string $firstname
 * @property string $lastname
 * @property string $birthday
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property integer $status
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 */
class CourseUser extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fullname', 'birthday', 'phone', 'email', 'address'], 'required'],
            [['address'], 'string'],
            [['status', 'created_time', 'created_by', 'modified_time', 'modified_by'], 'integer'],
            [['fullname', 'firstname', 'lastname', 'email'], 'string', 'max' => 255],
            [['birthday', 'phone'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fullname' => 'Fullname',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'birthday' => 'Birthday',
            'phone' => 'Phone',
            'email' => 'Email',
            'address' => 'Address',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
        ];
    }
}
