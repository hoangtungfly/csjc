<?php

namespace common\models\settings;

use common\core\cache\GlobalFileCache;
use common\models\admin\SettingsImages;
use common\models\category\CategoriesSearch;
use common\models\lib\LibTimezonesSearch;
use common\models\news\NewsSearch;
use common\models\product\ProductSearch;
use common\utilities\UtilityDirectory;
use common\utilities\UtilityFile;
use common\utilities\UtilityUrl;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "system_setting".
 *
 * @property integer $id
 * @property integer $modified_time
 * @property integer $modified_by
 * @property integer $created_by
 * @property integer $created_time
 * @property string $option_key
 * @property string $option_value
 * @property string $lang
 */
class SystemSettingSearch extends SystemSetting {

    public static function getAll($type = 'system_settings_common') {
        $key = self::getKeyFileCache('getAll' . $type);
        $cache = new GlobalFileCache();
        $data = $cache->get($key);
        if (!$data) {
            $where = $type ? ['type' => $type] : [];
            $data = ArrayHelper::map(self::find()->select('option_key,option_value')->where($where)->all(), 'option_key', 'option_value');
            $cache->set($key, $data);
        }
        return $data;
    }

    public function deleteDefaultFileCacheDefault() {
        UtilityFile::deleteFile([
            LINK_PUBLIC . 'partials/json/config.json',
        ]);
        UtilityDirectory::deleteDiretory([
            APPLICATION_PATH . '/cache/file',
        ]);
        $arrayKeyCache = array(
            'findone*',
            'SystemSettingsCommon*',
            'getAll*',
            'getSettingsStr*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }

    public function afterSave($insert, $changedAttributes) {
        $this->deleteDefaultFileCacheDefault();
        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeDelete() {
        $this->deleteDefaultFileCacheDefault();
        return parent::beforeDelete();
    }

    public function replaceWebMain($web_main) {
//        $content = UtilityFile::getFileInWeb(APPLICATION_PATH . '/config.php');
//        $content = preg_replace("/define\('WEB_MAIN', '.*'\);/", "define('WEB_MAIN', '{$web_main}');", $content);
//        UtilityFile::fileputcontents(APPLICATION_PATH . '/config.php', $content);
    }
    
    public static $list_value = [];

    public static function getValue($key) {
        if(!isset(self::$list_value[$key])) {
            $model = self::findOne(['option_key' => $key]);
            self::$list_value[$key] = $model ? $model->option_value : '';
        }
        return self::$list_value[$key];
    }
    public static $id_time_zone;
    public static function time_zone_int() {
        if(self::$id_time_zone === null) {
            if(($id_time_zone = self::getValue('timezone'))) {
                $model = LibTimezonesSearch::findOne((int)$id_time_zone);
                if($model) {
                    self::$id_time_zone = (int)$model->minutes * 60;
                }
            }
            if(!self::$id_time_zone) {
                self::$id_time_zone = 0;
            }
        }
        return self::$id_time_zone;
    }

    public static function setValue($key, $value) {
        $model = self::findOne(['option_key' => $key]);
        if ($model) {
            $model->option_value = $value;
            $model->save(false);
            return true;
        }
        return false;
    }

    public static function array_config($type = 'system_settings_common') {
        $list = self::getAll($type);
        return self::getArrayByObject($list);
    }

    public static function array_configoptimize($type = 'system_settings_common') {
        $list = self::getAll($type);
        return self::getArrayByObjectOptimize($list);
    }

    
    public static function getSettingsStr() {
        $key = self::getKeyFileCache('getSettingsStr' . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($key);
        if (!$data) {
            $where = ['lang' => app()->language, 'type' => 'system_settings_common'];
            $list = self::find()->select('group_concat(option_key) as option_key')->where($where)->asArray()->one();
            $array = explode(',', $list['option_key']);
            $data = "{{system_settings." . implode("}},{{system_settings.", $array) . "}}";
            $cache->set($key, $data);
        }
        return $data;
    }

    public static function getArrayByObject($list, $flag_key_id = false, $w_h = false) {
        $model = new SystemSettingSearch();
        foreach ($list as $key => $value) {
            if (preg_match('/logo|image|favicon|favico$/', $key)) {
                $list[$key] = $model->getimage([], $value);
            }
            if (preg_match('/^\[\{"/', $value)) {
                $value = json_decode($value);
                if (is_array($value)) {
                    foreach ($value as $item) {
                        if (isset($item->swf)) {
                            $item->swf = $model->getimage([], $item->swf);
                        }
                        if (isset($item->image)) {
                            $item->image = $model->getimage([], $item->image);
                        }
                    }
                }
                $list[$key] = $value;
            }
            if ($key == 'logo_footer') {
                foreach ($list[$key] as $item) {
                    if ($item->Logo) {
                        $item->Logo = $model->getimage([], $item->Logo);
                    }
                }
            }
        }
        return $list;
    }

    public static function getArrayByObjectOptimize($list, $flag_key_id = false, $w_h = false) {
        $model = new SystemSettingSearch();
        foreach ($list as $key => $value) {
            if (preg_match('/logo|image|favicon|favico$/', $key)) {
                $list[$key] = $model->getimage([], $value);
            }
            if (preg_match('/^\[\{"/', $value)) {
                $value = json_decode($value);
                if (is_array($value)) {
                    foreach ($value as $item) {
                        if (isset($item->swf)) {
                            $item->swf = $model->getimage([], $item->swf);
                            SettingsImages::optimizeImage($item->swf);
                        }
                        if (isset($item->image)) {
                            $item->image = $model->getimage([], $item->image);
                        SettingsImages::optimizeImage($item->image);
                        }
                    }
                }
                $list[$key] = $value;
            }
            if ($key == 'logo_footer') {
                foreach ($list[$key] as $item) {
                    if ($item->Logo) {
                        $item->Logo = $model->getimage([], $item->Logo);
                        SettingsImages::optimizeImage($item->Logo);
                    }
                }
            }
        }
        return $list;
    }
    
    

    public static $config_common = false;

    public static function getConfigCommon() {
        if (!self::$config_common) {
            $config = self::array_config();
            $config['curl'] = UtilityUrl::realURL();
            $config['og_image'] = $config['logo'];
            $config['og_type'] = $config['meta_title'];
            $get = r()->get();
            if (isset($get['id'])) {
                $id = (int) $get['id'];
                $news = NewsSearch::findOne($id);
                if ($news) {
                    $config['meta_title'] = $news->meta_title;
                    $config['meta_description'] = $news->meta_description;
                    $config['meta_keyword'] = $news->meta_keyword;
                    $config['og_image'] = $news->getimage([], $news->image);
                    $name = $news->name;
                    $alias = str_replace('-',' ',$news->alias);
                    if($news->category_id) {
                        $a = explode(',',$news->category_id);
                        $category_id = $a[count($a) - 1];
                        $category = CategoriesSearch::findOne([$category_id]);
                        if ($category) {
                            $config['og_type'] = $category->name;
                        }
                    }
                    if(!$config['meta_title']) {
                        $config['meta_title'] = $name;
                        if(isset($config['template_news_title'])) {
                            $config['meta_title'] = str_replace(['{name}','{alias}'], [$name,$alias], $config['template_news_title']);
                        }
                    }
                    if(!$config['meta_keyword']) {
                        $config['meta_keyword'] = $name;
                        if(isset($config['template_news_title'])) {
                            $config['meta_keyword'] = str_replace(['{name}','{alias}'], [$name,$alias], $config['template_news_keyword']);
                        }
                    }
                    if(!$config['meta_description']) {
                        $config['meta_description'] = $name;
                        if(isset($config['template_news_description'])) {
                            $config['meta_description'] = str_replace(['{name}','{alias}'], [$name,$alias], $config['template_news_description']);
                        }
                    }
                }
            } else if (isset($get['alias'])) {
                $alias = $get['alias'];
                preg_match('/-h([0-9]+)$/',$alias,$match);
                if(isset($match[1])) {
                    $id = (int) $match[1];
                    $product = ProductSearch::findOne($id);
                    if ($product) {
                        $config['meta_title'] = $product->meta_title;
                        $config['meta_description'] = $product->meta_description;
                        $config['meta_keyword'] = $product->meta_keyword;
                        $config['og_image'] = $product->getimage([], $product->image);
                    } else {
                        $config['meta_title'] = $get['alias'];
                        $config['meta_description'] = $get['alias'];
                        $config['meta_keyword'] = $get['alias'];
                    }
                } else {
                    $category = CategoriesSearch::findOne(['alias' => $get['alias']]);
                    if ($category) {
                        $config['meta_title'] = $category->meta_title.' | Sức khỏe người việt';
                        $config['meta_description'] = $category->meta_description;
                        $config['meta_keyword'] = $category->meta_keyword;
                    } else {
                        $config['meta_title'] = $get['alias'];
                        $config['meta_description'] = $get['alias'];
                        $config['meta_keyword'] = $get['alias'];
                    }
                }
                if (isset($get['page'])) {
                    $config['meta_title'] = ' trang ' . $get['page'];
                }
            }
            self::$config_common = $config;
        }

        return self::$config_common;
    }

    public static function SystemSettingsCommon() {
        $keyCache = self::getKeyFileCache('SystemSettingsCommon');
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = [];
            $query = self::find();
            $query->select('option_key,option_value');
            $query->andFilterWhere(['=', "type", 'system_settings_common']);
            $query->andFilterWhere(['=', "lang", app()->language]);
            $result = self::getArrayByObject(ArrayHelper::map($query->all(), 'option_key', 'option_value'));
            $cache->set($keyCache, $result);
        }
        return $result;
    }
    
}
