<?php

namespace common\models\admin;

use common\core\cache\GlobalFileCache;
use common\core\enums\LanguageEnum;
use yii\helpers\ArrayHelper;

class SettingsMessageSearch extends SettingsMessage {
    
    public static $list_all_name = false;
    public static $list_all_by_name = [];
    
    public static function getAllName() {
        if(!self::$list_all_name) {
            $keyCache = self::getKeyFileCache('getAllName'.app()->language);
            $cache = new GlobalFileCache();
            $result = $cache->get($keyCache);
            if (!$result) {
                $result = ArrayHelper::map(self::find()->where(['lang' => app()->language])->groupBy('name')->orderBy('name')->all(),'name','name');
                $cache->set($keyCache, $result);
            }
            self::$list_all_name = $result;
        }
        return self::$list_all_name;
    }
    
    public static function getAllByName($name) {
        if(!isset(self::$list_all_by_name[$name])) {
            $keyCache = self::getKeyFileCache('getAllByName'.$name.'_'.app()->language);
            $cache = new GlobalFileCache();
            $result = $cache->get($keyCache);
            if (!$result) {
                $result = ArrayHelper::map(self::find()->where(['name' => $name,'lang' => app()->language])->all(),'message_key','message_value');
                $cache->set($keyCache, $result);
            }
            self::$list_all_by_name[$name] = $result;
        }
        return self::$list_all_by_name[$name];
    }
    
    public static $list_t = [];
    
    public static function t($name,$key,$value = false, $update = false) {
        $key_static = $name.'_'.$key;
        if(!isset(self::$list_t[$key_static])) {
            $list = self::getAllByName($name);
            if(!isset($list[$key]) || ($value && $value != $list[$key]) && $update) {
                $list[$key] = self::insertAutoMessage($name, $key, $value);
            }
            self::$list_t[$key_static] = $list[$key];
        }
        return nl2br(self::$list_t[$key_static]);
    }
    
    public static function insertAutoMessage($name,$key,$value = false) {
        if($name && $key) {
            $model = SettingsMessageSearch::findOne(['name' => $name,'message_key' => $key,'lang' => 'vi']);
            $flag = true;
            if(!$model) {
                $model = new SettingsMessageSearch();
                $model->lang = LanguageEnum::VI;
                $flag = false;
            }
            $model->name = $name;
            $model->message_key = $key;
            $model->message_value = $value ? $value : $key;
            $model->save();
            if(!$flag) {
                $modelEn = SettingsMessageSearch::findOne(['name' => $name,'message_key' => $key,'lang' => 'en']);
                if(!$modelEn) {
                    $modelEn = new SettingsMessageSearch();
                }
                $modelEn->attributes = $model->attributes;
                $modelEn->lang = LanguageEnum::EN;
                $modelEn->save(false);
            }
            $value = $model->message_value;
        }
        return $value;
    }
    
    public function deleteDefaultFileCacheDefault() {
        $arrayKeyCache = array(
            'getAllName'.app()->language.'*',
            'getAllByName'.$this->name.app()->language.'*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }
}