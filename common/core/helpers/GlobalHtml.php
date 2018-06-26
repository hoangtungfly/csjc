<?php
/**
 * Dung Nguyen Anh
 */
namespace common\core\helpers;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Request;

class GlobalHtml extends Html {
    public static function activeCheckbox($model, $attribute, $options = [],$labelFlag = true,$uncheckFlag = true)
    {
        $name = isset($options['name']) ? $options['name'] : static::getInputName($model, $attribute);
        $value = static::getAttributeValue($model, $attribute);

        if (!array_key_exists('value', $options)) {
            $options['value'] = '1';
        }
        if (!array_key_exists('uncheck', $options) && $uncheckFlag) {
            $options['uncheck'] = '0';
        }
        if (!array_key_exists('label', $options) && $labelFlag) {
            $options['label'] = static::encode($model->getAttributeLabel(static::getAttributeName($attribute)));
        }

        $checked = "$value" === "{$options['value']}";

        if (!array_key_exists('id', $options)) {
            $options['id'] = static::getInputId($model, $attribute);
        }

        return static::checkbox($name, $checked, $options);
    }
    
    public static function activeCheckboxOne($model, $attribute, $options = [],$labelFlag = true,$uncheckFlag = true) {
        $name = isset($options['name']) ? $options['name'] : static::getInputName($model, $attribute);
        $value = static::getAttributeValue($model, $attribute);

        if (!array_key_exists('value', $options)) {
            $options['value'] = '1';
        }
        if (!array_key_exists('uncheck', $options) && $uncheckFlag) {
            $options['uncheck'] = '0';
        }
        $label = '';
        if (!array_key_exists('label', $options) && $labelFlag) {
            $label = static::encode($model->getAttributeLabel(static::getAttributeName($attribute)));
        } else {
            $label = $options['label'];
        }

        $checked = "$value" === "{$options['value']}";

        if (!array_key_exists('id', $options)) {
            $options['id'] = static::getInputId($model, $attribute);
        }
        $labelOptions = isset($options['labelOptions']) ? $options['labelOptions'] : '';
        unset($options['label'],$options['labelOptions']);
        
        return self::input('hidden', $name,0)."\n" . self::input('checkbox', $name, $options['value'], $options)."\n " . self::label($label,$options['id'],$labelOptions);
    }
    
    public static function activeCheckboxTwo($model, $attribute, $options = [],$labelFlag = true,$uncheckFlag = true) {
        $name = isset($options['name']) ? $options['name'] : static::getInputName($model, $attribute);
        $value = static::getAttributeValue($model, $attribute);

        if (!array_key_exists('value', $options)) {
            $options['value'] = '1';
        }
        if (!array_key_exists('uncheck', $options) && $uncheckFlag) {
            $options['uncheck'] = '0';
        }
        $label = '';
        if (!array_key_exists('label', $options) && $labelFlag) {
            $label = static::encode($model->getAttributeLabel(static::getAttributeName($attribute)));
        }

        $checked = "$value" === "{$options['value']}";

        if (!array_key_exists('id', $options)) {
            $options['id'] = static::getInputId($model, $attribute);
        }
        $labelOptions = isset($options['labelOptions']) ? $options['labelOptions'] : '';
        unset($options['label'],$options['labelOptions']);
        
        return self::input('hidden', $name,0)."\n" . self::input('checkbox', $name, $options['value'], $options);
    }
    
    public static function beginForm($action = '', $method = 'post', $options = [], $hideAction = false)
    {
        $action = Url::to($action);

        $hiddenInputs = [];

        $request = Yii::$app->getRequest();
        if ($request instanceof Request) {
            if (strcasecmp($method, 'get') && strcasecmp($method, 'post')) {
                // simulate PUT, DELETE, etc. via POST
                $hiddenInputs[] = static::hiddenInput($request->methodParam, $method);
                $method = 'post';
            }
            if ($request->enableCsrfValidation && !strcasecmp($method, 'post')) {
                $hiddenInputs[] = static::hiddenInput($request->csrfParam, $request->getCsrfToken());
            }
        }

        if (!strcasecmp($method, 'get') && ($pos = strpos($action, '?')) !== false) {
            // query parameters in the action are ignored for GET method
            // we use hidden fields to add them back
            foreach (explode('&', substr($action, $pos + 1)) as $pair) {
                if (($pos1 = strpos($pair, '=')) !== false) {
                    $hiddenInputs[] = static::hiddenInput(
                        urldecode(substr($pair, 0, $pos1)),
                        urldecode(substr($pair, $pos1 + 1))
                    );
                } else {
                    $hiddenInputs[] = static::hiddenInput(urldecode($pair), '');
                }
            }
            $action = substr($action, 0, $pos);
        }
        $options['action'] = $action;
        $options['method'] = $method;
        if($hideAction) {
            unset($options['action']);
        }
        
        $form = static::beginTag('form', $options);
        if (!empty($hiddenInputs)) {
            $form .= "\n" . implode("\n", $hiddenInputs);
        }

        return $form;
    }
    
}