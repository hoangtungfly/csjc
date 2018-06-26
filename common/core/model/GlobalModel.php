<?php

/**
 * @author 457 Team
 * 
 * Extend model core
 */

namespace common\core\model;

use common\core\enums\StatusEnum;
use common\models\admin\SettingsMessageSearch;
use Yii;

class GlobalModel extends \yii\base\Model {

    /**
     * @inheritdoc
     * @param type $values
     * @param type $safeOnly
     * @return GlobalModel
     */
    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
        $this->trimAttributes();
        return $this;
    }

    /**
     * trim all attribues
     * @return GlobalModel
     */
    public function trimAttributes() {
        foreach ($this->attributes as $attr => $value) {
            if (is_string($value)) {
                $this->$attr = trim($value);
            }
        }
        return $this;
    }

    /**
     * @author tuna<tunguyeanh@orenj.com>
     * conver array errors to string
     * 
     * @return string
     */
    public function toStringErrors($has = ', ', $errors = array()) {
        $result = array();
        $errors = $errors ? $errors : $this->getErrors();
        if ($errors) {
            foreach ($errors as $item) {
                if (!is_array($item)) {
                    $result[] = $item;
                } else if ($e = $this->toStringErrors($has, $item)) {
                    $result[] = $e;
                }
            }
        }
        if ($result) {
            return implode($has, $result);
        }
        return null;
    }
    /**
     * add rule phone 
     * @param type $attribute
     * @param type $param
     */
    public function rulePhone($attribute) {
        $pat = '/^[\(+]?([0-9]{1,3})\)?[-. ]?([0-9]{1,3})\)?[-. ]?([0-9]{3,4})[-. ]?([0-9]{0,4})[-. ]?([0-9]{0,4})$/';
        if (!preg_match($pat, $this->$attribute)) {
            $this->addError($attribute, Yii::t('errorsProfile', 'mess_phone_error').'. Ex: 3 9320 5979');
        }
    }
    
    public function setAttr($dataPost) {
        $array = explode('\\',get_class($this));
        $class = $array[count($array) - 1];
        if(count($dataPost)) {
            if(isset($dataPost[$class])) {
                $dataPost = $dataPost[$class];
            }
            foreach($dataPost as $key => $value) {
                if(is_array($value)) {
                    $this->$key = implode(',', $value);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }
    public function agreeFunction($attribute) {
        if($this->$attribute != StatusEnum::STATUS_ACTIVED) {
            $this->addError($attribute, SettingsMessageSearch::t('errorForm',$attribute.'_error_title','Please agree to the terms of service'));
        }
    }
    
    public function loadAll($data) {
        $class = className(get_class($this));
        if (isset($data[$class])) {
            $dt = $data[$class];
            foreach ($dt as $key => $value) {
                $this->$key = $value;
            }
//            $rules = $this->rules();
//            foreach ($rules as $rule) {
//                if ($rule[1] == 'integer') {
//                    foreach ($rule[0] as $value) {
//                        if (isset($dt[$value])) {
//                            $this->{$value} = (int) $this->{$value};
//                        }
//                    }
//                    break;
//                }
//            }
            return true;
        }
        return false;
    }
}
