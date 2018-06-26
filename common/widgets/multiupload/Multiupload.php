<?php

/**
 * @copyright Copyright (c) 2014 Roman Ovchinnikov
 * @link https://github.com/RomeroMsk
 * @version 1.0.0
 */

namespace common\widgets\multiupload;

use Yii;
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
class Multiupload extends InputWidget {

    /**
     * @var string the file types that are allowed (eg "gif|jpg").
     * Note, the server side still needs to check if the uploaded files have allowed types.
     */
    public $accept;

    /**
     * @var integer the maximum number of files that can be uploaded. If -1, it means no limits. Defaults to -1.
     */
    public $max = -1;

    /**
     * @var string the label for the remove button. Defaults to "Remove".
     */
    public $remove;

    /**
     * @var string message that is displayed when a file type is not allowed.
     */
    public $denied;

    /**
     * @var string message that is displayed when a file is selected.
     */
    public $selected;

    /**
     * @var string message that is displayed when a file appears twice.
     */
    public $duplicate;

    /**
     * @var string the message template for displaying the uploaded file name
     * @since 1.1.3
     */
    public $file;

    /**
     * @var array additional options that can be passed to the constructor of the multifile js object.
     * @since 1.1.7
     */
    public $clientOptions = [];

    /**
     * Runs the widget.
     * This method registers all needed client scripts and renders
     * the multiple file uploader.
     */
    public function run() {
        list($name, $id) = $this->resolveNameID();
        if (substr($name, -2) !== '[]')
            $name.='[]';
        if (isset($this->options['id']))
            $id = $this->options['id'];
        else
            $this->options['id'] = $id;
        $this->registerScript();
        echo Html::fileInput($name, '', $this->options);
    }

    /**
     * Registers the needed CSS and JavaScript.
     */
    public function registerScript() {
        $id = $this->options['id'];

        $clientOptions = $this->getClientOptions();
        $clientOptions = $clientOptions === array() ? '' : Json::encode($clientOptions);

        MultiuploadAsset::register($this->getView());
        $this->getView()->registerJs("jQuery('#$id').MultiFile({$clientOptions});");
    }

    /**
     * @return array the javascript options
     */
    protected function getClientOptions() {
        $options = $this->clientOptions;
        foreach (array('onFileRemove', 'afterFileRemove', 'onFileAppend', 'afterFileAppend', 'onFileSelect', 'afterFileSelect') as $event) {
            if (isset($options[$event]) && !($options[$event] instanceof JsExpression))
                $options[$event] = new JsExpression($options[$event]);
        }

        if ($this->accept !== null)
            $options['accept'] = $this->accept;
        if ($this->max > 0)
            $options['max'] = $this->max;

        $messages = array();
        foreach (array('remove', 'denied', 'selected', 'duplicate', 'file') as $messageName) {
            if ($this->$messageName !== null)
                $messages[$messageName] = $this->$messageName;
        }
        if ($messages !== array())
            $options['STRING'] = $messages;

        return $options;
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
