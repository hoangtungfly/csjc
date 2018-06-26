<?php

namespace common\models\news;

use common\core\cache\GlobalFileCache;
use common\core\enums\CategoryEnum;
use common\core\enums\NewsEnum;
use common\core\enums\StatusEnum;
use common\models\admin\SettingsImages;
use common\models\category\CategoriesSearch;
use common\models\category\CategoryNews;
use common\utilities\SimpleHtmlDom;
use common\utilities\UtilityFile;
use common\utilities\UtilityUrl;

class NewsSearch extends News {

    public function afterSave($insert, $changedAttributes) {
        if ($insert || isset($changedAttributes['category_id'])) {
            CategoryNews::deleteAll('news_id = ' . $this->id);
            if ($this->category_id != "") {
                $arrayCategoryId = explode(",", $this->category_id);
                $data = [];
                foreach ($arrayCategoryId as $key => $value) {
                    $data[] = [$value, $this->id, time(), user()->id];
                }
                app()->db->createCommand()->batchInsert(CategoryNews::tableName(), ['category_id', 'news_id', 'created_time', 'created_by'], $data)->execute();
            }
        }
        $this->deleteDefaultFileCacheDefault();
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeSave($insert) {
        $this->category_id1 = 0;
        $this->category_id2 = 0;
        if($this->category_id != "") {
            $a = explode(',',$this->category_id);
            $this->category_id1 = $a[0];
            $this->category_id2 = isset($a[1]) ? $a[1] : 0;
        }
        return parent::beforeSave($insert);
    }

    public function beforeDelete() {
        CategoryNews::deleteAll('news_id = ' . $this->id);
        $this->deleteDefaultFileCacheDefault();
        return parent::beforeDelete();
    }
    
    public function deleteDefaultFileCacheDefault() {
        UtilityFile::deleteFile([
            APPLICATION_PATH . '/cache/file/footer.php',
            APPLICATION_PATH . '/cache/file/right.php',
        ]);
        $arrayKeyCache = array(
            'getListNewsNewByLimit*',
            'getListNewsByHotAndLimit*',
            'getListNewsByTypeAndLimit*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }
    
    public static function getListNewsNewByLimit($limit = 10) {
        $keyCache = self::getKeyFileCache('getListNewsNewByLimit');
        $cache = new GlobalFileCache();
        $list = $cache->get($keyCache);
        if(!$list) {
            $list = self::getArrayByObject(self::find()->select(NewsEnum::SELECT)->where([
                'status' => StatusEnum::STATUS_ACTIVED,
            ])->limit($limit)->orderBy(['id' => SORT_DESC])->asArray()->all());
            $cache->set($keyCache,$list);
        }
        return $list;
    }
    
    
    public static function getListNewsByNew($limit = 10, $offset = 0) {
        $query = self::find();
        $query->select(NewsEnum::SELECT);
        $query->andFilterWhere(['=','status',StatusEnum::STATUS_ACTIVED]);
        $count = $query->count();
        $query->limit($limit);
        $query->offset($offset);
        $query->orderBy(['id' => SORT_DESC]);
        $list = self::getArrayByObject($query->asArray()->all());
        return [$count,$list];
    }
    
    public static function getListNewsByHot($limit = 10, $offset) {
        $query = self::find();
        $query->select(NewsEnum::SELECT);
        $query->andFilterWhere(['=','status',StatusEnum::STATUS_ACTIVED]);
        $query->andFilterWhere(['=','hot',StatusEnum::STATUS_ACTIVED]);
        $count = $query->count();
        $query->limit($limit);
        $query->offset($offset);
        $query->orderBy(['id' => SORT_DESC]);
        $list = self::getArrayByObject($query->asArray()->all());
        return [$count,$list];
    }
    
    public static function getTieuDiem(&$list,&$not_category,$category_home,$w_h = false) {
        foreach($not_category as $id) {
            if(isset($category_home[$id])) {
                unset($category_home[$id]);
            }
        }
        $query = self::find();
        $query->select(NewsEnum::SELECT);
        $query->andFilterWhere(['=','status',StatusEnum::STATUS_ACTIVED]);
        $query->andFilterWhere(['IN','category_id1',$category_home]);
        $query->orderBy(['id' => SORT_DESC]);
        $query->limit(1);
        $new = $query->one();
        if($new) {
            $list[] = self::getObject($new,$w_h);
            $not_category[$new->category_id1] = $new->category_id1;
        }
    }
    
    public static function getListTieuDiem($category_home) {
        $list = [];
        $not_category = [];
        self::getTieuDiem($list,$not_category,$category_home,[534,462]);
        self::getTieuDiem($list,$not_category,$category_home,[533,261]);
        self::getTieuDiem($list,$not_category,$category_home,[265,198]);
        self::getTieuDiem($list,$not_category,$category_home,[265,198]);
        return $list;
    }
    
    
    public static function getListNewsByHotAndLimit($limit = 10,$time_start = 0, $time_end = 0) {
        $keyCache = self::getKeyFileCache('getListNewsByHotAndLimit' . $limit . $time_start . $time_end);
        $cache = new GlobalFileCache();
        $list = $cache->get($keyCache);
        if(!$list) {
            $query = self::find();
            $query->select(NewsEnum::SELECT);
            $query->andFilterWhere(['=','status',StatusEnum::STATUS_ACTIVED]);
            $query->andFilterWhere(['=','hot',StatusEnum::STATUS_ACTIVED]);
            if($time_start) {
                $query->andFilterWhere(['>=','created_time',$time_start]);
            }
            if($time_end) {
                $query->andFilterWhere(['<=','created_time',$time_end]);
            }
            $list = self::getArrayByObject($query->limit($limit)->orderBy(['id' => SORT_DESC])->asArray()->all());
            $cache->set($keyCache,$list);
        }
        return $list;
    }
    
    public static function getListNewsByTypeAndLimit($type = 'mainmenu',$limit = 10) {
        $keyCache = self::getKeyFileCache('getListNewsByTypeAndLimit' . $type . $limit);
        $cache = new GlobalFileCache();
        $list = $cache->get($keyCache);
        if(!$list) {
            $list = self::getArrayByObject(self::find()->select(NewsEnum::SELECT)->where([
                'status' => StatusEnum::STATUS_ACTIVED,
                $type   => StatusEnum::STATUS_ACTIVED,
            ])->limit($limit)->orderBy(['id' => SORT_DESC])->asArray()->all());
            $cache->set($keyCache,$list);
        }
        return $list;
    }
    
    
    public static function getListNewsCountByLimit($limit = 10) {
        return self::getArrayByObject(self::find()->select(NewsEnum::SELECT)->where([
            'status' => StatusEnum::STATUS_ACTIVED,
            
        ])->limit($limit)->orderBy(['count' => SORT_DESC,'id' => SORT_DESC])->asArray()->all());
    }
    
    public static function getListByCategoryid($category_id,$level = 1,$limit = 10, $offset = 0, $not_in = false,$w_h = false) {
        $query = self::find()->select(NewsEnum::SELECT);
        if(!$not_in) {
            $query->where([
                'status' => StatusEnum::STATUS_ACTIVED,
                'category_news.category_id' => (int)$category_id,
            ]);
        } else {
            $query->where('status = :status AND category_news.category_id = :category_id AND `id` not in ('.implode(',',$not_in).') ',[
                ':status' => StatusEnum::STATUS_ACTIVED,
                ':category_id' => (int)$category_id,
            ]);
        }
        $result = $query->innerJoin('category_news', '`news`.id = `category_news`.news_id')->limit($limit)->offset($offset)->orderBy(['id' => SORT_DESC])->asArray()->all();
        return self::getArrayByObject($result,false,$w_h);
    }
    
    public static function getTotalByCategoryid($category_id,$level = 1) {
        return self::find()->where([
            'status' => StatusEnum::STATUS_ACTIVED,
            'category_id'.$level => $category_id,
        ])->innerJoinWith('categoryNews')->count();
    }
    
    public static function getListIdByListNews($listNews) {
        $result = [];
        if($listNews && is_array($listNews) && count($listNews)) {
            foreach($listNews as $key => $item) {
                $result[] = $item->id;
            }
        }
        return $result;
    }
    
    public function getCategoryNews() {
        return $this->hasOne(CategoryNews::className(), ['news_id' => 'id']);
    }
    
    public function getTags() {
        $result = [];
        $array = explode(',',$this->tags);
        $count = count($array);
        if($count) {
            foreach($array as $key => $value) {
                $value = trim($value);
                $result[] = [
                    'name'  => $value,
                    'link_main' => UtilityUrl::createUrl('/' . WEBNAME .'/main/tags',['tag' => str_replace(' ','-',$value)]),
                    'comma'    => $key == $count - 1 ? '' : ',',
                ];
            }
        }
        return $result;
    }
    
    public static function updateNews() {
        $listNews = NewsSearch::find()->select('id,cron_id')->where('id > 100 && id < 200')->all();
        foreach($listNews as $news) {
            if($news->cron_id) {
                $object_content_file = SimpleHtmlDom::file_get_html($news->cron_id);
                if ($object_content_file) {
//                    $object_value = $object_content_file->find('.time');
//                    if($object_value) {
//                        $value = $object_value[0]->innertext;
//                        $a = explode(' - ', $value);
//                        $news->created_time = isset($a[1]) ? UtilityDateTime::getIntFromDate($a[1],'d/m/Y H:i') : $news->created_time;
//                    }
//                    $object_value = $object_content_file->find('.keywords a');
//                    if($object_value) {
//                        $result = array();
//                        foreach ($object_value as $item) {
//                            $result[] = isset($item->attr['title']) && $item->attr['title'] ? $item->attr['title'] : strip_tags($item->innertext);
//                        }
//                        $news->tags = implode(',',$result);
//                    }
                    $object_value = $object_content_file->find('.bodytext');
                    if($object_value) {
                        $result = $object_value[0]->innertext;
                        preg_match_all('/src="[^"]+"|src=\'[^\']+\'/', $result, $matches);
                        $arraySearch = [];
                        $arrayReplace = [];
                        if (isset($matches[0])) {
                            foreach ($matches[0] as $value) {
                                $value1 = preg_replace('/src=|"|\'/', "", $value);
                                if($value1 != "" && $value1{0} == '/') {
                                    $array = SettingsImages::updateImageByLink($value1, 'news', 'http://phapluatvavanhoa.com.vn/');
                                    if (isset($array['name']) && $array['name'] != "") {
                                        $arraySearch[] = $value1;
                                        $arrayReplace[] = $news->getimage([], $array['name']);
                                    }
                                }
                            }
                        }
                        $news->content = str_replace($arraySearch, $arrayReplace, $result);
                        if($news->content != "") {
                            $news->save(false);
                        }
                    }
                }
            }
        }
    }
    
    public function getRating() {
        $result = app()->db->createCommand("select avg(point) as point,count(*) as total from rating where table_name = 'news' and did = " . $this->id)->queryOne();
        $result['point_main'] = round($result['point']);
        $result['point'] = number_format($result['point'],2);
        return $result;
        
    }
    
    public static function getListByTag($tag,$limit = 10, $offset = 0, $w_h = false) {
        $query = self::find()->select(NewsEnum::SELECT)->where(
        'status = :status and (name like :tag or tags like :tag or description like :tag)',[
            ':tag' => '%'.$tag.'%',
            ':status' => StatusEnum::STATUS_ACTIVED,
        ]);
        $count = $query->count();
        $list = $query->limit($limit)->offset($offset)->orderBy(['id' => SORT_DESC])->asArray()->all();
        $list = self::getArrayByObject($list, false, $w_h);
        return [$count,$list];
    }
    
    public function breadcrumbs() {
        $breadcrumbs = [];
        $arrayCategoryId = explode(',', $this->category_id);
        $category_id = $arrayCategoryId[count($arrayCategoryId) - 1];

        $category = CategoriesSearch::findOne($category_id);
        return $category->breakcrumbs(CategoryEnum::CATEGORY_ALIAS_NOT_ID);
    }
    
    public function createUrl($route = '', $params = array()) {
        return parent::createUrl('news', ['alias' => $this->alias,'id' => $this->id]);
    }
    
}
