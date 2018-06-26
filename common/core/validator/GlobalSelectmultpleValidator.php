<?php

namespace common\core\validator;

use yii\validators\Validator;

class GlobalSelectmultpleValidator extends Validator {

    public $compareValue;

    public $message;

    public function init() {
        parent::init();
        if ($this->message === null) {
            $this->message = 'Please select less than {compareValue} {attribute}.';
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute) {
        $value = $model->$attribute;
        if ($value && is_array($value) && count($value) >= $this->compareValue) {
            $compareLabel = $model->getAttributeLabel($attribute);
            $this->addError($model, $attribute, $this->message, [
                'attribute' => $compareLabel,
                'compareValue' => $this->compareValue,
            ]);
            return;
        }
    }

}
