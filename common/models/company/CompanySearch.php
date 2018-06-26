<?php

namespace common\models\company;

use common\models\admin\SettingsMessageSearch;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property integer $company_category_id
 * @property integer $company_size_id
 * @property string $lang
 * @property integer $pbx_id
 * @property string $information_name
 * @property string $information_email
 * @property string $information_mobile
 * @property string $infomation_phone
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property integer $status
 */
class CompanySearch extends Company {
    public $agree;
    public function rules() {
        $result = parent::rules();
//        $result[] = [['captcha'], 'captcha'];
//        $result[] = [['captcha'], 'required'];
        $result[] = [['agree'], 'agree'];
        $result[] = [['information_email'], 'email','message' => SettingsMessageSearch::t('form','email_required','Email không chính xác.')];
        $result[] = [['information_mobile'], 'match', 'pattern' => '/^([0-9]{2,4}|\([0-9]{2,4}\))(| )[0-9]{5,18}$/', 'message' => SettingsMessageSearch::t('company', 'validate_phone','Please enter correct mobile')];
        $result[] = [['information_phone'], 'match', 'pattern' => '/^([0-9]{2,4}|\([0-9]{2,4}\))(| )[0-9]{5,18}$/', 'message' => SettingsMessageSearch::t('company', 'validate_phone','Please enter correct phone')];
        return $result;
    }
    
    public function agree($attribute) {
        $value = trim($this->$attribute);
        if(!$value) {
            $this->addError($attribute, SettingsMessageSearch::t('company', 'validate_agree','Làm ơn nhấn đồng ý điều khoản của chúng tôi'));
        }
    }
}
