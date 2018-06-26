<?php

namespace common\models\contact;

use common\core\dbConnection\GlobalActiveRecord;
use common\models\admin\SettingsMessageSearch;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $title
 * @property string $content
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $mobile_id
 * @property integer $brand_id
 * @property integer $manufacturer_id
 * @property string $os_version
 */
class Contact extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required','message' => SettingsMessageSearch::t('form','required','{attribute} không được để rỗng.')],
            [['content'], 'string'],
            [['created_time', 'modified_time', 'created_by', 'modified_by', 'mobile_id', 'brand_id', 'manufacturer_id'], 'integer'],
            [['name', 'email', 'phone', 'address', 'title'], 'string', 'max' => 255],
            [['os_version'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => SettingsMessageSearch::t('contact','name','Họ tên'),
            'email' => 'Email',
            'phone' => SettingsMessageSearch::t('contact','phone','Di động'),
            'address' => 'Địa chỉ',
            'title' => SettingsMessageSearch::t('contact','title','Tiêu đề'),
            'content' => SettingsMessageSearch::t('contact','content','Nội dung'),
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'mobile_id' => 'Mobile ID',
            'brand_id' => SettingsMessageSearch::t('contact','brand','Thiết bị di động'),
            'manufacturer_id' => SettingsMessageSearch::t('contact','manufacturer','Nhà cung cấp'),
            'os_version' => 'Os Version',
        ];
    }
}
