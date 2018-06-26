<?php

namespace common\models\user;

use common\core\enums\UserEnum;
use common\core\userIdentity\UserIdentity;
use common\utilities\UtilityArray;

/**
 * UserSearch represents the model behind the search form about `common\models\user\User`.
 */
class UserSearch extends UserModel {

    public $new_password;
    public $confirm_password;
    public $occupation;

    public function beforeSave($insert) {
        if (strlen($this->password) != 32) {
            $userIndentity = new UserIdentity();
            $this->password = $userIndentity->encrypPassword($this->password);
        }
        $this->display_name = trim($this->firstname.' '.$this->lastname);
        return parent::beforeSave($insert);
    }
    
    public function renderDisplayName() {
        return trim($this->firstname.' '.$this->lastname);
    }

    public static function getUserLogin() {
        $model = self::findOne(user()->id);
        if (!$model) {
            $model = new UserSearch();
        }
        return $model;
    }

}
