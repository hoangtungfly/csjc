<?php

namespace common\models\libs;

use common\core\dbConnection\GlobalActiveRecord;
use common\core\enums\StatusEnum;
use Yii;

/**
 * This is the model class for table "lib_cities".
 *
 * @property string $city_id
 * @property integer $state_id
 * @property string $country_code
 * @property string $city_name
 * @property integer $status
 * @property integer $created_time
 * @property integer $modified_time
 * @property string $created_by
 * @property string $modified_by
 *
 * @property LibCountries $countryCode
 */
class Cities extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lib_cities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_id', 'status', 'created_time', 'modified_time', 'created_by', 'modified_by'], 'integer'],
            [['country_code'], 'string', 'max' => 10],
            [['city_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => 'City ID',
            'state_id' => 'State ID',
            'country_code' => 'Country Code',
            'city_name' => 'City Name',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
        ];
    }
    
    
    /**
     * search category and add to cache
     * @return type
     */
    public function searchFromCacheByStates()
    {
        if(!$this->state_id)
            return null;
        $dependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'sql' => 'SELECT MAX(modified_time) FROM ' .self::tableName() .' WHERE status=' . StatusEnum::STATUS_ACTIVED,
        ]);
        $that = $this;
        $result = self::getDb()->cache(function ($db) use($that) {
            $data = $that->find()->where(['status' => StatusEnum::STATUS_ACTIVED, 'state_id'=>$that->state_id])->orderBy(['city_id'=>SORT_ASC])->asArray()->all();
            $data = $data ? \yii\helpers\ArrayHelper::map($data, 'city_id', 'city_name') : [];
            return $data;
        }, 3600*24*7, $dependency);
        return $result;
    }
}
