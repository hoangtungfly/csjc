<?php

namespace common\core\dbConnection;

use yii\base\DynamicModel;

class GlobalDynamicModel extends DynamicModel {
    
    
    public $active_rules;
    
    public function getActiveRules() {
        if(!$this->active_rules) {
            $rules = $this->rules();
            $this->active_rules = [];
            foreach($rules as $key => $rule) {
                if(!isset($rule['on']) || (isset($rule['on']) && $rule['on'] == $this->getScenario())) {
                    $this->active_rules[] = $rule;
                }
            }
        }
        return $this->active_rules;
    }
    
    public function getRuleByFunctionValidate($func_str) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(in_array($func_str,$rule)) {
                return $rule;
            }
        }
        return [];
    }
    
    public function isRequired($attribute) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(!is_array($rule[0])) {
                $rule[0] = [$rule[0]];
            }
            if(in_array('required',$rule) && in_array($attribute, $rule[0])) {
                return $rule;
            }
        }
        return false;
    }
    
    public function isPatternRequired($attribute) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(!is_array($rule[0])) {
                $rule[0] = [$rule[0]];
            }
            if(isset($rule['pattern']) && in_array($attribute, $rule[0])) {
                return $rule;
            }
        }
        return false;
    }
    
    public function isDateRequired($attribute) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(!is_array($rule[0])) {
                $rule[0] = [$rule[0]];
            }
            if(in_array('date',$rule) && in_array($attribute, $rule[0])) {
                return $rule;
            }
        }
        return false;
    }
    
    public function isDateFuture($attribute) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(!is_array($rule[0])) {
                $rule[0] = [$rule[0]];
            }
            if(in_array('validateDateFuture',$rule) && in_array($attribute, $rule[0])) {
                return $rule;
            }
        }
        return false;
    }
    
    public function isDatePast($attribute) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(!is_array($rule[0])) {
                $rule[0] = [$rule[0]];
            }
            if(in_array('validateDatePast',$rule) && in_array($attribute, $rule[0])) {
                return $rule;
            }
        }
        return false;
    }
    
    public function isMaxString($attribute) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(!is_array($rule[0])) {
                $rule[0] = [$rule[0]];
            }
            if(in_array('string',$rule) && in_array($attribute, $rule[0]) && isset($rule['max'])) {
                return $rule;
            }
        }
        return false;
    }
    
    public function isCompare($attribute) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(!is_array($rule[0])) {
                $rule[0] = [$rule[0]];
            }
            if(in_array('compare',$rule) && in_array($attribute, $rule[0])) {
                return $rule;
            }
        }
        return false;
    }
    
    public function isSelectMultiple($attribute) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(!is_array($rule[0])) {
                $rule[0] = [$rule[0]];
            }
            if(in_array('selectMultiple',$rule) && in_array($attribute, $rule[0])) {
                return $rule;
            }
        }
        return false;
    }
    
    public function isEmail($attribute) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(!is_array($rule[0])) {
                $rule[0] = [$rule[0]];
            }
            if(in_array('email',$rule) && in_array($attribute, $rule[0])) {
                return $rule;
            }
        }
        return false;
    }
    
    public function validateDateFuture($attribute) {
        $value = strtotime($this->$attribute);
        if ((int) $value < time()) {
            $rule = $this->getRuleByFunctionValidate('validateDateFuture');
            $this->addError($attribute, isset($rule['message']) ? $rule['message'] : 'Date cannot be earlier than today.');
        }
    }
    
    public function validateDatePast($attribute) {
        $value = strtotime($this->$attribute);
        if ((int) $value > time()) {
            $rule = $this->getRuleByFunctionValidate('validateDatePast');
            $this->addError($attribute, isset($rule['message']) ? $rule['message'] : 'Date cannot be later than today.');
        }
    }
    
     public function isCompare2($attribute) {
        $rules = $this->getActiveRules();
        foreach($rules as $key => $rule) {
            if(!is_array($rule[0])) {
                $rule[0] = [$rule[0]];
            }
            if(in_array('compare2',$rule) && in_array($attribute, $rule[0])) {
                return $rule;
            }
        }
        return false;
    }
}