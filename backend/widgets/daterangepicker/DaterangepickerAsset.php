<?php
/**
 * @author DungNguyenAnh
 * @date 26/8/2015
 */
namespace backend\widgets\daterangepicker;

use yii\web\AssetBundle;

class DaterangepickerAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/daterangepicker/assets';

    public $css = [
    ];

    public $js = [
        'daterangepicker.min.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}