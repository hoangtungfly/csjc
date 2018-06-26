<?php

namespace common\core\userIdentity;

use common\core\enums\StatusEnum;
use common\models\user\UserSearch;
use Yii;
use yii\web\IdentityInterface;

/**
 * UserIdentity
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property string $password write-only password
 */
class UserIdentity extends UserSearch implements IdentityInterface {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => StatusEnum::STATUS_ACTIVED],
        ];
    }

    /**
     * @inheritdoc
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        $model = UserSearch::findOne($id);
        if ($model && $model->status == StatusEnum::STATUS_ACTIVED) {
            $m = new UserIdentity();
            $m->setAttributes($model->attributes, false);
            return $m;
        }
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($email) {
        $email = strtolower($email);
        return self::find()->where('email = :em AND status = :st AND app_type <> :app', [
                    ':em' => $email,
                    ':st' => StatusEnum::STATUS_ACTIVED,
                    ':app' => APP_TYPE_ADMIN
                ])->one();
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmailUser($email) {
        $email = strtolower($email);
        return self::find()->where('email = :em AND status = :st AND app_type <> :app', [
                    ':em' => $email,
                    ':st' => StatusEnum::STATUS_ACTIVED,
                    ':app' => [APP_TYPE_USER,APP_TYPE_CUSTOMERS]
                ])->one();
    }
    
    public static function findAdminByEmail($email){
        $email= strtolower($email);
        return self::find()->where('email= :email AND status= :status', [
            ':email'=> $email,
            ':status'=> StatusEnum::STATUS_ACTIVED,
        ])->one();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->password === $this->encrypPassword($password);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
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

    public function getAuthKey() {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
        return $this->auth_key;
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        
    }

    public function getIsSuperAdmin() {
        return false;
    }
    public function setAdminId(){
        Yii::$app->session->set('admin_id', $this->user_id);
    }
    public function getAdminId(){
        return Yii::$app->session->get('admin_id');
    }
    
    public function isAdmin(){
        if($this->getAdminId())
            return true;
        else
            return false;
    }
}
