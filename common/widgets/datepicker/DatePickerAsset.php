<?php
/**
 * @copyright Copyright (c) 2014 Roman Ovchinnikov
 * @link https://github.com/RomeroMsk
 * @version 1.0.0
 */
namespace common\widgets\datepicker;

use yii\web\AssetBundle;

/**
 * ChosenAsset
 *
 * @author Roman Ovchinnikov <nex.software@gmail.com>
 * @link https://github.com/RomeroMsk/yii2-chosen
 * @see http://harvesthq.github.io/chosen
 */
class DatePickerAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/datepicker/assets';

    public $css = [
        'css/datepicker.min.css'
    ];

    public $js = [
        'js/bootstrap-datepicker.js'
    ];

    public $depends = [
//        'yii\bootstrap\BootstrapAsset',
//        'yii\web\JqueryAsset',
    ];
}
