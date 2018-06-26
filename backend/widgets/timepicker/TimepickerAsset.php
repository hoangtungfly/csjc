<?php
/**
 * @author DungNguyenAnh
 * @date 26/8/2015
 */
namespace backend\widgets\timepicker;

use yii\web\AssetBundle;

class TimepickerAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/timepicker/assets';

    public $css = [
        'bootstrap-timepicker.min.css',
    ];

    public $js = [
        'bootstrap-timepicker.min.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}