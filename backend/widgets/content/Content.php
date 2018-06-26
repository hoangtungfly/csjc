<?php
/**
 * @author DungNguyenAnh
 * @date 26/8/2015
 */
namespace backend\widgets\content;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;


class Content extends InputWidget
{

    /**
     * @var array items array to render select options
     */
    public $items = [];

    public $clientOptions = [];
    
    public $clientEvents = [];
    
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if(isset($this->name) && $this->name)
            $this->options['id'] = $this->name;
        
        $this->registerScript();
        $this->registerEvents();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeText($this->model, $this->attribute, $this->items, $this->options);
        } else {
            echo Html::listBox($this->name, $this->value, $this->items, $this->options);
        }
    }

    /**
     * Registers multiselect.js
     */
    public function registerScript() {
        regJsFile([
            
        ]);
        $clientOptions = Json::encode($this->clientOptions);
        $id = $this->options['id'];
        $this->getView()->registerJs("jQuery('#$id').chosen({$clientOptions});");
    }

    /**
     * Registers multiselect event handlers
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
