<?php

namespace common\models\category;

use common\core\cache\GlobalFileCache;
use common\core\enums\CategoriesEnum;
use common\core\enums\CategoryEnum;
use common\core\enums\StatusEnum;
use common\models\category\Categories;
use common\utilities\UtilityArray;
use common\utilities\UtilityDirectory;
use common\utilities\UtilityUrl;

/**
 * CategoriesSearch represents the model behind the search form about `common\models\category\Categories`.
 */
class CategoriesSearch extends Categories {

    public static $listBreakcrumb = array();

    public static function breakcrumb($id) {
        if (!$id)
            return false;
        $key = 1;
        if (!isset(self::$listBreakcrumb[$id])) {
            $listCategory = array();
            while ($id != 0 && $key < 5) {
                $model = self::findOne($id);
                $id = $model->pid;
                $listCategory[] = $model;
                $key++;
            }
            self::$listBreakcrumb[$id] = array_reverse($listCategory);
        }
        return self::$listBreakcrumb[$id];
    }

    public function beforeSave($insert) {
        if (!$insert && $this->getOldAttribute('pid') != $this->pid) {
            $category_id_old = $this->category_id;
            $this->updateCategoryId();
            $category_id_new = $this->category_id;
            $this->updateCategoryIdChild($category_id_old, $category_id_new);
        }
        $this->home = (int) $this->home;
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes) {
        if (!$insert && isset($changedAttributes['pid'])) {
            $this->updateCategoryIdAttr();
        }
        $this->deleteDefaultFileCacheDefault();
        parent::afterSave($insert, $changedAttributes);
    }

    public function updateCategoryIdChild($category_id_old, $category_id_new) {
        app()->db->createCommand("update categories set category_id = REPLACE(category_id,'{$category_id_old}','{$category_id_new}') WHERE category_id LIKE '%{$this->id}%' and id != {$this->id}")->execute();
    }

    public function updateCategoryId() {
        $breadkcrumb = $this->breakcrumb($this->pid);
        if (!$breadkcrumb) {
            $breadkcrumb = [];
        }
        $category_id = [0];
        if(is_array($breadkcrumb) && count($breadkcrumb)) {
            foreach ($breadkcrumb as $item) {
                $category_id[] = $item->id;
            }
        }
        $this->category_id = implode(',', $category_id);;
        return $this->category_id;
    }
    
    public function getLevel() {
        $count = 1;
        if($this->category_id) {
            $count = substr_count($this->category_id,',') + 2;
        }
        return $count;
    }

    public function updateCategoryIdAttr() {
        /* delete id old */

        if ($this->type == 1) {
            $classAttr = 'common\models\category\CategoryNews';
            $table = 'category_news';
            $attr = 'news_id';
            $tableAttr = 'news';
        } else {
            $classAttr = 'common\models\category\CategoryProduct';
            $table = 'category_product';
            $attr = 'product_id';
            $tableAttr = 'product';
        }
        $attr_id_in = app()->db->createCommand("select group_concat(`$attr`) from `$table` where `category_id` = " . $this->id)->queryScalar();
        if ($attr_id_in != "") {
            /* insert id new */
            if ($this->pid) {
                $listBreadcrumbNew = $this->breakcrumb($this->pid);
                $arrayNewsId = explode(',',$attr_id_in);
                foreach ($listBreadcrumbNew as $item) {
                    foreach ($arrayNewsId as $key => $value) {
                        if (!$classAttr::findOne(['category_id' => $item->id, $attr => $value])) {
                            $catgory_attr = new $classAttr();
                            $catgory_attr->category_id = $item->id;
                            $catgory_attr->$attr = $value;
                            $catgory_attr->save();
                            $attr_category_in = app()->db->createCommand("select group_concat(`category_id`) from `$table` where `$attr` = " . $value)->queryScalar();
                            app()->db->createCommand("update `$tableAttr` set `category_id` = '" . $attr_category_in . "' WHERE `id` = " . $value)->execute();
                        }
                    }
                }
            }
        }
    }

    public function beforeDelete() {
        if ($this->type == 1) {
            $classAttr = 'common\models\category\CategoryNews';
            $classTb = 'common\models\news\NewsSearch';
            $table = 'category_news';
            $attr = 'news_id';
            $tableAttr = 'news';
        } else {
            $classAttr = 'common\models\category\CategoryProduct';
            $classTb = 'common\models\product\ProductSearch';
            $table = 'category_product';
            $attr = 'product_id';
            $tableAttr = 'product';
        }
        $list = $classTb::find()->select('id')->where("category_id LIKE '%{$this->id}%'")->all();
        if ($list) {
            app()->db->createCommand("DELETE FROM `$table` WHERE `category_id` IN (select `id` from `categories` where `category_id` like '%{$this->id}%')")->execute();
            foreach ($list as $item) {
                $attr_category_in = app()->db->createCommand("select group_concat(`category_id`) from `$table` where `$attr` = " . $item->id)->queryScalar();
                app()->db->createCommand("update `$tableAttr` set `category_id` = '" . $attr_category_in . "' WHERE `id` = " . $item->id)->execute();
            }
        }
        $this->deleteDefaultFileCacheDefault();
        return parent::beforeDelete();
    }

    public static function getAllCategoryByType($flagId = 0, $type = 0) {
        $keyCache = self::getKeyFileCache('getAllCategoryByType' . $flagId . $type . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $data = [];
            $list = self::find()->select(CategoryEnum::SELECT)->where([
                        'lang' => app()->language,
                        'status' => StatusEnum::STATUS_ACTIVED,
                        'type' => $type])->all();
            $data = self::getArrayByObject($list, $flagId, true);
            $cache->set($keyCache, $data);
        }
        return $data;
    }

    public static function getAllCategoryByTypeAndOnlyParent($flagId = 0, $type = 0) {
        $keyCache = self::getKeyFileCache('getAllCategoryByTypeAndOnlyParent' . $flagId . $type . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $data = [];
            $list = self::find()->select(CategoryEnum::SELECT)->where([
                        'lang' => app()->language,
                        'status' => StatusEnum::STATUS_ACTIVED,
                        'pid'   => 0,
                        'type' => $type])->all();
            $data = self::getArrayByObject($list, $flagId, true);
            $cache->set($keyCache, $data);
        }
        return $data;
    }
    
    public static function getAllCategoryByTypeParent($flagId = 0, $type = 0) {
        return UtilityArray::ArrayPC(self::getAllCategoryByType($flagId, $type));
    }

    public static function getAllCategoryByCategoryid($flagId = 0, $type = 0) {
        $keyCache = self::getKeyFileCache('getAllCategoryByCategoryid' . $flagId . $type . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $data = [];
            $list = self::find()->select(CategoryEnum::SELECT)->where([
                        'lang' => app()->language,
                        'status' => StatusEnum::STATUS_ACTIVED,
                        'type' => $type])->orderBy('category_id')->all();
            $data = self::getArrayByObject($list, $flagId);
            $cache->set($keyCache, $data);
        }
        return $data;
    }

    public static function getAllCategoryRss($flagId = 0) {
        $keyCache = self::getKeyFileCache('getAllCategoryRss' . $flagId . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $data = [];
            $list = self::find()->select(CategoryEnum::SELECT)->where([
                        'lang' => app()->language,
                        'status' => StatusEnum::STATUS_ACTIVED,
                        ])->orderBy('category_id')->all();
            if($list) {
                foreach($list as $key => $item) {
                    $data[] = [
                        'name'      => $item->name,
                        'link_main' => $item->hyperlink ? $item->hyperlink : UtilityUrl::createAbsoluteUrl('/' . WEBNAME . '/category/index', ['alias' => $item->alias]),
                        'link_rss'  => UtilityUrl::createAbsoluteUrl('/site/rss', ['alias' => $item->alias]),
                        'fixed'     => str_replace(',', '--', preg_replace('/[0-9]+/', '', $item->category_id)),
                    ];
                }
            }
            $cache->set($keyCache, $data);
        }
        return $data;
    }

    public static function getMenu($flagId = 0, $type = 'main', $flag_menu = true) {
        $keyCache = self::getKeyFileCache('getmenu' . $type . $flagId . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $list = self::find()->select(CategoryEnum::SELECT)->where([
                        'lang' => app()->language,
                        'status' => StatusEnum::STATUS_ACTIVED,
                        $type . 'menu' => StatusEnum::STATUS_ACTIVED])->orderBy(['pid' => SORT_ASC, $type . 'menu_odr' => SORT_ASC])->all();
            $listArray = self::getArrayByObject($list, $flagId);
            $data = $flag_menu ? UtilityArray::ArrayPC($listArray, 'menu') : $listArray;
            $cache->set($keyCache, $data);
        }
        return $data;
    }

    public static function getMenuLeft($flagId = 0, $type = 'main', $flag_menu = true) {
        $keyCache = self::getKeyFileCache('getmenu' . $type . $flagId . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $list = self::find()->select(CategoryEnum::SELECT)->where([
                        'lang' => app()->language,
                        'status' => StatusEnum::STATUS_ACTIVED,
                        $type . 'menu' => StatusEnum::STATUS_ACTIVED])->orderBy(['pid' => SORT_ASC, $type . 'menu_odr' => SORT_ASC])->all();
            $listArray = self::getArrayByObject($list, $flagId);
            $data = $flag_menu ? UtilityArray::ArrayPC($listArray, 'menu') : $listArray;
            $cache->set($keyCache, $data);
        }
        return $data;
    }

    public static function getHome($flagId = 0) {
        $keyCache = self::getKeyFileCache('gethome' . $flagId . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $list = self::find()->select(CategoryEnum::SELECT)->where([
                        'lang' => app()->language,
                        'status' => StatusEnum::STATUS_ACTIVED,
                        'home' => StatusEnum::STATUS_ACTIVED])->orderBy(['home_odr' => SORT_ASC])->all();
            $data = self::getArrayByObject($list, $flagId);
            $cache->set($keyCache, $data);
        }
        return $data;
    }

    public static function getFooter($flagId = 0) {
        $keyCache = self::getKeyFileCache('getfooter' . $flagId . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $list = self::find()->select(CategoryEnum::SELECT)->where([
                        'lang' => app()->language,
                        'status' => StatusEnum::STATUS_ACTIVED,
                        'footermenu' => StatusEnum::STATUS_ACTIVED])->orderBy(['footermenu_odr' => SORT_ASC])->asArray()->all();
            $data = self::getArrayByObject($list, $flagId);
            $cache->set($keyCache, $data);
        }
        return $data;
    }
    
    public static function getListByShowType($type, $flagId = 0) {
        $keyCache = self::getKeyFileCache('getListByShowType' . $type . $flagId . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $list = self::find()->select(CategoryEnum::SELECT)->where([
                        'lang' => app()->language,
                        'status' => StatusEnum::STATUS_ACTIVED,
                        'show_type' => $type])->orderBy('id desc')->all();
            $data = self::getArrayByObject($list, $flagId);
            $cache->set($keyCache, $data);
        }
        return $data;
    }

    public static function getCategorySlider($flagId = 0) {
        $keyCache = self::getKeyFileCache('getCategorySlider' . app()->language);
        $cache = new GlobalFileCache();
        $data = $cache->get($keyCache);
        if (!$data) {
            $list = self::find()->select(CategoryEnum::SELECT)->where([
                        'lang' => app()->language,
                        'status' => StatusEnum::STATUS_ACTIVED,
                        'slider' => StatusEnum::STATUS_ACTIVED])->limit(1)->orderBy('id desc')->all();
            $data = self::getArrayByObject($list, $flagId);
            $data = isset($data[0]) ? $data[0] : [];
            $cache->set($keyCache, $data);
        }
        return $data;
    }
    
    public function breakcrumbs($flagId = 0) {
        $result = [];
        if($this->pid != 0) {
            $categoryParent = self::findOne($this->pid);
            $result[] = $categoryParent;
            $pid = $this->pid;
        } else {
            $result[] = $this;
            $pid = $this->id;
        }
        $list = self::find()->select(CategoryEnum::SELECT)->where(['pid' => $pid,'mainmenu' => 1])->orderBy('mainmenu_odr')->all();
        if($list) {
            $result = array_merge($result,$list);
        }
        $result = self::getArrayByObject($result, $flagId);
        if($result) {
            foreach($result as $key => $item) {
                if($item['id'] == $this->id) {
                    $result[$key]['active'] = true;
                    break;
                }
            }
        }
        return $result;
    }

    public static $list_main_menu = false;

    public static function MainMenu() {
        if(!self::$list_main_menu) {
            $keyCache = self::getKeyFileCache('MainMenu'.app()->language);
            $cache = new GlobalFileCache();
            $result = $cache->get($keyCache);
            if (!$result || !count($result)) {
                $result = [];
                $lang = \common\utilities\UtilityFunction::getLang();
                $query = self::find();
                $query->select(CategoriesEnum::SELECT);
                $query->andFilterWhere(['=','status',StatusEnum::STATUS_ACTIVED]);
                $query->andFilterWhere(['=','mainmenu',StatusEnum::STATUS_ACTIVED]);
                $query->andFilterWhere(['=','lang',$lang]);
                $query->orderBy('pid,mainmenu_odr');
                $result = UtilityArray::ArrayPC(self::getArrayByObject($query->asArray()->all()));
                $cache->set($keyCache, $result);
            }
            self::$list_main_menu = $result;
        }
        
        
        return self::$list_main_menu;
    }

    public static function FooterMenu() {
        $keyCache = self::getKeyFileCache('FooterMenu'.app()->language);
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = [];
            $lang = \common\utilities\UtilityFunction::getLang();
            $query = self::find();
            $query->select(CategoriesEnum::SELECT);
            $query->andFilterWhere(['=','footermenu',StatusEnum::STATUS_ACTIVED]);
            $query->andFilterWhere(['=','status',StatusEnum::STATUS_ACTIVED]);
            $query->andFilterWhere(['=','lang',$lang]);
            $query->orderBy('footermenu_odr');
            $result = self::getArrayByObject($query->all());
            $cache->set($keyCache, $result);
        }
        return $result;
    }

    public function deleteDefaultFileCacheDefault() {
        UtilityDirectory::deleteDiretory([
            APPLICATION_PATH . '/cache/file',
        ]);
        $arrayKeyCache = array(
            'MenuTop*',
            'MainMenu*',
            'FooterMenu*',
            'MenuRight*',
            'MenuLeft',
            'getmenu*',
            'getall*',
            'gethome*',
            'getfooter*',
            'getAllByCategoryid*',
            'getListAllNews*',
            'getListByShowType*',
            'getCategorySlider*',
            'getAllCategoryByType*',
            'getAllCategoryByCategoryid*',
            'getAllCategoryByTypeAndOnlyParent*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }

    public static function MenuTop() {
        $keyCache = self::getKeyFileCache('MenuTop');
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = [];
            $query = self::find();
            $query->select(CategoriesEnum::SELECT);
            $query->andFilterWhere(['=',"status",StatusEnum::STATUS_ACTIVED]);
            $query->andFilterWhere(['=',"footerlistmenu",StatusEnum::STATUS_ACTIVED]);
            $query->orderBy('footerlistmenu_odr');
            $result = self::getArrayByObject($query->all());
            $cache->set($keyCache, $result);
        }
        return $result;
    }
    
    public static function MenuRight() {
        $keyCache = self::getKeyFileCache('MenuRight');
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = [];
            $query = self::find();
            $query->select(CategoriesEnum::SELECT);
            $query->andFilterWhere(['=',"status",StatusEnum::STATUS_ACTIVED]);
            $query->andFilterWhere(['=',"rightmenu",StatusEnum::STATUS_ACTIVED]);
            $query->orderBy('rightmenu_odr');
            $result = self::getArrayByObject($query->all());
            $cache->set($keyCache, $result);
        }
        return $result;
    }
    
    public static function MenuLeft() {
        $keyCache = self::getKeyFileCache('MenuLeft');
        $cache = new GlobalFileCache;
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = [];
            $query = self::find();
            $query->select(CategoriesEnum::SELECT);
            $query->andFilterWhere(['=',"status",StatusEnum::STATUS_ACTIVED]);
            $query->andFilterWhere(['=',"leftmenu",StatusEnum::STATUS_ACTIVED]);
            $query->orderBy('leftmenu_odr');
            $result = self::getArrayByObject($query->all());
            $cache->set($keyCache, $result);
        }
        return $result;
    }
    public static $listAllNews = false;
    public static function getListAllNews() {
        if(!self::$listAllNews) {
            $keyCache = self::getKeyFileCache('getListAllNews');
            $cache = new GlobalFileCache;
            $result = $cache->get($keyCache);
            if (!$result) {
                $result = [];
                $query = self::find();
                $query->select(CategoriesEnum::SELECT);
                $query->andFilterWhere(['=',"status",StatusEnum::STATUS_ACTIVED]);
                $query->orderBy('id desc');
                $result = self::getArrayByObject($query->all(),true);
                $cache->set($keyCache, $result);
            }
            self::$listAllNews = $result;
        }
        return self::$listAllNews;
    }
    
    public static function getAllByCategoryid($category_id) {
        $keyCache = self::getKeyFileCache('getAllByCategoryid'.$category_id);
        $cache = new GlobalFileCache;
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = [];
            $query = self::find();
            $query->select(CategoriesEnum::SELECT);
            $query->andFilterWhere(['=',"status",StatusEnum::STATUS_ACTIVED]);
            $query->andFilterWhere(['=',"pid",(int)$category_id]);
            $query->orderBy('name desc');
            $result = self::getArrayByObject($query->all());
            $cache->set($keyCache, $result);
        }
        
        return $result;
    }
    
    public static function getCategoryByCategoryidNews($category_id,$id = 0) {
        $ListAllNews = CategoriesSearch::getListAllNews();
        $category_id_array = explode(',',$category_id);
        $length = count($category_id_array) - 1;
        return isset($ListAllNews[$category_id_array[$length]]) && $category_id_array[$length] != $id ? $ListAllNews[$category_id_array[$length]] : false;
    }

}