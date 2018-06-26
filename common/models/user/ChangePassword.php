<?php

namespace common\models\user;

use Yii;

/**
 * Change password form
 */
class ChangePassword extends UserModel {

    public $oldpass;
    public $newpass;
    public $newpassconfirm;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['oldpass', 'newpass', 'newpassconfirm'], 'required', 'message' => Yii::t('errorsEa', 'required_field')],
            [['oldpass'], 'validateOldPassword'],
            ['newpass', 'rulePasswordStrength'],
            ['newpassconfirm', 'compare', 'compareAttribute' => 'newpass', 'message' => Yii::t('errorForm', 'password_not_match')],
        ];
    }

    public function attributeLabels() {
        return ['oldpass' => 'Old password',
            'newpass' => 'New password',
            'newpassconfirm' => 'Confirm password'
        ];
    }

    public function rulePasswordStrength($attribute, $params) {
        //password strong
        $pattern = '/^(?=.*(?=.*\d))(?=.*([a-z]|[A-Z])).{6,}$/';
        //password weak
        /* $pattern = '/^(?=.*[a-zA-Z0-9]).{5,}$/'; */
        $value = $this->$attribute;
        if (!preg_match($pattern, $value)) {
            $this->addError($attribute, Yii::t('errorForm', 'weak_password'));
        }
    }

    public function validateOldPassword($attribute) {
        if ($attribute) {
            $value = $this->$attribute;
            $encryptedPass = md5(md5($value));
            if ($encryptedPass != Yii::$app->user->identity->password) {
                $this->addError($attribute, Yii::t('errorForm', 'incorrect_old_password'));
            }
        }
    }

}
