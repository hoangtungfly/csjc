<?php

namespace common\core\validator;

use Yii;
use yii\validators\CompareValidator;

class GlobalCompareValidator extends CompareValidator {

    protected function compareValues($operator, $type, $value, $compareValue) {
        switch($type) {
            case 'number':
                $value = (float) $value;
                $compareValue = (float) $compareValue;
                break;
            case 'date':
                $value = strtotime($value);
                $compareValue = strtotime($compareValue);
                break;
            default :
                $value = (string) $value;
                $compareValue = (string) $compareValue;
                break;
        }
        switch ($operator) {
            case '==':
                return $value == $compareValue;
            case '===':
                return $value === $compareValue;
            case '!=':
                return $value != $compareValue;
            case '!==':
                return $value !== $compareValue;
            case '>':
                return $value > $compareValue;
            case '>=':
                return $value >= $compareValue;
            case '<':
                return $value < $compareValue;
            case '<=':
                return $value <= $compareValue;
            default:
                return false;
        }
    }
    
    public static function getMessageByOperator($operator) {
        $msg = '';
        switch ($operator) {
            case '==':
                $msg = Yii::t('yii', '{attribute} must be repeated exactly.');
                break;
            case '===':
                $msg = Yii::t('yii', '{attribute} must be repeated exactly.');
                break;
            case '!=':
                $msg = Yii::t('yii', '{attribute} must not be equal to "{compareValue}".');
                break;
            case '!==':
                $msg = Yii::t('yii', '{attribute} must not be equal to "{compareValue}".');
                break;
            case '>':
                $msg = Yii::t('yii', '{attribute} must be greater than "{compareValue}".');
                break;
            case '>=':
                $msg = Yii::t('yii', '{attribute} must be greater than or equal to "{compareValue}".');
                break;
            case '<':
                $msg = Yii::t('yii', '{attribute} must be less than "{compareValue}".');
                break;
            case '<=':
                $msg = Yii::t('yii', '{attribute} must be less than or equal to "{compareValue}".');
                break;
        }
        return $msg;
    }
    
    

}
