<?php

/**
 * Extended ActiveRecord class for whole project
 * 
 * This class is extended from CActiveRecord
 * @author phonghongpham <phongbro1805@gmail.com>
 * @date 13/01/2015
 * @version 1.0
 */

namespace common\core\dbConnection;

use common\core\cache\GlobalFileCache;
use common\core\enums\StatusEnum;
use common\lib\UploadLib;
use common\models\admin\SettingsImages;
use common\models\admin\SettingsMappingSearch;
use common\models\admin\SettingsMessageSearch;
use common\models\news\NewsSearch;
use common\models\product\ProductSearch;
use common\models\user\UserModel;
use common\utilities\UtilityArray;
use common\utilities\UtilityDateTime;
use common\utilities\UtilityFile;
use common\utilities\UtilityFunction;
use common\utilities\UtilityHtmlFormat;
use common\utilities\UtilityUrl;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class GlobalActiveRecord extends ActiveRecord {

    public static $table_name_static;
    public static $time_now_begin;
    public $link_main;
    public $encodeAttributes = array();
    public $trimAttributes = array();
    public $stripTags = array();
    protected $isEncoded = false;
    protected $isStripTaged = false;
    public $captcha;
    public $duration = 0;

    /**
     * store old attributes and keep this value on after save
     * @var array
     */
    public $oldKeepedAttributes = [];

    /**
     * page size array
     */
    public static function arrPageSize() {
        return [
            '5' => 5,
            '10' => 10,
            '15' => 15,
            '20' => 20,
            '25' => 25,
            '50' => 50,
            '100' => 100,
        ];
    }

    /**
     * add rule phone 
     * @param type $attribute
     * @param type $param
     */
    public function rulePhone($attribute) {
        $pat = '/^[\(+]?([0-9]{1,3})\)?[-. ]?([0-9]{3})\)?[-. ]?([0-9]{3,4})[-. ]?([0-9]{0,4})[-. ]?([0-9]{0,4})$/';
        if (!preg_match($pat, $this->$attribute)) {
            $this->addError($attribute, Yii::t('yii', 'rule_phone'));
        }
    }

    /**
     * clear cache data
     * @return boolean
     */
    public function deleteCache() {
        return false;
    }

    /**
     * set cache
     * @return boolen
     */
    public function setCache() {
        return false;
    }

    /**
     * @author phonghongpham
     * beforeSave 
     * when an record is created:
     * - Add created_time if it has not been setted
     * - Add created_by if it has not been setted
     * @return parrent method
     */
    public function beforeSave($insert) {
        if ($this->getIsNewRecord()) {
            $this->setDefaultDataAttributesInsert();
        } else {
            $this->setDefaultDataAttributesUpdate();
        }
        $this->setDefaultDataAttributes();
        $this->oldKeepedAttributes = $this->oldAttributes;
        $this->deleteDefaultFileCache();
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes) {
        if ($this->hasAttribute('tags') && $this->tags != "" && isset($changedAttributes['tags'])) {
            $this->processTags($changedAttributes);
        }
        $this->deleteCache();
        return parent::afterSave($insert, $changedAttributes);
    }

    public function processTags() {
        $tagArrayOld = explode(',', $changedAttributes['tags']);
        if (count($tagArrayOld)) {
            foreach ($tagArrayOld as $key => $tag) {
                $tagArrayOld[$key] = str_replace(',', '', trim($tag));
            }
        }
        $tagArrayOld = array_flip($tagArrayOld);
        $arrayTagNew = [];

        $arrayTag = explode(',', $this->tags);
        $tags = [];
        foreach ($arrayTag as $key => $tag) {
            $tag = str_replace(',', '', trim($tag));
            if (!isset($tagArrayOld[$tag]) && $tag != "") {
                $tags[] = $tag;
            }
        }
        if (count($tags)) {
            $array = ArrayHelper::map(app()->db->createCommand("select name from tags where name in ('" . implode("','", $tags) . "')")->queryAll(), 'name', 'name');
            foreach ($tags as $tag) {
                if (!isset($array[$tag])) {
                    $arrayTagNew[] = [$tag, UtilityHtmlFormat::stripUnicode($tag), time(), !user()->isGuest ? user()->id : 0];
                }
            }
            if (count($arrayTagNew)) {
                app()->db->createCommand()->batchInsert('tags', ['name', 'alias', 'created_time', 'created_by'], $arrayTagNew)->execute();
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function afterDelete() {
        $this->deleteCache();
        parent::afterDelete();
    }

    public function beforeDelete() {
        $this->deleteDefaultFileCache();
        return parent::beforeDelete();
    }

    public function setDefaultDataAttributes() {
        if ($this->hasAttribute('lang') && !$this->lang) {
            $this->lang = app()->language;
        }
        if ($this->hasAttribute('alias') && $this->hasAttribute('name') && $this->name != "") {
            $this->alias = UtilityHtmlFormat::stripUnicode($this->name);
        }
        if ($this->hasAttribute('mainalias') && $this->mainalias != "") {
            $this->mainalias = UtilityHtmlFormat::stripUnicode($this->mainalias);
            $this->alias = $this->mainalias;
        }
        if ($this->hasAttribute('description') && $this->description != "") {
            $this->description = html_entity_decode($this->description);
        }
        if ($this->hasAttribute('tags') && $this->tags != "") {
            $arrayTag = explode(',', str_replace("'", '', $this->tags));
            if (count($arrayTag)) {
                $tags = array();
                foreach ($arrayTag as $tag) {
                    if ($tag != "") {
                        $tags[] = $tag;
                    }
                }
                $this->tags = count($tags) ? implode(',', $tags) : '';
            } else {
                $this->tags = '';
            }
        }
        if ($this->hasAttribute('ismobile') && !$this->ismobile) {
            $this->ismobile = UtilityUrl::isMobile() ? 'mobile' : 'desktop';
        }
        if ($this->hasAttribute('ip') && !$this->ip) {
            $this->ip = getenv("REMOTE_ADDR");
        }
    }

    public function setDefaultDataAttributesInsert() {
        if ($this->hasAttribute('created_time') && !$this->created_time)
            $this->created_time = intval(time());
        if ($this->hasAttribute('created_at') && !$this->created_at)
            $this->created_at = date('Y-m-d H:i:s');
        if ($this->hasAttribute('modified_time') && !$this->modified_time)
            $this->modified_time = intval(time());
        //check Application is not console
        if ($this->hasAttribute('created_by') && isset(Yii::$app->user) && !Yii::$app->user->isGuest && (int) $this->created_by == 0)
            $this->created_by = intval(app()->user->identity->id);
    }

    public function setDefaultDataAttributesUpdate() {
        if ($this->hasAttribute('modified_time'))
            $this->modified_time = intval(time());
        if ($this->hasAttribute('updated_at'))
            $this->updated_at = date('Y-m-d H:i:s');
        //check Application is not console
        if ($this->hasAttribute('updated_by') && isset(Yii::$app->user) && !Yii::$app->user->isGuest && (int) $this->updated_by == 0)
            $this->updated_by = intval(app()->user->identity->id);
        if ($this->hasAttribute('modified_by') && isset(Yii::$app->user) && trim(get_class(Yii::$app)) == 'yii\web\Application' && !Yii::$app->user->isGuest)
            $this->modified_by = isset(app()->user->identity->userLog) ? intval(app()->user->identity->userlog) : intval(app()->user->identity->id);
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * The default implementation raises the {@link onAfterFind} event.
     * You may override this method to do postprocessing after each newly found record is instantiated.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    public function afterFind() {
        return parent::afterFind();
    }

    public function CHtmlEncodeAttributes($attributes = array()) {
        if (!$this->isEncoded) {
            $attributes = $attributes ? $attributes : $this->encodeAttributes;
            foreach ($attributes as $item) {
                $this->$item = Html::encode($this->$item);
            }
            $this->isEncoded = true;
        }
        return $this;
    }

    public function CHtmlDecodeAttributes($attributes = array()) {
        $attributes = $attributes ? $attributes : $this->encodeAttributes;
        foreach ($attributes as $item) {
            $this->$item = Html::decode($this->$item);
        }
        return $this;
    }
    
    public function trimAttributes($attributes = array()) {
        return $this->trimAttrValue($attributes);
    }

    public function trimAttrValue($attributes = array()) {
        $array = $this->attributes ? array_keys($this->attributes) : [];
        $attributes = $attributes ? $attributes : ($this->trimAttributes ? $this->trimAttributes : $array);
        if (is_array($attributes)) {
            foreach ($attributes as $item) {
                if (is_string($this->$item)) {
                    $this->$item = trim($this->$item);
                }
            }
        }
        return $this;
    }

    public function stripTagAttributes($attributes = array(), $tags = []) {
        $attributes = $attributes ? $attributes : $this->stripTags;
        foreach ($attributes as $item) {
            $this->$item = UtilityHtmlFormat::stripTag($this->$item, $tags);
        }
        return $this;
    }

    /**
     * @author Phong Pham Hong
     * 
     * unset all attributes
     * 
     * @param type $names
     * @return GlobalActiveRecord
     */
    public function unsetAttributes($names = null) {
        if ($names === null)
            $names = array_keys($this->getAttributes());
        foreach ($names as $name)
            $this->$name = null;
        $this->clearErrors();
        return $this;
    }

    /**
     * @inheritdoc
     * 
     * @param type $name
     * @param type $value
     * @return GlobalActiveRecord
     */
    public function setAttribute($name, $value) {
        parent::setAttribute($name, is_string($value) ? trim($value) : $value);
        return $this;
    }

    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name => value) to be assigned to the model.
     * @param boolean $safeOnly whether the assignments should only be done to the safe attributes.
     * A safe attribute is one that is associated with a validation rule in the current [[scenario]].
     * @see safeAttributes()
     * @see attributes()
     * @return GlobalActiveRecord
     */
    public function setAttributes($values, $safeOnly = true) {
        parent::setAttributes($values, $safeOnly);
        $this->trimAttrValue();
        return $this;
    }

    public static function findByDomain($domain = '', $condition = array()) {
        $domain = trim($domain);
        if ($domain != '') {
            return self::findOne(array_merge([
                        'domain' => $domain
                                    ], $condition));
        }
        return null;
    }

    public function toStringErrors($has = ', ', $errors = array()) {
        $result = array();
        $errors = $errors ? $errors : $this->getErrors();
        if ($errors) {
            foreach ($errors as $item) {
                if (!is_array($item)) {
                    $result[] = $item;
                } else if ($e = $this->toStringErrors($has, $item)) {
                    $result[] = $e;
                }
            }
        }
        if ($result) {
            return implode($has, $result);
        }
        return null;
    }

    /**
     * 
     * @return type
     */
    public static function getKey() {
        $primaryKey = self::primaryKey();
        if ($primaryKey && $primaryKey[0]) {
            $primaryKey = $primaryKey[0];
        }
        return $primaryKey;
    }

    /**
     * 
     * @param type $query
     * @return \common\core\dbConnection\ActiveDataProvider
     */
    public function searchAdmin($query = false) {
        if (!$query)
            $query = $this->find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $rules = $this->rules();
        $arrayString = array();
        $arrayInteger = array();
        $tableName = $this->tableName();
        foreach ($rules as $key => $item) {
            $array = array();
            if (!is_array($item[0])) {
                $array[] = $item[0];
            } else {
                $array = $item[0];
            }
            switch ($item[1]) {
                case 'string':
                    foreach ($array as $k => $v) {
                        if (isset($this->$v)) {
                            $arrayString[$v] = $this->$v;
                        }
                    }
                    break;
                case 'integer':
                    foreach ($array as $k => $v) {
                        if (isset($this->$v)) {
                            $arrayInteger[$v] = $this->$v;
                        }
                    }
                    break;
                case 'number':
                    foreach ($array as $k => $v) {
                        if (isset($this->$v)) {
                            $arrayInteger[$v] = $this->$v;
                        }
                    }
                    break;
            }
        }
        if (count($arrayInteger) > 0) {
            $arrayDate = ['created_time', 'modified_time', 'start_date', 'end_date', 'birthday'];
            $arrayInteger = UtilityArray::ua($arrayDate, $arrayInteger);

            foreach ($arrayInteger as $k => $v) {
                if($tableName == 'tbl_mam_customers') {
                    if($k == 'usertype') {
                        $query->andFilterWhere(['tbl_um_user' . '.' . $k => $v]);
                    } else {
                        $query->andFilterWhere([$tableName . '.' . $k => $v]);
                    }
                } else {
                    $query->andFilterWhere([$tableName . '.' . $k => $v]);
                }
                
            }

            foreach ($arrayDate as $key => $value) {
                if ($this->hasAttribute($value) && $this->$value != "") {
                    $array = explode(' - ', $this->$value);
                    $start_time = strtotime($array[0]);
                    $end_time = strtotime($array[1] . ' 23:59:59');
                    $query->andFilterWhere(['>=', $tableName . '.' . $value, $start_time]);
                    $query->andFilterWhere(['<=', $tableName . '.' . $value, $end_time]);
                }
            }
        }
        $primaryKey = $this->getKey();
        if (count($arrayString) > 0) {
            foreach ($arrayString as $key => $value) {
                if ($primaryKey == $key)
                    $primaryKey = false;
                if ($value == '') {
                    $query->andWhere($key . " = ''");
                } else {
                    $query->andFilterWhere(['like', $tableName . '.' . $key, $value]);
                }
            }
        }
        if ($primaryKey) {
            $query->andFilterWhere(['like', $tableName . '.' . $primaryKey, $this->$primaryKey]);
        }

        return $dataProvider;
    }

    public function getUser() {
        return $this->hasOne(UserModel::className(), ['user_id' => 'user_id']);
    }

    public function getCreated() {
        return $this->hasOne(UserModel::className(), ['user_id' => 'created_by']);
    }

    public function getModified() {
        return $this->hasOne(UserModel::className(), ['user_id' => 'modifified_by']);
    }

    public function loadAll($data) {
        $class = className(get_class($this));
        if (isset($data[$class])) {
            $dt = $data[$class];
            foreach ($dt as $key => $value) {
                $this->$key = $value;
            }
            $rules = $this->rules();
            foreach ($rules as $rule) {
                if ($rule[1] == 'integer') {
                    foreach ($rule[0] as $value) {
                        if (isset($dt[$value])) {
                            $this->{$value} = (int) $this->{$value};
                        }
                    }
                    break;
                }
            }
            return true;
        }
        return false;
    }

    public function loadAttribute(&$get, $arrayAttributes) {
        if (count($get) > 0) {
            $getget = [];
            foreach ($arrayAttributes as $k => $v) {
                if (isset($get[$k]) && $get[$k]) {
                    $v = preg_replace('/([^\]])+\[|\]/', '', $v);
                    $this->$v = $get[$k];
                    $getget[$k] = $get[$k];
                }
            }
            $get = $getget;
        }
    }

    public static $listInstance = array();

    public static function setInstance($item, $keyInstance = false) {
        if ($item) {
            if (!$keyInstance) {
                $key = self::getKey();
                $keyInstance = $item->$key;
            }
            self::$listInstance[self::tableName()][$keyInstance] = $item;
        }
    }

    public static function getInstance($id) {
        if (self::issetInstance($id)) {
            return self::$listInstance[self::tableName()][$id];
        } else {
            $item = self::findOneKey($id);
            self::setInstance($item);
            return $item;
        }
    }

    public static function issetInstance($id) {
        return isset(self::$listInstance[self::tableName()][$id]) ? true : false;
    }

    public static function unsetInstance($id = false) {
        if ($id)
            unset(self::$listInstance[self::tableName()][$id]);
        else
            self::$listInstance = array();
    }

    /**
     * @inheritdoc
     * @return static|null ActiveRecord instance matching the condition, or `null` if nothing matches.
     */
    public static function findOne($condition) {
        $keyInstance = $condition;
        if (is_array($keyInstance)) {
            $keyInstance = UtilityHtmlFormat::stripUnicode(json_encode($condition));
        }
        if (self::issetInstance($keyInstance)) {
            return self::$listInstance[self::tableName()][$keyInstance];
        } else {
            $item = self::findOneKey($condition);
            self::setInstance($item, $keyInstance);
            return $item;
        }
    }

    /**
     * @inheritdoc
     * @return static|null ActiveRecord instance matching the condition, or `null` if nothing matches.
     */
    public static function findOneKey($condition) {
        $key = 'findone_';
        if (is_array($condition)) {
            $key .= 'array_' . preg_replace('/[^a-zA-Z0-9]+/', '', json_encode($condition));
        } else if (is_int($condition)) {
            $key .= 'string_' . $condition;
        } else {
            $key .= 'string_' . preg_replace('/[^a-zA-Z0-9]+/', '', $condition);
        }
        $key = self::getKeyFileCache($key);
        $cache = new GlobalFileCache();
        $app = $cache->get($key);
        if (!$app) {
            $app = parent::findOne($condition);
            $cache->set($key, $app);
        }
        return $app;
    }

    public static function getValueTokenInput($stringId, $strId = 'id', $strName = 'name', $strKey = false) {
        $result = [];
        $stringId = trim($stringId);
        if ($stringId) {
            $strKey = $strKey ? $strKey : self::getKey();
            $data_obj = self::find()->select([$strId, $strName])->where([$strKey => explode(',', $stringId)])->asArray()->all();
            if (is_array($data_obj) && count($data_obj) > 0) {
                foreach ($data_obj as $key => $item) {
                    $result[$item[$strId]] = $item[$strName];
                }
            }
        }
        return $result;
    }

    public static function getTableName() {
//        if (!self::$table_name_static)
//            self::$table_name_static = preg_replace('/([^a-zA-Z0-9_]+)|(_search)/', '', self::tableName());
//        return self::$table_name_static;
        return preg_replace('/([^a-zA-Z0-9_]+)|(_search)/', '', self::tableName());
    }

    public static function getimage($array = array(), $filename = false, $table_name = false) {
        if (!$table_name) {
            $table_name = self::getTableName();
        }
        if ($filename != "") {
            if (strpos($filename, 'http') === false) {
                $tmp = '';
                if (count($array) && strtolower(pathinfo($filename, PATHINFO_EXTENSION)) != 'gif') {
                    if ($array[1] > 0 && $array[0] > 0) {
                        $tmp = $array[0] . 'x' . $array[1] . '/';
                    } else if ($array[1] > 0) {
                        $tmp = 'h' . $array[1] . '/';
                    } else if ($array[0] > 0) {
                        $tmp = 'w' . $array[0] . '/';
                    }
                } else {
                    $tmp = 'main/';
                }
                if ($filename === false) {
                    $filename = $this->image;
                }
                
                self::resizeimage($table_name, $tmp, $filename);
                return HOST_MEDIA_IMAGES . $table_name . '/' . $tmp . $filename;
            } else {
                return $filename;
            }
        } else {
            return '';
        }
    }

//    public static function resize($data) {
//        $up = new UploadLib(false, true);
//        $up->compressed($data);
//    }
    
    public static function resize($data) {
        $up = new UploadLib(false, true);
        $up->upload($data);
    }

    public static function getManyImages($array = array(), $filename = false, $table_name = false) {
        if (!$table_name) {
            $table_name = self::getTableName();
        }
        $tmp = '';
        if (count($array)) {
            if ($array[1] > 0 && $array[0] > 0) {
                $tmp = $array[0] . 'x' . $array[1] . '/';
            } else if ($array[1] > 0) {
                $tmp = 'h' . $array[1] . '/';
            } else if ($array[0] > 0) {
                $tmp = 'w' . $array[0] . '/';
            }
        } else {
            $tmp = 'main/';
        }
        if ($filename === false) {
            $filename = $this->images;
        }
        $arrayLink = [];
        if ($filename != "") {
            $array = json_decode($filename, true);
            foreach ($array as $k => $item) {
                self::resizeimage($table_name, $tmp, $item['name']);
                $arrayLink[] = [
                    'link' => HOST_MEDIA_IMAGES . $table_name . '/' . $tmp . $item['name'],
                    'name' => $item['name'],
                ];
            }
        }
        return $arrayLink;
    }

    public static function resizeimage($table_name, $tmp, $filename) {
        $link = HOST_MEDIA_RESIZE . $table_name . '/' . $tmp . $filename;
        $link_main = HOST_MEDIA_RESIZE . $table_name . '/main/' . $filename;
        if (HOST_MEDIA_RESIZE && !is_file($link) && is_file($link_main)) {
            self::resize(['table_name' => $table_name, 'tmp' => $tmp, 'filename' => $filename,]);
        }
    }

    public function getfile($filename = false) {
        if (!$filename && isset($this->attributes['file'])) {
            $filename = $this->file;
        }
        if ($filename != "") {
            return HOST_MEDIA_FILES . $this->tableName() . '/main/' . $filename;
        } else {
            return false;
        }
    }

    public function getManyFiles($filename = false) {
        if (!$filename) {
            $filename = $this->files;
        }
        if ($filename != "") {
            $array = json_decode($filename);
            $arrayLink = [];
            $table_name = $this->tableName();
            foreach ($array as $k => $item) {
                $arrayLink[] = [
                    'link' => HOST_MEDIA_FILES . $table_name . '/' . $item->name,
                    'name' => $item->name,
                ];
            }
            return $arrayLink;
        } else {
            return false;
        }
    }

    public static function getNoAva() {
        return HOST_MEDIA_IMAGES . 'no-ava.jpg';
    }

    public static function getAllmenu($id) {
        $list = [];
        if ($id) {
            $model = self::find()->select('id,pid,name')->where('id = ' . $id)->one();
            while ($model) {
                $list[] = $model->id;
                $model = self::find()->select('id,pid,name')->where('id = ' . $model->pid)->one();
            }
        }
        $list[] = 0;
        $list = array_reverse($list);
        return $list;
    }

    public function deleteDefaultFileCache($a = []) {
        $key = $this->getKey();
        $arrayKeyCache = array_merge(array(
            'mapping_*',
            'findone_array_*',
            'findone_string_' . $this->$key,
            'getAll*',
        ),$a);
//        UtilityDirectory::deleteDiretory(APPLICATION_PATH.'/cache/file/');
        $this->deleteCacheFile($arrayKeyCache);
    }

    public function deleteCacheFile($arrayKeyCache = array()) {
        if (!is_array($arrayKeyCache)) {
            $arrayKeyCache = [$arrayKeyCache];
        }
        if (count($arrayKeyCache)) {
            $cache = new GlobalFileCache();
            $tbname = self::tbname();
            foreach ($arrayKeyCache as $k => $key) {
                $key = $tbname . '/' . $key;
                $cache->delete($key);
            }
        }
    }

    public static function tbname($tbname = false) {
        return strtolower(preg_replace('/[^a-zA-Z0-9]+|(search)|(tbl)/', '', $tbname ? $tbname : self::tableName()));
    }

    public static function getKeyFileCache($key) {
        return self::tbname() . '/' . $key;
    }

    public static function getTokenValue($value, $mapping_id, $key_search = false) {
        $mapping_id = (int) $mapping_id;
        $modelMapping = SettingsMappingSearch::findOne($mapping_id);
        if ($modelMapping) {
            $where = $modelMapping->where;

            $key_search = trim($key_search);
            $where_search = false;
            if ($key_search) {
                if ($mapping_id == 46) {
                    $where_search = ' (' . $modelMapping->select_name . " LIKE  '%{$key_search}%' OR firstname LIKE '%{$key_search}%' OR lastname LIKE '%{$key_search}%') ";
                } else {
                    $where_search = $modelMapping->select_name . " LIKE '%{$key_search}%'";
                }
                if ($where) {
                    $where .= ' AND ' . $where_search;
                } else {
                    $where = $where_search;
                }
            }

            $value = trim($value);
            $where_value = false;
            if ($value) {
                $where_value = $modelMapping->select_id . " in  ({$value})";
                if ($where) {
                    $where .= ' AND ' . $where_value;
                } else {
                    $where = $where_value;
                }
            }

            $query = new Query();
            $app = $query
                    ->select($modelMapping->select_id . ',' . $modelMapping->select_name)
                    ->from(strtolower($modelMapping->table_name))
                    ->where($where)
                    ->orderBy($modelMapping->odr)
                    ->limit(20)
                    ->all();
            $result = [];
            if ($app) {
                foreach ($app as $item) {
                    $result[] = [
                        'id' => $item[$modelMapping->select_id],
                        'name' => $item[$modelMapping->select_name],
                        'label' => $item[$modelMapping->select_name],
                    ];
                }
            }
            return json_encode($result);
        }
    }

    public function createUrl($route, $params = []) {
        return UtilityUrl::createUrl($route, $params);
    }

    public function captcha($attribute) {
        if ($this->$attribute && $this->$attribute != session()['__captcha/site/captcha']) {
            $this->addError($attribute, 'The verification code is incorrect.');
        }
    }

    public static function getListArrayByListObject($list) {
        $result = [];
        if ($list) {
            foreach ($list as $key => $item) {
                $result[] = $item->attributes;
            }
        }
        return $result;
    }

    public static function getSelectQuery($class) {
        if ($class) {
            $cl = className($class);
            $classEnum = '\\common\\core\\enums\\';
            $cl1 = $classEnum . $cl . 'Enum';
            if (class_exists($cl1)) {
                return $cl1 . '::SELECT';
            } else {
                $cl = str_replace('Search', '', $cl);
                $cl2 = $classEnum . $cl . 'Enum';
                if (class_exists($cl2)) {
                    return $cl2 . '::SELECT';
                }
            }
        }
        return '';
    }

    public static function getArrayByObject($list, $flag_key_id = false, $w_h = false) {
        $listArray = [];
        if ($list && is_array($list)) {
            $key = 0;
            foreach ($list as $item) {
                $key_array = $flag_key_id ? (is_array($item) ? $item['id'] : $item->id) : $key;
                $listArray[$key_array] = self::getObject($item, $w_h);
                $key++;
            }
        }
        return $listArray;
    }

    public static function getObject($item, $w_h = false) {
        if (!self::$time_now_begin)
            self::$time_now_begin = strtotime(date('Y-m-d'));
        $time = self::$time_now_begin;
        $table_name = self::getTableName();
        if (is_object($item)) {
            $item = $item->attributes;
        }

        if (isset($item['image'])) {
            $item['image_old'] = $item['image'];
            $item['image_main'] = self::getimage([], $item['image'], $table_name);
            if ($item['image'] && is_array($w_h) && count($w_h)) {
                $item['image'] = self::getimage($w_h, $item['image'], $table_name);
            }
        }
        
        if(isset($item['name'])) {
            $item['name_display'] = $item['name'];
            if(isset($item['code']) && $item['code']) {
                $item['name_display'] = $item['name']. ' - ' . $item['code'];
            }
        }

        if (isset($item['image2'])) {
            if ($item['image2'] && is_array($w_h) && count($w_h)) {
                $item['image2'] = self::getimage($w_h, $item['image2'], $table_name);
            } else {
                $item['image2'] = self::getimage([], $item['image2'], $table_name);
            }
        }
        
        if (isset($item['background'])) {
            $item['background'] = self::getimage([], $item['background'], $table_name);
        }
        
        if (array_key_exists('category_id', $item)) {
            $count = 0;
            if($item['category_id'] !== null) {
                $count = substr_count($item['category_id'],',') + 1;
            }
            $item['level'] = $count + 1;
        }
        
        if (isset($item['alias'])) {
            $params = ['alias' => $item['alias'],];
            switch ($table_name) {
                case NewsSearch::tableName():
                    $params['id'] = $item['id'];
                    break;
                case ProductSearch::tableName():
//                    $params['id'] = $item['id'];
                    break;
            }
            $item['link_main'] = UtilityUrl::createUrl('/' . WEBNAME . '/'.($table_name == 'categories' ? 'product' : $table_name).'/index', $params);
        }

        if (isset($item['created_time'])) {
            $item['new'] = $item['created_time'] >= $time ? 1 : 0;
            $item['created_time'] = UtilityDateTime::formatDateTime($item['created_time']);
        }

        if (isset($item['price'])) {
            $item['price'] = $item['price'] ? UtilityHtmlFormat::numberFormatPrice($item['price']) : 'Liên hệ';
        }
        if (isset($item['price_old'])) {
            $item['price_old'] = UtilityHtmlFormat::numberFormatPrice($item['price_old']);
        }
        if (isset($item['size'])) {
            $item['size'] = explode(',', $item['size']);
        }
        return $item;
    }

    public function updateFunction($link, $functionname, $contentFunctionWrite, $type = true) {
        $contentFile = filegetcontents($link);
        if ($contentFile) {
            $contentFunction = UtilityFunction::getFunction($contentFile, $functionname);
            if ($contentFunction) {
                if ($type) {
                    $contentFile = str_replace($contentFunction, trim($contentFunctionWrite), $contentFile);
                    UtilityFile::fileputcontents($link, $contentFile);
                }
            } else {
                $contentFile = UtilityHtmlFormat::insertStringToContentByPosition($contentFile, "\n" . $contentFunctionWrite . "\n", strrpos($contentFile, '}'));
                UtilityFile::fileputcontents($link, $contentFile);
            }
        }
    }

    public function getAllImage($array_one = false, $array_many = false, $type = true) {
        if (!$array_one) {
            $array_one = ['image'];
        }

        if (!$array_many && $this->hasAttribute('images')) {
            $array_many = ['images'];
        }

        if (!is_array($array_one)) {
            $array_one = [$array_one];
        }

        if (!is_array($array_many)) {
            $array_many = [$array_many];
        }

        $array_size = app()->params['resize']['product']['size'];
        if ($type) {
            $array_size = array_merge([[]], $array_size);
        }
        $array_image = [];
        foreach ($array_size as $key => $tmp) {
            if ($array_one) {
                foreach ($array_one as $one) {
                    $array_image[] = str_replace(HTTP_HOST, APPLICATION_PATH, $this->getimage($tmp, $this->$one));
                }
            }
            if ($array_many) {
                foreach ($array_many as $many) {
                    $array = $this->getManyImages($tmp, $this->$many);
                    foreach ($array as $a) {
                        $array_image[] = str_replace(HTTP_HOST, APPLICATION_PATH, $a['link']);
                    }
                }
            }
        }
        return $array_image;
    }

    public function deleteItemAll() {
        $array_size = $this->getAllImage();
        foreach ($array_size as $key => $value) {
            if (is_file($value)) {
                unlink($value);
            }
        }
        $this->delete();
    }

    public function getLinkImagePath($tmp, $image) {
        return str_replace(HTTP_HOST, APPLICATION_PATH, $this->getimage($tmp, $image));
    }

    public function renameFile($oldname, $name, $type = false) {
        if (is_file($oldname)) {
//            $name = UtilityHtmlFormat::stripUnicode($name);
            $newname = pathinfo($oldname, PATHINFO_DIRNAME) . '/' . $name . '.' . pathinfo($oldname, PATHINFO_EXTENSION);
            if ($type) {
                $newname = getFileLinkNew($newname);
            }
            if (!is_file($newname)) {
                rename($oldname, $newname);
            }
            return pathinfo($newname, PATHINFO_BASENAME);
        }
        return $oldname ? pathinfo($oldname, PATHINFO_BASENAME) : '';
    }

    public function renameone($attr, $name) {
        $this->$attr = $this->renameFile($this->getLinkImagePath([], $this->$attr), $name);
    }

    public function renamemany($attr, $name) {
        $array = json_decode($this->$attr, true);
        foreach ($array as $key => $item) {
            $array[$key]['name'] = $this->renameFile($this->getLinkImagePath([], $item['name']), $name.'('.($key + 1).')');
        }
        $this->$attr = json_encode($array);
    }

    public function renameImageAll($name = false, $array_one = false, $array_many = false) {
        if (!$array_one) {
            $array_one = ['image'];
        }

        if (!$array_many && $this->hasAttribute('images')) {
            $array_many = ['images'];
        }

        if (!is_array($array_one)) {
            $array_one = [$array_one];
        }

        if (!is_array($array_many)) {
            $array_many = [$array_many];
        }
        
        if (!$name && $this->hasAttribute('name')) {
            $name = $this->name;
        }
        $name = UtilityHtmlFormat::stripUnicode($name);
        
        $array_size = app()->params['resize']['product']['size'];
        
        foreach($array_one as $one) {
            $image = $this->$one;
            $this->renameone($one, $name);
            foreach ($array_size as $tmp) {
                $this->renameFile($this->getLinkImagePath($tmp, $image), $name);
            }
        }
        
        foreach($array_many as $many) {
            $array = json_decode($this->$many, true);
            $this->renamemany($many, $name);
            
            foreach ($array_size as $tmp) {
                foreach ($array as $key => $item) {
                    $this->renameFile($this->getLinkImagePath($tmp, $item['name']), $name.'('.($key + 1).')');
                }
            }
        }
        
    }
    
    public function converJson($object) {
        $list = $object;
        if(is_object($object) || is_array($object)) {
            $list = is_object($object) ? (array) $object : $object;
            foreach ($list as $key => $value) {
                if(is_string($value)) {
                    $value = trim($value);
                    if(!preg_match('/(<[^>]+>)|(<\/[^>]+>)/',$value)) {
                        $list[$key] = nl2br($value);
                    }
                }
                if (preg_match('/(image|image2|background|logo)$/', $key)) {
                    $list[$key] = $this->getimage([], $value);
                }
                if (preg_match('/^\[\{"/', $value)) {
                    $value = json_decode($value);
                    if (is_array($value)) {
                        foreach ($value as $item) {
                            if (isset($item->swf)) {
                                $item->swf = $this->getimage([], $item->swf);
                            }
                            if (isset($item->image)) {
                                $item->image = $this->getimage([], $item->image);
                            }
                            if (isset($item->background)) {
                                $item->background = $this->getimage([], $item->background);
                            }
                            foreach((array)$item as $k => $v) {
                                if(is_string($v) && !preg_match('/(<[^>]+>)|(<\/[^>]+>)/',$v)) {
                                    $item->$k = nl2br($v);
                                }
                            }
                        }
                    }
                    if(is_string($value)) {
                        $value= trim($value);
                        if(!preg_match('/(<[^>]+>)|(<\/[^>]+>)/',$value)) {
                            $value = nl2br($value);
                        }
                    }
                    $list[$key] = $value;
                }
                if ($key == 'logo_footer') {
                    foreach ($list[$key] as $item) {
                        if ($item->Logo) {
                            $item->Logo = $this->getimage([], $item->Logo);
                        }
                    }
                }
            }
        }
        return $list;
    }

    public function convertJsonObject($string_json) {
        $list = json_decode($string_json);
        if ($list && is_array($list)) {
            foreach ($list as $key => $object) {
                $list[$key] = $this->converJson($object);
            }
        }
        return $list;
    }
    
    public function convertJsonObjectOptimize($string_json) {
        $list = json_decode($string_json);
        if ($list && is_array($list)) {
            foreach ($list as $key => $object) {
                $list[$key] = $this->converJsonOptimize($object);
            }
        }
        return $list;
    }
    
    public function converJsonOptimize($object) {
        $list = $object;
        if(is_object($object) || is_array($object)) {
            $list = is_object($object) ? (array) $object : $object;
            foreach ($list as $key => $value) {
                if(is_string($value)) {
                    $value = trim($value);
                    if(!preg_match('/(<[^>]+>)|(<\/[^>]+>)/',$value)) {
                        $list[$key] = nl2br($value);
                    }
                }
                if (preg_match('/(image|background|logo)$/', $key)) {
                    $list[$key] = $this->getimage([], $value);
                }
                if (preg_match('/^\[\{"/', $value)) {
                    $value = json_decode($value);
                    if (is_array($value)) {
                        foreach ($value as $item) {
                            if (isset($item->swf)) {
                                $item->swf = $this->getimage([], $item->swf);
                                SettingsImages::optimizeImage($item->swf);
                            }
                            if (isset($item->image)) {
                                $item->image = $this->getimage([], $item->image);
                                SettingsImages::optimizeImage($item->image);
                            }
                            if (isset($item->background)) {
                                $item->background = $this->getimage([], $item->background);
                                SettingsImages::optimizeImage($item->background);
                            }
                            foreach((array)$item as $k => $v) {
                                if(is_string($v) && !preg_match('/(<[^>]+>)|(<\/[^>]+>)/',$v)) {
                                    $item->$k = nl2br($v);
                                }
                            }
                        }
                    }
                    if(is_string($value)) {
                        $value= trim($value);
                        if(!preg_match('/(<[^>]+>)|(<\/[^>]+>)/',$value)) {
                            $value = nl2br($value);
                        }
                    }
                    $list[$key] = $value;
                }
                if ($key == 'logo_footer') {
                    foreach ($list[$key] as $item) {
                        if ($item->Logo) {
                            $item->Logo = $this->getimage([], $item->Logo);
                        }
                    }
                }
            }
        }
        return $list;
    }
    
    public function uniqueEmail($attribute, $params) {
        $model = static::findOne([$attribute => $this->$attribute]);
        $key = $this->getKey();
        if($model && ($this->isNewRecord || (!$this->isNewRecord && $model->{$key} != $this->$key))) {
            $this->addError($attribute, SettingsMessageSearch::t('errorForm','form_'.$attribute.'_title','This ' . $attribute . ' has exists!'));
        }
    }
    
    public function agreeFunction($attribute) {
        if($this->$attribute != StatusEnum::STATUS_ACTIVED) {
            $this->addError($attribute, SettingsMessageSearch::t('errorForm',$attribute.'_error_title','Please agree to the terms of service'));
        }
    }

}