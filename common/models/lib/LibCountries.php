<?php

namespace common\models\lib;

use Yii;

/**
 * This is the model class for table "lib_countries".
 *
 * @property string $country_code
 * @property string $country_name
 * @property string $phone_code
 * @property integer $created_time
 * @property integer $modified_time
 * @property string $created_by
 * @property string $modified_by
 * @property string $keysearch
 */
class LibCountries extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lib_countries';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_code'], 'required'],
            [['created_time', 'modified_time', 'created_by', 'modified_by'], 'integer'],
            [['country_code', 'phone_code'], 'string', 'max' => 10],
            [['country_name', 'keysearch'], 'string', 'max' => 100],
            [['country_code'], 'unique'],
            [['country_name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_code' => 'Country Code',
            'country_name' => 'Country Name',
            'phone_code' => 'Phone Code',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'keysearch' => 'Keysearch',
        ];
    }
}
