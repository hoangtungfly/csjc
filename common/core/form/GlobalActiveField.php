<?php

/**
 * ActiveField represents a form input field within an [[ActiveForm]].
 * 
 * @author Dung Nguyen Anh
 */

namespace common\core\form;

use common\core\helpers\GlobalHtml;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveField;

class GlobalActiveField extends ActiveField {

    public $angular = false;
    public $reset_attribute = true;
    public $optionsAngular = [];

    public function init() {
        if ($this->angular) {
            $classNameArray = explode('\\', get_class($this->model));
            $className = $classNameArray[count($classNameArray) - 1];
            $this->optionsAngular['ng-model'] = $className . '.' . $this->attribute;
            $this->inputOptions = array_merge($this->optionsAngular, $this->inputOptions);
            if ($this->reset_attribute) {
                $this->model->{$this->attribute} = null;
            }
        }
    }

    public function label($label = null, $options = []) {
        if ($label === false) {
            $this->parts['{label}'] = '';
            return $this;
        }

        if ($label === null) {
            $arrayLabel = $this->model->attributeLabels();
            $label = isset($arrayLabel[$this->attribute]) ? $arrayLabel[$this->attribute] : '';
        }

        if ($this->model->isAttributeRequired($this->attribute)) {
            $label .= ' <span class="class_required">*</span>';
        }

        $options = array_merge($this->labelOptions, $options);
        if ($label !== null) {
            $options['label'] = $label;
        } else {
            
        }
        $this->parts['{label}'] = Html::activeLabel($this->model, $this->attribute, $options);

        return $this;
    }

    public function description($description = null, $options = []) {
        $this->parts['{description}'] = $description;
        return $this;
    }

    public function render($content = null) {
        if ($content === null) {
            if (!isset($this->parts['{input}'])) {
                $this->parts['{input}'] = Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
            }
            if (!isset($this->parts['{label}'])) {
                $this->parts['{label}'] = Html::activeLabel($this->model, $this->attribute, $this->labelOptions);
            }
            if (!isset($this->parts['{description}'])) {
                $this->parts['{description}'] = '';
            }
            if (!isset($this->parts['{error}'])) {
                if ($this->angular) {
                    $this->parts['{error}'] = Html::tag('div', "{{ error['{$this->attribute}'] }}", $this->errorOptions);
                } else {
                    $this->parts['{error}'] = Html::error($this->model, $this->attribute, $this->errorOptions);
                }
            }
            if (!isset($this->parts['{hint}'])) {
                $this->parts['{hint}'] = '';
            }
            $content = strtr($this->template, $this->parts);
        } elseif (!is_string($content)) {
            $content = call_user_func($content, $this);
        }

        return $this->begin() . "\n" . $content . "\n" . $this->end();
    }

    public function begin() {
        if ($this->form->enableClientScript) {
            $clientOptions = $this->getClientOptions();
            if (!empty($clientOptions)) {
                $this->form->attributes[] = $clientOptions;
            }
        }

        $inputID = Html::getInputId($this->model, $this->attribute);
        $attribute = Html::getAttributeName($this->attribute);
        $options = $this->options;

        if ($this->angular) {
            $options['ng-class'] = "{ 'has-success': !error['{$this->attribute}'] && submitted, 'has-error': error['{$this->attribute}'] && submitted }";
        }

        $class = isset($options['class']) ? [$options['class']] : [];
        $class[] = "field-$inputID";
        if ($this->model->isAttributeRequired($attribute)) {
            $class[] = $this->form->requiredCssClass;
        }
        if ($this->model->hasErrors($attribute)) {
            $class[] = $this->form->errorCssClass;
        }
        $options['class'] = implode(' ', $class);
        $tag = ArrayHelper::remove($options, 'tag', 'div');

        return Html::beginTag($tag, $options);
    }

    public function capcha($options = []) {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $parentOptions = isset($options['parentOptions']) && is_array($options['parentOptions']) ? $options['parentOptions'] : [];
        $divInputOptions = isset($options['divInputOptions']) && is_array($options['divInputOptions']) ? $options['divInputOptions'] : [];
        $divImgOptions = isset($options['divImgOptions']) && is_array($options['divImgOptions']) ? $options['divImgOptions'] : [];
        $imgOptions = isset($options['imgOptions']) && is_array($options['imgOptions']) ? $options['imgOptions'] : [];
        $imgOptions['onclick'] = 'refreshCaptcha();';
        if(!isset($imgOptions['class'])) {
            $imgOptions['class'] = '';
        }
        if(!isset($parentOptions['class'])) {
            $parentOptions['class'] = 'captcha_parent';
        }
        if(!isset($divInputOptions['class'])) {
            $divInputOptions['class'] = 'captcha_input_div';
        }
        if(!isset($divImgOptions['class'])) {
            $divImgOptions['class'] = 'captcha_img_div';
        }
        
        $imgOptions['class'] .= ' settings_captcha';
        unset($options['parentOptions'], $options['divInputOptions'], $options['divImgOptions'], $options['imgOptions']);

        $inputID = Html::getInputId($this->model, $this->attribute);
        $imgOptions['id'] = $inputID . '-image';

        $html = Html::beginTag('div', $parentOptions);
        $html .= Html::beginTag('div', $divImgOptions);
        $html .= Html::img('/site/captcha?v=58347627e94ba', $imgOptions);
        $html .= Html::endTag('div');
        $html .= Html::beginTag('div', $divInputOptions);
        $html .= Html::activeTextInput($this->model, $this->attribute, $options);
        $html .= Html::endTag('div');
        $html .= Html::endTag('div');
        $this->parts['{input}'] = $html;
        cs()->registerJs('refreshCaptcha();');
        return $this;
    }

    public function checkbox($options = [], $enclosedByLabel = true) {
        $classNameArray = explode('\\', get_class($this->model));
        $className = $classNameArray[count($classNameArray) - 1];
        $options = array_merge($options, [
            'value' => 1,
            'ng-checked' => $className . '.' . $this->attribute,
        ]);
        return parent::checkbox($options, $enclosedByLabel);
    }
    
    public function checkboxone($options) {
        $this->parts['{input}'] = GlobalHtml::activeCheckboxTwo($this->model, $this->attribute, $options);
        return $this;
    }

}