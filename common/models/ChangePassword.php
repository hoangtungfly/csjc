<?php

namespace common\models;
use Yii;

/**
 * Register form
 */
class ChangePassword extends \yii\base\Model {
    
    public $oldpass;
    public $newpass;
    public $newpassconfirm;
   /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['oldpass', 'newpass', 'newpassconfirm'], 'required'],
            [['oldpass'], 'validateOldPassword'],
            [['newpass'],'string','min'=>6,'message'=>'Password must contain at least 6 characters '],
            ['newpassconfirm', 'compare', 'compareAttribute' => 'newpass', 'message' => 'Password and confirmed password are not matched. Please try again.'],
        ];
    }

    public function attributeLabels() {
        return ['oldpass' => 'Old password',
            'newpass' => 'New password',
            'newpassconfirm' => 'Confirm password'
        ];
    }
    
     public function validateOldPassword($attribute) {
        if ($attribute) {
            $value = $this->$attribute;
            $encryptedPass = User::encrypPassword($value);
            if ($encryptedPass != Yii::$app->user->identity->password) {
                $this->addError($attribute, 'Please re-enter the correct old password.');
            }
        }
    }
}
