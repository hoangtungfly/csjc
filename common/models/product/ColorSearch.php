<?php

namespace common\models\product;

use common\core\cache\GlobalFileCache;

class ColorSearch extends Color {
    public static function getAll() {
        $keyCache = self::getKeyFileCache('getAll');
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = self::find()->where(['status' => 1])->orderBy('odr')->all();
            $cache->set($keyCache, $result);
        }
        return $result;
    }
}
