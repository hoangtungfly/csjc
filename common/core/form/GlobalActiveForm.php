<?php

/**
 * ActiveForm is a widget that builds an interactive HTML form for one or multiple data models.
 *
 * @author Phong PHam Hong
 */

namespace common\core\form;

use Closure;
use Yii;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use common\core\helpers\GlobalHtml;

class GlobalActiveForm extends ActiveForm {

    public $fieldClass = 'common\core\form\GlobalActiveField';
    public $angular = false;
    public $hideAction = false;
    public $reset_attribute = true;

    /**
     * Generates a form field.
     * A form field is associated with a model and an attribute. It contains a label, an input and an error message
     * and use them to interact with end users to collect their inputs for the attribute.
     * @param Model $model the data model
     * @param string $attribute the attribute name or expression. See [[Html::getAttributeName()]] for the format
     * about attribute expression.
     * @param array $options the additional configurations for the field object
     * @return ActiveField the created ActiveField object
     * @see fieldConfig
     */
    public function field($model, $attribute, $options = []) {
        $config = $this->fieldConfig;
        $options['angular'] = $this->angular;
        $options['reset_attribute'] = $this->reset_attribute;
        if ($config instanceof Closure) {
            $config = call_user_func($config, $model, $attribute);
        }
        if (!isset($config['class'])) {
            $config['class'] = $this->fieldClass;
        }
        return Yii::createObject(ArrayHelper::merge($config, $options, [
                            'model' => $model,
                            'attribute' => $attribute,
                            'form' => $this,
        ]));
    }

    /**
     * Renders an HTML label for a model attribute.
     * This method is a wrapper of {@link CHtml::activeLabelEx}.
     * Please check {@link CHtml::activeLabelEx} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated label tag
     */
    public function labelEx($model, $attribute, $options = array()) {
        return $this->field($model, $attribute)->label(null, $options);
    }

    /**
     * render checkbox with an default template
     * 
     * set default html template
     * @return string
     */
    public function pCheckBox($model, $attribute, $options = []) {
        $temp = isset($options['template']) ? $options['template'] : '<div class="btn-group checkbox" data-toggle="buttons">'
                . '<a class="btn btn-primary tabcheck" href="javascript: void(0)">'
                . '{input}'
                . '</a>'
                . '{label} '
                . '{error}'
                . '</div>';
        $options['template'] = $temp;
        $checkboxparams = isset($options['options']) ? (array) $options['options'] : array();
        unset($options['options']);
        $labeltext = isset($options['label']) ? $options['label'] : false;
        unset($options['label']);
        return $this->field($model, $attribute, $options)->checkbox($checkboxparams, false)->label($labeltext);
    }

    public function init() {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        echo GlobalHtml::beginForm($this->action, $this->method, $this->options, $this->hideAction);
    }

}
