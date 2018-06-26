<?php
/**
 * @author DungNguyenAnh
 * @date 26/8/2015
 */
namespace backend\widgets\datepicker;

use yii\web\AssetBundle;

class DatepickerAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/datepicker/assets';

    public $css = [
        'bootstrap-datepicker.min.css'
    ];

    public $js = [
        'bootstrap-datepicker.min.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}