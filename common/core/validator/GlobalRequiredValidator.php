<?php

namespace common\core\validator;

use yii\validators\RequiredValidator;

class GlobalRequiredValidator extends RequiredValidator {

    public $compareAttribute;
    public $compareValue;
    public $model;

    public function validateAttribute($model, $attribute) {
        $this->model = $model;
        $flag = true;
        if ($this->compareAttribute && $this->compareValue) {
            $flag = false;
            if(in_array($this->model->{$this->compareAttribute}, $this->compareValue)) {
                $flag = true;
            }
        }
        if($flag) {
            $value = $model->$attribute;
            if((!$this->requiredValue && $this->isEmpty(is_string($value) ? trim($value) : $value)) || ($this->requiredValue && $value != $this->requiredValue)) {
                $this->addError($model, $attribute, $this->message);
            }
        }
    }

}
