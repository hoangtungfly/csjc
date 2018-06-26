<?php

namespace common\models\libs;

use common\core\dbConnection\GlobalActiveRecord;
use common\core\enums\StatusEnum;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "lib_states".
 *
 * @property integer $state_id
 * @property string $state_name
 * @property string $state_code
 * @property string $country_code
 * @property integer $status
 * @property integer $created_time
 * @property integer $modified_time
 * @property string $created_by
 * @property string $modified_by
 *
 * @property LibCountries $countryCode
 */
class States extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lib_states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'created_time', 'modified_time', 'created_by', 'modified_by'], 'integer'],
            [['state_name', 'state_code'], 'string', 'max' => 255],
            [['country_code'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'state_id' => 'State ID',
            'state_name' => 'State Name',
            'state_code' => 'State Code',
            'country_code' => 'Country Code',
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
    public function searchFromCache()
    {
        $dependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'sql' => 'SELECT MAX(modified_time) FROM ' .self::tableName() .' WHERE status=' . StatusEnum::STATUS_ACTIVED,
        ]);
        $that = $this;
        $result = self::getDb()->cache(function ($db) use($that) {
            return $that->find()->where(['status' => StatusEnum::STATUS_ACTIVED])->orderBy(['state_id'=>SORT_ASC])->all();
        }, 3600*24*7, $dependency);
        return $result;
    }
}
