<?php

namespace common\models\company;

use common\core\cache\GlobalFileCache;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "company_size".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property string $lang
 * @property integer $odr
 */
class CompanySizeSearch extends CompanySize {
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
    
    public static function getAllDropown() {
        return ArrayHelper::map(self::getAll(), 'id', 'name');
    }
    
    public static function getAllAdmin() {
        $lang = isset($_GET['CompanySearch']['lang']) ? $_GET['CompanySearch']['lang'] : 'vi';
        return ArrayHelper::map(self::getAll($lang),'id','name');
    }
}
