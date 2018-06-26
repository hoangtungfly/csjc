<?php

namespace common\models\lib;

use common\core\cache\GlobalFileCache;
use yii\helpers\ArrayHelper;

class LibTimezonesSearch extends LibTimezones {
    
    public static function getAll() {
        $keyCache = self::getKeyFileCache('getAll');
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $data = self::find()->all();
            $cache->set($keyCache, $data);
        }
        return $data;
    }
    
    public static function getAllDropDown() {
        return ArrayHelper::map(self::getAll(), 'id', 'display');
    }
}
