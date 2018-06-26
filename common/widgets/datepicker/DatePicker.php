<?php

namespace common\widgets\datepicker;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * echo DatePicker::widget([
 *       'model' => $model,
 *       'attribute' => 'birthday',
 *       'options' => array(
 *           'endDate' => 'new Date()',
 *           'startView' => 2,
 *       ),
 *       'htmlOptions' => array(
 *           'placeholder' => 'date of birth',
 *       ),
 *   ]);
 * </pre>
 * @author Phong Pham Hong <phongbro1805@gmail.com>
 * extension DatePicker
 * extended from CJuiAutoComplete
 * set some default options

 */
class DatePicker extends InputWidget {
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
    public $addon=false;
    public $icon_template='<i class="fa fa-calendar"></i>';

    public function init() {
        parent::init();
//        $this->themeUrl = Yii::app()->request->baseUrl . '/js/bootstrap-js/bootstrap-datepicker';
//        $this->scriptUrl = Yii::app()->request->baseUrl . '/js/bootstrap-js/bootstrap-datepicker';
//        $this->cssFile = 'datepicker.css';
//        $this->scriptFile = 'bootstrap-datepicker.js';
        # set default options
        $arrayHtmlOp = array(
            'class' => 'input-text',
            //'readonly' => 'readonly',
            'dateFormat' => 'datepicker date-day',
            'forceParse' => 'true',
            'keyboardNavigation' => 'true'
        );
        $this->htmlOptions = array_merge($arrayHtmlOp, $this->htmlOptions);

        # set default options JS
        $arrayOp = array(
            'format' => 'd-M-yyyy',
            'autoclose' => true,
            'todayHighlight'=>false,
        );
        $this->options = array_merge($arrayOp, $this->options);
        
    }

    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run() {
        DatePickerAsset::register($this->getView());
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
                $input = Html::activeTextInput($this->model, $this->attribute, $this->htmlOptions);
                if($this->addon){
                    $input .= Html::tag('span', $this->icon_template, ['class' => 'add-on']);
                }
                echo $input;
                    
            } else {
                $input = Html::textInput($name, $this->value, $this->htmlOptions);
                if($this->addon){
                    $input .= Html::tag('span', $this->icon_template, ['class' => 'add-on']);
                }
                echo $input;
            }
                
        }
        else {
            if ($this->hasModel()) {
                $input = Html::activeTextInput($this->model, $this->attribute, $this->htmlOptions);
                if($this->addon){
                    $input .= Html::tag('span', $this->icon_template, ['class' => 'add-on']);
                }
                echo $input;
                $attribute = $this->attribute;
                $this->options['defaultDate'] = $this->model->$attribute;
            } else {
                $input = Html::hiddenInput($name, $this->value, $this->htmlOptions);
                if($this->addon){
                    $input .= Html::tag('span', $this->icon_template, ['class' => 'add-on']);
                }
                echo $input;
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
        if ($this->typeView == 'birthday' && !$this->value) {
            $this->options['startView'] = 'decade';
        }
        
        $options = Json::encode($this->options);
        $js = "jQuery('#{$id}').bdatepicker($options)";

        # add event
        if (!isset($this->events['hide'])) {
            $this->events['hide'] = "var oldtext = $('#{$id}').attr('value'); var value = $(this).val(); if(value == ''){
                $('#{$id}').val(oldtext);"
                    . "} ";
        }
        if (is_array($this->events) && count($this->events) > 0) {
            foreach ($this->events as $key => $value) {
                $js = $js . ".on('{$key}',function(ev){{$value}})";
            }
        }
        $js = $js . ';';
        if($this->addon) {
            $js .= "
                $('#{$id}').parent().find('i').click(function(e){
                    jQuery('#{$id}').focus();
                });
            ";
                     
        }
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
