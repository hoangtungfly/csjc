<?php

namespace common\models\product;

use common\core\cache\GlobalFileCache;
use yii\helpers\ArrayHelper;

class ManufacturerSearch extends Manufacturer {
    public static function getAll($category_id = false) {
        $where = ['status' => 1];
        if($category_id){
            $category_id = (int)$category_id;
            $keyCache = self::getKeyFileCache('getAll_'.$category_id);
            $where['category_id'] = $category_id;
        }else{
            $keyCache = self::getKeyFileCache('getAll');
        }
        
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = self::find()->where($where)->orderBy('odr')->all();
            $cache->set($keyCache, $result);
        }
        return $result;
    }
    
    public static function getAllDropown() {
        return ArrayHelper::map(self::getAll(), 'id', 'name');
    }
}
