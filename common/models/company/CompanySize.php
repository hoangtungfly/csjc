<?php

namespace common\models\company;

use Yii;

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
class CompanySize extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_size';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'created_time', 'created_by', 'modified_time', 'modified_by', 'odr'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['lang'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'lang' => 'Lang',
            'odr' => 'Odr',
        ];
    }
}
