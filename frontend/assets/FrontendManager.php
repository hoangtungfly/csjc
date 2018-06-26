<?php

namespace frontend\assets;

use yii\web\AssetManager;

class FrontendManager extends AssetManager {
    /**
     * @return string the root directory storing the published asset files.
     */
    public $basePath = '@webroot/frontend/web/assets';
    /**
     * @return string the base URL through which the published asset files can be accessed.
     */
    public $baseUrl = '@web/assets';
}