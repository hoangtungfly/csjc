<?php

namespace common\models\user;

use common\core\model\GlobalModel;
use common\core\userIdentity\UserIdentity;
use Yii;
use yii\web\User;

/**
 * Login form
 */
class LoginForm extends GlobalModel {

    const REMEMBER_COOKIE_NAME = '_rmbme';
    
    public $username;
    public $password;
    public $rememberMe = 0;
    private $_user = false;
    
    
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            [['username'], 'email', 'message' => 'Please re-enter your email address.'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels() {
        return [
            'username' => 'Email',
            'rememberMe' => 'Remember Me'
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Please re-enter your email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            $login = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            if ($login) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser() {
        if ($this->_user === false) {
            $this->_user = UserIdentity::findByEmailUser($this->username);
            # add auth_key for user
            if ($this->_user && !trim($this->_user->auth_key)) {
                $this->_user->generateAuthKey();
                $this->_user->save(false);
            }
        }
        return $this->_user;
    }

    /**
     * @phongph
     * encryping Password
     * @param string $password
     * @param string $salt 
     * @return string
     */
    public function encrypPassword($password, $salt = '') {
        $n = $salt . md5($password);
        return md5($n);
    }

}
