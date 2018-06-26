<?php

namespace common\models\admin;

use common\core\cache\GlobalFileCache;
use Yii;

class SettingsStatisticalSearch extends SettingsStatistical {
    public static function getAll() {
        $key = self::getKeyFileCache('getallstaticstical');
        $cache = new GlobalFileCache();
        $app = $cache->get($key);
        if (!$app) {
            $app = self::find()->orderBy('odr')->all();
            $cache->set($key, $app);
        }
        return $app;
    }
    
    public static function getAllCount() {
        $list = self::getAll();
        $result = [];
        if($list) {
            foreach($list as $key => $item) {
                $a['count'] = app()->db->createCommand($item->sql)->queryScalar();
                $a['icon']  = $item->icon;
                $a['link']  = $item->link;
                $a['name']  = $item->name;
                $result[] = $a;
            }
        }
        return $result;
    }
    
    public function afterSave($insert, $changedAttributes) {
        $this->deleteDefaultFileCacheDefault();
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeDelete() {
        $this->deleteDefaultFileCacheDefault();
        return parent::beforeDelete();
    }
    
    
    
    public function deleteDefaultFileCacheDefault() {
        $arrayKeyCache = array(
            'getallstaticstical',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }
}
