<?php
/**
 * @copyright Copyright (c) 2014 Roman Ovchinnikov
 * @link https://github.com/RomeroMsk
 * @version 1.0.0
 */
namespace common\widgets\chosen;

use common\utilities\UtilityUrl;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Chosen renders a Chosen select (Harvest Chosen jQuery plugin).
 *
 * @author Roman Ovchinnikov <nex.software@gmail.com>
 * @link https://github.com/RomeroMsk/yii2-chosen
 * @see http://harvesthq.github.io/chosen
 * 
 */
class Chosen extends InputWidget
{
    /**
     * @var boolean whether to render input as multiple select
     */
    public $multiple = false;

    /**
     * @var boolean whether to show deselect button on single select
     */
    public $allowDeselect = true;

    /**
     * @var integer|boolean hide the search input on single selects if there are fewer than (n) options or disable at all if set to true
     */
    public $disableSearch = 10;

    /**
     * @var string placeholder text
     */
    public $placeholder = null;

    /**
     * @var string category for placeholder translation
     */
    public $translateCategory = 'app';

    /**
     * @var array items array to render select options
     */
    public $items = [];

    /**
     * @var array options for Chosen plugin
     * @see http://harvesthq.github.io/chosen/options.html
     */
    public $clientOptions = [];
    
    /**
     * @var array event handlers for Chosen plugin
     * @see http://harvesthq.github.io/chosen/options.html#triggered-events
     */
    public $clientEvents = [];
    
    public $ajax_enum;
    
    public $add_new = false;
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if(isset($this->name) && $this->name && !isset($this->options['id']))
            $this->options['id'] = $this->name;
        if ($this->multiple) {
            $this->options['multiple'] = true;
        } elseif ($this->allowDeselect) {
            $this->items = ArrayHelper::merge([null => ''], $this->items);
            $this->clientOptions['allow_single_deselect'] = true;
        }
        if ($this->disableSearch === true) {
            $this->clientOptions['disable_search'] = true;
        } else {
            $this->clientOptions['disable_search_threshold'] = $this->disableSearch;
        }
        $this->clientOptions['placeholder_text_single'] = \Yii::t($this->translateCategory, $this->placeholder ? $this->placeholder : 'Select an option');
        $this->clientOptions['placeholder_text_multiple'] = \Yii::t($this->translateCategory, $this->placeholder ? $this->placeholder : 'Select some options');
        $this->clientOptions['no_results_text'] = \Yii::t('app', 'No results match');
        if ($this->add_new) {
            $this->clientOptions = array_merge([
                'create_option' => 'true',
                // persistent_create_option decides if you can add any term, even if part
                // of the term is also found, or only unique, not overlapping terms
                'persistent_create_option' => 'true',
                // with the skip_no_results option you can disable the 'No results match..' 
                // message, which is somewhat redundant when option adding is enabled
                'skip_no_results' => 'true'
                    ], $this->clientOptions);
        }
        $this->options['unselect'] = null;
        
        if($this->ajax_enum) {
            $url = UtilityUrl::createUrl('/suggest/loadchosen',['ajax_enum' => $this->ajax_enum]);
            $this->options['data-loadchosen'] = $url;
            if(!isset($this->options['data-param']))
                $this->options['data-param'] = '';
        }
        
        $this->registerScript();
        $this->registerEvents();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeListBox($this->model, $this->attribute, $this->items, $this->options);
        } else {
            echo Html::listBox($this->name, $this->value, $this->items, $this->options);
        }
        $this->registerAjax();
    }
    
    public function registerAjax() {
        if($this->ajax_enum) {
            $id = $this->options['id'];
            $id_chosen = str_replace('-','_',$this->options['id']).'_chosen';
            
            $value = $this->model->{$this->attribute};
            $clientOptions = Json::encode($this->clientOptions);
            cs()->registerJs("jQuery('body').on('click','#$id_chosen',function(e){
                if(!$('#$id').attr('loadtrue')) {
                    e.preventDefault();
                    e.stopPropagation();
                    var thet = $(this);
                    MainAjax({
                        url     : $('#$id').data('loadchosen'),
                        data    : $('#$id').attr('data-param'),
                        async   : false,
                        success : function(rs) {
                            if(rs.code == 200) {
                                var that = $('#$id');
                                that.chosen('destroy');
                                that.html(rs.data);
                                that.val('$value');
                                that.chosen($clientOptions);
                                $('#$id').attr('loadtrue',1);
                                that.trigger('chosen:open.chosen');
                            }
                        },
                    });
                };
            });");
        }
    }

    /**
     * Registers chosen.js
     */
    public function registerScript()
    {
        ChosenAsset::register($this->getView());
        $clientOptions = Json::encode($this->clientOptions);
        $id = $this->options['id'];
        $this->getView()->registerJs("jQuery('#$id').chosen({$clientOptions});");
    }

    /**
     * Registers Chosen event handlers
     */
    public function registerEvents()
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handle) {
                $handle = new JsExpression($handle);
                $js[] = "jQuery('#{$this->options['id']}').on('{$event}', {$handle});";
            }
            $this->getView()->registerJs(implode(PHP_EOL, $js));
        }
    }
}
