<?php

namespace common\models\contact;

use common\models\admin\SettingsMessageSearch;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $title
 * @property string $content
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 */
class ContactSearch extends Contact {
    public function rules() {
        $result = parent::rules();
        $result[] = [['captcha'], 'captcha', 'on' => 'frontend','message' => SettingsMessageSearch::t('form','captcha_captcha','Mã bảo mật không chính xác.')];
        $result[] = [['captcha'], 'required', 'on' => 'frontend','message' => SettingsMessageSearch::t('form','captcha_required','Mã bảo mật không được để rỗng.')];
        $result[] = [['phone','email'], 'required', 'on' => 'frontend','message' => SettingsMessageSearch::t('form','required','{attribute} không được để rỗng.')];
        $result[] = [['email'], 'email', 'on' => 'frontend','message' => SettingsMessageSearch::t('form','email_required','Email không chính xác.')];
        $result[] = [['phone'], 'match', 'pattern' => '/^([0-9]{2,4}|\([0-9]{2,4}\))(| )[0-9]{5,18}$/', 'message' => SettingsMessageSearch::t('contact', 'validate_phone','Please enter correct contact phone'), 'on' => 'frontend'];
        return $result;
    }
}
