<?php
namespace common\widgets\tokeninput;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * TokenInput displays a text input field for a model attribute and applies the jQuery Tokeninput plugin
 * on it.
 * 
 * jQuery Tokeninput plugin homepage: {@link http://loopj.com/jquery-tokeninput}.
 * 
 * Important notes:
 * <ul>
 * 	<li>Currently only string attributes are supported.</li>
 * 	<li>Currently only server-backed search is supported, no local data search.</li>
 * 	<li>Tokens are added using the token value for both tokenValue (default: 'id') and propertyToSearch (default: 'name'), i.e. {'id': value, 'name': value}.</li>
 * 	<li>In production (YII_DEBUG not defined or set to false), a minified version of the JS file is loaded (minified with http://jscompress.com/).</li>
 * 	<li>The included Tokeninput plugin is version 1.6.0 enhanced with the pull request 'Allow creation of tokens on the fly' ({@link https://github.com/loopj/jquery-tokeninput/pull/219}).</li>
 * </ul>
 * 
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * echo TokenInput::widget([
 *   'url' => 'sdsdsdsdsd/asdasd/ádasd',
 *   'options' => array(
 *      'allowCreation' => false,
 *      'deleteText' => 'x',
 *   
 *       ),
 *   'value' => 111,
 *   'clientEvents'=>[
 *          'onAdd' => 11 ? '' :'function(item){
 *               if(!item.id) {
 *                   item.id = insertFieldDataEx($(this), item.value);
 *               }
 *               if(item.id) {
 *                   updateFieldDataEx($(this),item.id);
 *               }
 *           }',
 *           'onDelete' => 'function(item){console.log("11111111")}',
 *      ]
 *   'name' => 22222,
 *   'id' => 'presit_',
 *   'htmlOptions' => array('class' => 'token-input-disabled' .  1212)
 *]);
 * </pre>
 * 
 * The widget will automatically pre-populate the token input with the value of the attribute.
 * 
 * The 'cssFile' property can be defined to use a custom css file. If it is not defined, one of the default
 * plugin CSS files will be used based on the value of $options['theme']. If this option is not defined,
 * 'token-input.css' will be used, otherwise 'token-input-<theme>.css' will be used. Look at the css files
 * in the extensions 'css' directory for available themes.
 * 
 * @author Haykel Ben Jemia (http://www.allmas-tn.com)
 * @version 0.3
 * @license Like the jQuery Tokeninput plugin, GPL or MIT depending on the project you are using it in and how you wish to use it.
 */

class TokenInput extends InputWidget {

    /**
     * @var CModel the data model associated with this widget.
     */
    public $model;

    /**
     * class default
     * 
     * @var string
     */
    private $defaultClass;

    /**
     * @var string the attribute associated with this widget.
     */
    public $attribute;

    /**
     * id of input
     * 
     * @var string
     */
    public $id = 'ipt-tagging';

    /**
     * value of this input
     * 
     * @var data
     */
    public $value;

    /**
     * choose another selector
     * 
     * @var string
     */
    public $selector = '';

    /**
     * name of input
     * 
     * @var string
     */
    public $name;

    /**
     * @var mixed URL or an action route that can be used to create the URL to handle search requests.
     * See {@link normalizeUrl} for more details about how to specify this parameter.
     * See {@link http://loopj.com/jquery-tokeninput} for more details about the script. 
     */
    public $url;

    /**
     * @var array the initial JavaScript options that should be passed to the jQuery Tokeninput plugin.
     */
    public $options = array();

    /**
     * @var string the CSS file to use. Defaults to 'null', meaning to use one of the default plugin CSS files based
     * on the value of $options['theme']. If it is not defined, 'token-input.css' will be used, otherwise
     * 'token-input-<theme>.css' will be used.
     */
    public $cssFile;

    /**
     * @phongph
     * html options
     * 
     * @var array
     */
    public $htmlOptions = array();

    /**
     * @phongph
     * default options
     */
    private $defaultOptions = array(
        'allowCreation' => true,
        'preventDuplicates' => true,
        'theme' => 'facebook',
        'searchDelay' => 300,
        'queryParam' => 'term',
        'minChars' => 1,
        'hintText' => '',
        'animateDropdown' => false,
    );
    
     public $clientEvents = [];

    /**
     * Splits the specified string by the specified delimiter.
     * 
     * @param string $value string to split.
     * @param string $tokenDelimiter delimiter by which to slit the string. If empty, ',' will be used.
     * @return array tokens found. Empty tokens are ignored and not included.
     */
    public static function tokenize($value, $tokenDelimiter = null) {
        if (empty($tokenDelimiter))
            $tokenDelimiter = ',';

        return preg_split('/\s*' . $tokenDelimiter . '\s*/', $value, -1, PREG_SPLIT_NO_EMPTY);
    }

    /* (non-PHPdoc)
     * @see CWidget::init()
     */

    public function init() {
        parent::init();
        list($name, $id) = $this->resolveNameID();
        if (!is_array($this->options))
            $this->options = array();
        /**
         * adding value if values are passed
         */
        if (is_object($this->model)) {
            $value = $this->model->{$this->attribute};
            $this->htmlOptions['id'] = $id;
            $this->htmlOptions['name'] = $name;
        } else {
            $value = $this->value;
        }
        $tokenValue = 'id';
        if (isset($this->options['tokenValue']) && strlen(trim($this->options['tokenValue'])) > 0) {
            $tokenValue = trim($this->options['tokenValue']);
            $this->options['tokenValue'] = $tokenValue;
        }

        $propertyToSearch = 'name';
        if (isset($this->options['propertyToSearch']) && strlen(trim($this->options['propertyToSearch'])) > 0) {
            $propertyToSearch = trim($this->options['propertyToSearch']);
            $this->options['propertyToSearch'] = $propertyToSearch;
        }

        if (is_array($value) && count($value)) {
            $prePopulate = array();
            $aryValue = array();
            foreach ($value as $key => $token) {
                $token = preg_match('/<\/script>/',$token) ? Html::encode($token) : $token;
                $prePopulate[] = array($tokenValue => $key, $propertyToSearch => $token);
                $aryValue[] = $key;
            }

            if (!empty($prePopulate)) {
                $this->options['prePopulate'] = $prePopulate;
                $this->value = join(',', $aryValue);
            }
        } elseif (!empty($value)) {
            $prePopulate = array();
            $tokenDelimiter = isset($this->options['tokenDelimiter']) ? $this->options['tokenDelimiter'] : null;
            $tokens = self::tokenize($value, $tokenDelimiter);

            if (isset($this->options['preventDuplicates']) && $this->options['preventDuplicates'] === true)
                $tokens = array_unique($tokens);

            foreach ($tokens as $token)
                $prePopulate[] = array($tokenValue => $token, $propertyToSearch => $token);

            if (!empty($prePopulate))
                $this->options['prePopulate'] = $prePopulate;
        }

        /**
         * process default options
         */
        foreach ($this->defaultOptions as $key => $value) {
            if (!isset($this->options[$key])) {
                $this->options[$key] = $value;
            }
        }
        /**
         * process htmlOptions
         */
        if (!isset($this->htmlOptions['id'])) {
            $this->htmlOptions['id'] = $this->id;
        }
        if (!isset($this->htmlOptions['name'])) {
            $this->htmlOptions['name'] = $this->name;
        }

    }

    /**
     * Publishes and resgisters the external javascript and css files.
     */
    public function registerClientScripts() {
        TokenInputAsset::register($this->getView());
        $clientOptions = Json::encode($this->options);
        //$id = $this->id;
        if($this->clientEvents) {
            $js = [];
            foreach ($this->clientEvents as $event => $handle) {
                $handle = new JsExpression($handle);
                $handle = $handle == '' ? "''" : $handle;
                $js[] = "'$event': {$handle},";
            }
            $clientOptions = substr($clientOptions, 0, -1) . ',' . implode(PHP_EOL, $js);
            $clientOptions = substr($clientOptions, 0, -1);
            $clientOptions .= '}';
        }
        $selector = trim($this->selector) == '' ? '#' . $this->htmlOptions['id'] : $this->selector;
        $this->getView()->registerJs("jQuery('{$selector}').tokenInput('{$this->url}',{$clientOptions});");
    }
    /* (non-PHPdoc)
     * @see CWidget::run()
     */

    public function run() {
        $this->registerClientScripts();
        if (trim($this->selector) == '') {
            $setName = '';
            if (is_object($this->model)) {
                $setName = !isset($this->htmlOptions['name']) || trim($this->htmlOptions['name']) == '' ?
                        get_class($this->model) . "[{$this->attribute}]" :
                        $this->htmlOptions['name'];
            } else {
                $setName = trim($this->htmlOptions['name']);
            }
            $v = is_array($this->value) && !count($this->value) ? '' : $this->value;
            echo Html::textInput($setName, $v, $this->htmlOptions);
        }
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
