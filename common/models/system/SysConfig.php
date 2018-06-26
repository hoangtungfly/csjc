<?php

namespace common\models\system;

use common\core\dbConnection\GlobalActiveRecord;
use common\core\model\WriteFile;
use common\utilities\UtilityJson;
use Yii;

/**
 * This is the model class for table "sys_config".
 *
 * @property integer $id
 * @property string $twitter_link
 * @property string $facebook_link
 * @property string $tumbir_link
 * @property string $instagram_link
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $created_time
 * @property integer $modified_time
 * @property string $logo
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $geo_position
 * @property string $geo_region
 * @property string $geo_country
 * @property string $geo_icbm
 * @property string $dc_title
 * @property string $dc_subject
 * @property string $dc_creator
 * @property string $dc_identifier
 * @property string $dc_description
 * @property string $dc_publisher
 * @property string $dc_contributor
 * @property string $dc_date
 * @property string $dc_type
 * @property string $dc_source
 * @property string $dc_relation
 * @property string $dc_coverage
 * @property string $dc_rights
 * @property string $dc_language
 * @property string $google_site_veri
 * @property string $google_analytic
 */
class SysConfig extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_config';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_by', 'modified_by', 'created_time', 'modified_time'], 'integer'],
            [['google_analytic'], 'string'],
            [['twitter_link', 'facebook_link', 'tumbir_link', 'instagram_link', 'logo', 'meta_title', 'meta_keyword', 'meta_description', 'geo_icbm', 'dc_title', 'dc_subject', 'dc_creator', 'dc_identifier', 'dc_description', 'dc_publisher', 'dc_contributor', 'dc_date', 'dc_type', 'dc_source', 'dc_relation', 'dc_coverage', 'dc_rights', 'dc_language', 'google_site_veri'], 'string', 'max' => 255],
            [['geo_position'], 'string', 'max' => 20],
            [['geo_region', 'geo_country'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'twitter_link' => 'Twitter Link',
            'facebook_link' => 'Facebook Link',
            'tumbir_link' => 'Tumbir Link',
            'instagram_link' => 'Instagram Link',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'logo' => 'Logo',
            'meta_title' => 'Meta Title',
            'meta_keyword' => 'Meta Keyword',
            'meta_description' => 'Meta Description',
            'geo_position' => 'Geo Position',
            'geo_region' => 'Geo Region',
            'geo_country' => 'Geo Country',
            'geo_icbm' => 'Geo Icbm',
            'dc_title' => 'Dc Title',
            'dc_subject' => 'Dc Subject',
            'dc_creator' => 'Dc Creator',
            'dc_identifier' => 'Dc Identifier',
            'dc_description' => 'Dc Description',
            'dc_publisher' => 'Dc Publisher',
            'dc_contributor' => 'Dc Contributor',
            'dc_date' => 'Dc Date',
            'dc_type' => 'Dc Type',
            'dc_source' => 'Dc Source',
            'dc_relation' => 'Dc Relation',
            'dc_coverage' => 'Dc Coverage',
            'dc_rights' => 'Dc Rights',
            'dc_language' => 'Dc Language',
            'google_site_veri' => 'Google Site Veri',
            'google_analytic' => 'Google Analytic',
        ];
    }
    
    public function beforeSave($insert) {
        if($this->id == 1) {
            $attributes = $this->getAttributes();
            unset($attributes['id']);
            WriteFile::writeFile('config.json', UtilityJson::printJson($attributes));
        }
        return parent::beforeSave($insert);
    }
}
