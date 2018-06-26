<?php
/**
 * @copyright Copyright (c) 2014 Roman Ovchinnikov
 * @link https://github.com/RomeroMsk
 * @version 1.0.0
 */
namespace common\widgets\timepicker;

use yii\web\AssetBundle;

/**
 * ChosenAsset
 *
 * @author Roman Ovchinnikov <nex.software@gmail.com>
 * @link https://github.com/RomeroMsk/yii2-chosen
 * @see http://harvesthq.github.io/chosen
 */
class TimePickerAsset extends AssetBundle
{
    public $sourcePath = '@common/widgets/timepicker/assets';

    public $css = [
        'css/jquery-ui-timepicker-addon.css'
    ];

    public $js = [
        'js/jquery-ui-timepicker-addon.js'
    ];

    public $depends = [
        
    ];
}
