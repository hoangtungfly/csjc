<?php
namespace common\models\user;

use common\models\user\User;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $fullname;
    public $email;
    public $password;
    public $confirm_password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['fullname', 'filter', 'filter' => 'trim'],
            ['fullname', 'required'],
            
            ['fullname', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This username has already been taken.'],
            ['fullname', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\user\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6,'message' => 'password must be than 6 charactor'],
            ['confirm_password','required'],
           
            ['confirm_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }
 public function attributeLabels()
    {
        return [
            
            'fullname' => '',
            'email' => '',
            'password' => '',
            'confirm_password'=>''
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->firstname = $this->fullname;
           
            $user->fullname = $this->fullname;
            $user->email = $this->email;
            $user->password=$user::encrypPassword($this->password,'');
            $user->alias_author=$this->fullname;
            
       
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
