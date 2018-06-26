<?php
/**
 * @author DungNguyenAnh
 * @date 26/8/2015
 */
namespace backend\widgets\colorpicker;

use yii\web\AssetBundle;

class ColorpickerAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/colorpicker/assets';

    public $css = [
        'colorpicker.min.css',
    ];

    public $js = [
        'bootstrap-colorpicker.min.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}