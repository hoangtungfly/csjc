<?php

namespace common\models\company;

use common\core\dbConnection\GlobalActiveRecord;
use common\models\admin\SettingsMessageSearch;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property integer $company_category_id
 * @property integer $company_size_id
 * @property string $lang
 * @property integer $company_pbx_id
 * @property string $information_name
 * @property string $information_email
 * @property string $information_mobile
 * @property string $information_phone
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property integer $status
 */
class Company extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'company_category_id', 'company_size_id', 'lang', 'company_pbx_id', 'information_name', 'information_email', 'information_mobile'], 'required','message' => SettingsMessageSearch::t('form','required','{attribute} không được để rỗng.')],
            [['company_category_id', 'company_size_id', 'company_pbx_id', 'created_time', 'created_by', 'modified_time', 'modified_by', 'status'], 'integer'],
            [['name', 'information_name', 'information_email'], 'string', 'max' => 255],
            [['lang', 'information_mobile', 'information_phone'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => SettingsMessageSearch::t('company','name','Tên công ty'),
            'company_category_id' => SettingsMessageSearch::t('company','company_category','Loại công ty'),
            'company_size_id' => SettingsMessageSearch::t('company','company_size','Quy mô công ty'),
            'lang' => SettingsMessageSearch::t('company','lang', 'Ngôn ngữ'),
            'company_pbx_id' => SettingsMessageSearch::t('company', 'company_pbx', 'Loại hình PBX cài đặt trong công ty? (PBX)'),
            'information_name' => SettingsMessageSearch::t('company', 'information_name', 'Họ tên'),
            'information_email' => SettingsMessageSearch::t('company', 'email', 'Email'),
            'information_mobile' => SettingsMessageSearch::t('company', 'information_mobile', 'Di động'),
            'information_phone' => SettingsMessageSearch::t('company', 'phone', 'Số điện thoại cố định'),
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'status' => 'Status',
        ];
    }
}
