<?php
namespace common\models\user;
use common\models\user\LoginForm;
use common\core\userIdentity\UserIdentity;
use Yii;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginFormAdmin
 *
 * @author hanguyenhai
 */
class LoginFormAdmin extends LoginForm{
    private $_user = false;
    
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getAdmin();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
    
    public function getAdmin(){
        if($this->_user=== false){
            $this->_user= UserIdentity::findAdminByEmail($this->username);
            if($this->_user && !trim($this->_user->auth_key)){
                $this->_user->generateAuthKey();
                $this->_user->save(false);
            }
        }
        return $this->_user;
    }
    
    public function loginAdmin(){
        if($this->validate()){
            $get_admin= $this->getAdmin();
            $login= Yii::$app->user->login($get_admin, $this->rememberMe ? 3600 * 24 * 30 : 0);
            if($login){
                return true;
            }
            return false;
        }
        else
            return false;
    }
}
