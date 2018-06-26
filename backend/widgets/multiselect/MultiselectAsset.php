<?php
/**
 * @author DungNguyenAnh
 * @date 26/8/2015
 */
namespace backend\widgets\multiselect;

use yii\web\AssetBundle;

class MultiselectAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/multiselect/assets';

    public $css = [
        'bootstrap-multiselect.min.css'
    ];

    public $js = [
        'bootstrap-multiselect.min.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}