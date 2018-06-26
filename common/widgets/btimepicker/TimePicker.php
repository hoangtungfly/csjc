<?php

namespace common\widgets\btimepicker;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * @author Nguyen Anh Dung <dungnguyenanh@orenj.com>
 * extension DatePicker
 * extended from CJuiAutoComplete
 * set some default options

 */
class TimePicker extends InputWidget {
    #option event

    public $events = array();

    #disbale js
    public $disable = false;
    # type view
    # EX typeView = 'birthday' -> show Year first, and then select month, finally
    public $typeView = '';
    public $defaultOptions = '';
    public $htmlOptions = [];
    public $flat=false;

    public function init() {
        parent::init();
        # set default options JS
        $arrayOp = array(
            'minuteStep' => 1,
            'showSeconds' => false,
            'showMeridian' => false,
        );
        $this->options = array_merge($arrayOp, $this->options);
        
    }

    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run() {
        TimePickerAsset::register($this->getView());
        list($name, $id) = $this->resolveNameID();
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];

        if ($this->flat === false) {
            if ($this->hasModel()) {
                $attr = $this->attribute;
                $this->value = $this->model->$attr;
                echo Html::activeTextInput($this->model, $this->attribute, $this->htmlOptions);
            } else
                echo Html::textInput($name, $this->value, $this->htmlOptions);
        }
        else {
            if ($this->hasModel()) {
                echo Html::activeHiddenInput($this->model, $this->attribute, $this->htmlOptions);
                $attribute = $this->attribute;
                $this->options['defaultDate'] = $this->model->$attribute;
            } else {
                echo Html::hiddenInput($name, $this->value, $this->htmlOptions);
                $this->options['defaultDate'] = $this->value;
            }

            $this->options['altField'] = '#' . $id;

            $id = $this->htmlOptions['id'] = $id . '_container';
            $this->htmlOptions['name'] = $name . '_container';

            echo Html::tag('div', '', $this->htmlOptions);
        }
        # if disable property is setted
        if ($this->disable === true) {
            return;
        }
        
        $options = Json::encode($this->options);
        $js = "jQuery('#{$id}').timepicker($options)";

        if (is_array($this->events) && count($this->events) > 0) {
            foreach ($this->events as $key => $value) {
                $js = $js . ".on('{$key}',function(ev){{$value}})";
            }
        }
        $js = $js . ".next().on('click',function(e){ $(this).prev().focus(); });";
        $this->getView()->registerJs("$js");
        
    }

    protected function resolveNameID() {
        if ($this->name !== null)
            $name = $this->name;
        elseif (isset($this->options['name']))
            $name = $this->options['name'];
        elseif ($this->hasModel())
            $name = Html::getInputName($this->model, $this->attribute);
        else
            throw new \yii\base\Exception('class must specify "model" and "attribute" or "name" property values.');
        if (($id = $this->getId(false)) === null) {
            if (isset($this->options['id']))
                $id = $this->options['id'];
            else
                $id = Html::getInputId($name);
        }
        return array($name, $id);
    }
}
