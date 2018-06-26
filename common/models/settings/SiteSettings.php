<?php

namespace common\models\settings;

use common\core\dbConnection\GlobalActiveRecord;
use common\utilities\UtilityGenerate;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * This is the model class for table "site_settings".
 *
 * @property integer $id
 * @property string $site_title
 * @property string $site_logo
 * @property string $site_background
 * @property string $google_analytics
 * @property string $time_zone
 * @property string $site_descriptions
 * @property string $site_skin
 * @property string $admin_email
 * @property string $meta_kerwords
 * @property string $meta_description
 */
class SiteSettings extends \yii\db\ActiveRecord {

    const KEY_CACHE_SITE_SETTING = 'key_cache_sitesetting';
    const TIME_EXPIRE_CACHE_SITE_SETTING = 216000;
    const SITE_ID = 1;

    public static $siteSetting = null;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'site_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['site_title', 'site_logo', 'site_background'], 'required'],
            [['google_analytics', 'meta_kerwords', 'meta_description'], 'string'],
            [['site_title', 'site_background'], 'string', 'max' => 255],
            [['site_logo'], 'string', 'max' => 240],
            [['time_zone'], 'string', 'max' => 100],
            [['site_descriptions', 'site_skin'], 'string', 'max' => 50],
            [['admin_email'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'site_title' => 'Site Title',
            'site_logo' => 'Site Logo',
            'site_background' => 'Site Background',
            'google_analytics' => 'Google Analytics',
            'time_zone' => 'Time Zone',
            'site_descriptions' => 'Site Descriptions',
            'site_skin' => 'Site Skin',
            'admin_email' => 'Admin Email',
            'meta_kerwords' => 'Meta Kerwords',
            'meta_description' => 'Meta Description',
        ];
    }

    /**
     * get site setting
     * @return array site info
     */
    public static function getSiteSettings() {
        if (self::$siteSetting === null) {
            $keycache = self::KEY_CACHE_SITE_SETTING;
            $result = cache_object()->get($keycache);
            if (!$result) {
                $model = self::findOne(self::SITE_ID);
                if ($model) {
                    cache_object()->set($keycache, $model->attributes, self::TIME_EXPIRE_CACHE_SITE_SETTING);
                    $result = $model->attributes;
                }
            }
            if ($result)
                self::$siteSetting = $result;
            else
                self::$siteSetting = array();
        }
        
        return self::$siteSetting;
    }

}
