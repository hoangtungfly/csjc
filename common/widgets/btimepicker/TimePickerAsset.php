<?php
/**
 * @copyright Copyright (c) 2014 Roman Ovchinnikov
 * @link https://github.com/RomeroMsk
 * @version 1.0.0
 */
namespace common\widgets\btimepicker;

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
    public $sourcePath = '@common/widgets/btimepicker/assets';

    public $css = [
        'css/bootstrap-timepicker.min.css'
    ];

    public $js = [
        'js/bootstrap-timepicker.min.js'
    ];

    public $depends = [
        
    ];
}
