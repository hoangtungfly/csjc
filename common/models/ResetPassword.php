<?php

namespace common\models;

use common\core\enums\EmailsettingEnum;
use common\core\enums\SystemTokenEnum;
use common\models\settings\SystemSettingSearch;
use common\models\system\SystemTokenSearch;
use common\models\user\UserSearch;
use common\utilities\UltilityEmail;
use common\utilities\UtilityUrl;
use Yii;
use yii\base\Model;

/**
 * Register form
 */
class ResetPassword extends Model {

    public $email;
    public $password;
    public $passwordConfirm;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            // username and password are both required
            [['password', 'passwordConfirm',], 'required','on' => 'reset'],
            [['email'], 'required', 'on' => 'forgot'],
            [['email'], 'email', 'on' => 'forgot'],
            // rememberMe must be a boolean value
            [['password'],'string','min'=>6,'message'=>'Password must contain at least 6 characters ', 'on' => 'forgot'],
            [['email'], 'exist', 'targetClass' => 'common\models\user\UserSearch', 'on' => 'forgot', 'message' => 'This email does not exist. Please try another email address.'],
            // password is validated by validatePassword()
            ['passwordConfirm', 'compare', 'compareAttribute' => 'password', 'on' => 'forgot', 'message' => 'Password is not matching. Please enter password again.'],
        ];
    }
     /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => 'New Password',
            'passwordConfirm' => 'Retype New Password',
        ];
    }
    
    /**
     * Usage: this function is to create a token then send it along the reset password email.
     * @return boolean
     */
    public function createTokenToResetPassword() {
        $this->scenario = 'forgot';
        $this->validate();
        if (!$this->getErrors('email')) {
            $user = UserSearch::findOne(['email' => $this->email]);            
            if ($user) {
                $customerName = $user->renderDisplayName();
                $connection = Yii::$app->db;
                $transaction = $connection->beginTransaction();
                $token = new SystemTokenSearch();
                $token->object_id = $user->id;
                $token->object_type = SystemTokenEnum::TOKEN_RESET_PW_USER;
                if ($token->insertToken()) {
                    $mail = new UltilityEmail();
                    $link = HTTP_HOST . UtilityUrl::createUrl('/'.WEBNAME.'/user/resetpassword', [
                        'token' => $token->token_key
                    ]);
                    $mail->getTemplateText(EmailsettingEnum::FORGOT_PASSWORD, [
                        'link' => $link,
                        'member_name' => $customerName ,
                        'phone_number' => SystemSettingSearch::getValue('sys_phone'),
                        'HOST_PUBLIC' => HOST_PUBLIC,
                    ]);
                    if ($mail->send($user->email)) {
                        $transaction->commit();
                    } else {
                        $transaction->rollBack();
                    }
                    return true;
                }
            }
        }
        return false;
    }
}
