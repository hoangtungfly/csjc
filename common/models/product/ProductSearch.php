<?php

namespace common\models\product;

use common\core\cache\GlobalFileCache;
use common\core\enums\product\ProductEnum;
use common\core\enums\StatusEnum;
use common\models\category\CategoriesSearch;
use common\models\category\CategoryProduct;
use common\models\order\OrderProductSearch;
use Yii;
use yii\db\ActiveQuery;

class ProductSearch extends Product {

    /**
     * @return ActiveQuery
     */
    public function getOrderProducts() {
        return $this->hasMany(OrderProductSearch::className(), ['product_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert || isset($changedAttributes['category_id'])) {
            CategoryProduct::deleteAll('product_id = ' . $this->id);
            if ($this->category_id != "") {
                $arrayCategoryId = explode(",", $this->category_id);
                $data = [];
                foreach ($arrayCategoryId as $key => $value) {
                    $data[] = [$value, $this->id, time(), user()->id];
                }
                Yii::$app->db->createCommand()->batchInsert(CategoryProduct::tableName(), ['category_id', 'product_id', 'created_time', 'created_by'], $data)->execute();
            }
        }
        $this->deleteDefaultFileCacheDefault();
        parent::afterSave($insert, $changedAttributes);
    }

    public function getCodeById() {
        $code = 'h';
        if (strlen($this->id) < 5) {
            $code .= str_repeat("0", 5 - strlen($this->id)) . $this->id;
        }
        $this->code = $code;
        return $code;
    }

    public function beforeSave($insert) {
        $this->category_id1 = $this->category_id2 = $this->category_id3 = $this->category_id4 = 0;
        $category_id = 0;
        if ($this->category_id != "") {
            $a = explode(',', $this->category_id);
            foreach ($a as $k => $v) {
                $attr = 'category_id' . ($k + 1);
                $this->{$attr} = $v;
                $category_id = $v;
            }
        }
        return parent::beforeSave($insert);
    }

    public function beforeDelete() {
        CategoryProduct::deleteAll('product_id = ' . $this->id);
        $this->deleteDefaultFileCacheDefault();
        return parent::beforeDelete();
    }

    public function deleteDefaultFileCacheDefault() {
        $arrayKeyCache = array(
            'ProductHot*',
            'ProductCategory*',
            'ProductAll*',
            'ProductCategoryCache*',
            'ProductHome*',
            'ProductSale*',
            'ProductPopular*',
            'ProductBestseller*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }

    public static function ProductAll() {
        $keyCache = self::getKeyFileCache('ProductAll');
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = [];
            $query = self::find();
            $query->select(ProductEnum::SELECT);
            $query->andFilterWhere(['=', 'status', StatusEnum::STATUS_ACTIVED]);
            $query->orderBy('id desc');
            $result = self::getArrayByObject($query->all(), false, app()->params['resize']['product']['size']['v2']);
            $cache->set($keyCache, $result);
        }
        return $result;
    }

    public static function ProductHome() {
        $keyCache = self::getKeyFileCache('ProductHome');
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = [];
            $query = self::find();
            $query->select(ProductEnum::SELECT);
            $query->andFilterWhere(['=', 'status', StatusEnum::STATUS_ACTIVED]);
            $query->andFilterWhere(['=', 'home', StatusEnum::STATUS_ACTIVED]);
            $query->orderBy('id desc');
            $result = self::getArrayByObject($query->all(), false, app()->params['resize']['product']['size']['v2']);
            $cache->set($keyCache, $result);
        }
        return $result;
    }

    public static function ProductCategoryCache($category_id = false, $limit = 100, $offset = 0, $w_h = false, $id_in = false) {
        if (!$w_h) {
            $w_h = app()->params['resize']['product']['size']['v2'];
        }
        $keyCache = self::getKeyFileCache('ProductCategoryCache' . $category_id);
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = self::ProductCategory($category_id, $limit, $offset, $w_h, $id_in);
            $cache->set($keyCache, $result);
        }
        return $result;
    }

    public static function ProductCategory($category_id = false, $limit = 100, $offset = 0, $w_h = false, $id_in = false) {
        if (!$w_h) {
            $w_h = app()->params['resize']['product']['size']['v2'];
        }
        $query = self::find();
        $query->select(ProductEnum::SELECT);
        $query->andFilterWhere(['=', "status", StatusEnum::STATUS_ACTIVED]);
        $query->andFilterWhere(['=', "category_product.category_id", $category_id]);
        $query->innerJoin('category_product', 'product.id = category_product.product_id');
        if ($id_in && is_array($id_in) && count($id_in)) {
            $query->andFilterWhere(['not in', "id", $id_in]);
        }
        $query->orderBy('id desc');
        $query->limit($limit);
        $query->offset($offset);
        $result = self::getArrayByObject($query->all(), false, $w_h);
        return $result;
    }

    public static function ProductCategoryTotal($category_id = false, $level = '', $product_id = false) {
        $query = self::find();
        $query->andFilterWhere(['=', "status", StatusEnum::STATUS_ACTIVED]);
        $query->andFilterWhere(['=', "category_product.category_id", $category_id]);
        $query->innerJoin('category_product', 'product.id = category_product.product_id');
        return $query->count();
    }

    public function breakcrumbs() {
        $breadcrumbs = [];
        $arrayCategoryId = explode(',', $this->category_id);
        $category_id = $arrayCategoryId[count($arrayCategoryId) - 1];

        $category = CategoriesSearch::findOne($category_id);
        if ($category) {
            $breadcrumbs = $category->breakcrumbs();
        }
        return $breadcrumbs;
    }

    public static function getObject($item, $w_h = false) {
        if (is_array($item)) {
            $image = isset($item['image']) && $item['image'] != "" ? $item['image'] : '';
        } else if (is_object($item)) {
            $image = $item->image != "" ? $item->image : '';
        }
        $item = parent::getObject($item, $w_h);
        if ($image) {
            $item['image_main'] = self::getimage([], $image);
            $item['image_thumb'] = self::getimage(app()->params['resize']['product']['size']['v3'], $image);
            $item['image_show'] = self::getimage(app()->params['resize']['product']['size']['v4'], $image);
        }
        if (isset($item['images']) && $item['images'] != "") {
            $item['images_main'] = self::getManyImages([], $item['images']);
            $item['images_thumb'] = self::getManyImages(app()->params['resize']['product']['size']['v3'], $item['images']);
            $item['images_show'] = self::getManyImages(app()->params['resize']['product']['size']['v4'], $item['images']);
        }
        return $item;
    }

    public static function ProductSearch($search = '', $limit = 100, $offset = 0, $w_h = false) {
        if (!$w_h) {
            $w_h = app()->params['resize']['product']['size']['v2'];
        }
        $query = self::find();
        $query->select(ProductEnum::SELECT);
        $query->andFilterWhere(['=', "status", StatusEnum::STATUS_ACTIVED]);
        $query->andFilterWhere(['like', "name", $search]);
        $query->orderBy('id desc');
        $query->limit($limit);
        $query->offset($offset);
        $result = self::getArrayByObject($query->all(), false, $w_h);
        return $result;
    }

    public static function ProductSearchTotal($search = '') {
        $query = self::find();
        $query->andFilterWhere(['=', "status", StatusEnum::STATUS_ACTIVED]);
        $query->andFilterWhere(['like', "name", $search]);
        return $query->count();
    }

    public static function ProductHot($limit = 10, $w_h = [75, 75]) {
        $keyCache = self::getKeyFileCache('ProductHot');
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = [];
            $query = self::find();
            $query->select(ProductEnum::SELECT);
            $query->andFilterWhere(['=', "hot", StatusEnum::STATUS_ACTIVED]);
            $query->orderBy('id desc');
            $query->limit($limit);
            $result = self::getArrayByObject($query->all(), [$w_h]);
            $cache->set($keyCache, $result);
        }
        return $result;
    }

    public function renameImageName() {
        $a = explode(',', $this->category_id);
        $category_id = (int) $a[count($a) - 1];
        $modelCategory = CategoriesSearch::findOne($category_id);
        if ($modelCategory) {
            $this->getCodeById();
            $this->name = 'Giày da nam ' . $modelCategory->name . ' ' . $this->code;
            $this->renameImageAll();
        }
        $this->save();
    }

    public static function getProductSale($w_h = [400, 400], $id_in = false) {
        $key = 'ProductSale';
        if ($id_in) {
            $key .= implode('_', $id_in);
        }
        $keyCache = self::getKeyFileCache($key);
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = self::ProductCategory(100096, 10, 0, $w_h, $id_in);
            $cache->set($keyCache, $result);
        }
        return $result;
    }

    public static function getProductPopular($w_h = [88, 88], $id_in = false) {
        $key = 'ProductPopular';
        if ($id_in) {
            $key .= implode('_', $id_in);
        }
        $keyCache = self::getKeyFileCache($key);
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = self::ProductCategory(100095, 3, 0, $w_h, $id_in);
            $cache->set($keyCache, $result);
        }
        return $result;
    }

    public static function getProductBestseller($w_h = [300, 300], $id_in = false) {
        $key = 'ProductBestseller';
        if ($id_in) {
            $key .= implode('_', $id_in);
        }
        $keyCache = self::getKeyFileCache($key);
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = self::ProductCategory(100094, 4, 0, $w_h, $id_in);
            $cache->set($keyCache, $result);
        }
        return $result;
    }

    public static function getProductFeatured($w_h = [300, 300], $id_in = false) {
        $key = 'ProductFeatured';
        if ($id_in) {
            $key .= implode('_', $id_in);
        }
        $keyCache = self::getKeyFileCache($key);
        $cache = new GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = self::ProductCategory(100093, 4, 0, $w_h, $id_in);
            $cache->set($keyCache, $result);
        }
        return $result;
    }
    
    public function searchHome($limit , $offset, $w_h = [300,300]) {
        $query = self::find();
        $query->select(ProductEnum::SELECT);
        if($this->color) {
            $a = explode(',',$this->color);
            $params = [];
            foreach($a as $key => $value) {
                $b[] = "color like :color$key";
                $params[':color'.$key] = '%'.$value.'%';
            }
            $query->andWhere(implode(' or ',$b),$params);
        }
        $query->andFilterWhere(['LIKE', "product.category_id", $this->category_id]);
        $query->andFilterWhere(['LIKE', "product.name", $this->name]);
        $query->andFilterWhere(['LIKE', "product.alias", $this->alias]);
        $query->andFilterWhere(['=', "product.status", StatusEnum::STATUS_ACTIVED]);
//        if($this->category_id) {
            $query->andFilterWhere(['=', "category_product.category_id", $this->category_id]);
            $query->innerJoin('category_product', 'product.id = category_product.product_id');
//        }
        if($this->manufacturer) {
            $query->andFilterWhere(['IN', "product.manufacturer", explode(',',$this->manufacturer)]);
        }
        
        if($this->price) {
            $query->andFilterWhere(['<=', "product.price", $this->price]);
        }
        if($this->price_old) {
            $query->andFilterWhere(['>=', "product.price", $this->price_old]);
        }
        $count = $query->count();
        $query->orderBy($this->modified_time);
        $query->limit($limit);
        $query->offset($offset);
        $result = self::getArrayByObject($query->all(), false, $w_h);
        return [$result,$count];
    }

}
