<?php

namespace common\models\settings;

use common\core\dbConnection\GlobalActiveRecord;
use common\utilities\UtilityDateTime;
use common\utilities\UtilityUrl;

/**
 * This is the model class for table "webaccess".
 *
 * @property integer $id
 * @property integer $table
 * @property integer $table_id
 * @property string $pagelink
 * @property string $pagelinkpre
 * @property integer $created_by
 * @property integer $created_time
 * @property string $search_name
 * @property integer $search_engine
 * @property string $ip
 * @property integer $timeindate
 * @property integer $weekday
 * @property integer $month
 * @property integer $year
 * @property string $city
 * @property string $country
 * @property string $loc
 * @property string $org
 * @property string $region
 * @property string $ismobile
 */
class Webaccess extends GlobalActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'webaccess';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['table', 'table_id', 'created_by', 'created_time', 'search_engine', 'timeindate', 'weekday', 'month', 'year'], 'integer'],
            [['ismobile'], 'required'],
            [['pagelink', 'search_name'], 'string', 'max' => 255],
            [['pagelinkpre'], 'string', 'max' => 1000],
            [['ip', 'ismobile'], 'string', 'max' => 20],
            [['city', 'country', 'org', 'region'], 'string', 'max' => 100],
            [['loc'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'table' => 'Table',
            'table_id' => 'Table ID',
            'pagelink' => 'Pagelink',
            'pagelinkpre' => 'Pagelinkpre',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'search_name' => 'Search Name',
            'search_engine' => 'Search Engine',
            'ip' => 'Ip',
            'timeindate' => 'Timeindate',
            'weekday' => 'Weekday',
            'month' => 'Month',
            'year' => 'Year',
            'city' => 'City',
            'country' => 'Country',
            'loc' => 'Loc',
            'org' => 'Org',
            'region' => 'Region',
            'ismobile' => 'Ismobile',
        ];
    }
    
    public static function insertRecord($get = false) {
        $webaccess = new Webaccess();
        $webaccess->ip = isset($get['ip']) ? $get['ip'] : getenv("REMOTE_ADDR");
        $webaccess->city = isset($get['city']) ? $get['city'] : '';
        $webaccess->country = isset($get['country']) ? $get['country'] : '';
        $webaccess->loc = isset($get['loc']) ? $get['loc'] : '';
        $webaccess->org = isset($get['org']) ? $get['org'] : '';
        $webaccess->region = isset($get['region']) ? $get['region'] : '';

        $webaccess->weekday = UtilityDateTime::getWeek();
        $webaccess->month = date('m');
        $webaccess->year = date('Y');

        if (isset($get['table']))
            $webaccess->table = $get['table'];

        if (isset($get['table_id']))
            $webaccess->table_id = $get['table_id'];

        if (isset($get['pagelink']))
            $webaccess->pagelink = $get['pagelink'];

        $pagelinkpre = trim(isset($get['pagelinkpre']) ? $get['pagelinkpre'] : '');

        if ($pagelinkpre != '') {
            $webaccess->webLinkPre($pagelinkpre);
            if($pagelinkpre != "" && str_replace(HTTP_HOST,'',$pagelinkpre) == $pagelinkpre) {
                $webaccess->save(false);
            }
        } else {
            $webaccess->search_engine = 7;
            $webaccess->save(false);
        }
    }
    
    /* BEGIN WEBACCESS */
    const WEBACCESS_GOOGLE = 1;
    const WEBACCESS_YAHOO = 2;
    const WEBACCESS_BAIDU = 3;
    const WEBACCESS_FACEBOOK = 4;
    const WEBACCESS_BING = 5;
    const WEBACCESS_COCCOC = 6;
    const WEBACCESS_OTHER = 10;
    const WEBACCESS_TABLE_PRODUCT = 1;
    const WEBACCESS_TABLE_NEWS = 2;
    const WEBACCESS_TABLE_QUESTION = 8;
    const WEBACCESS_TABLE_CATEGORY = 3;
    const WEBACCESS_TABLE_CART = 4;
    const WEBACCESS_TABLE_CONTACT = 5;
    const WEBACCESS_TABLE_RSS = 6;
    const WEBACCESS_TABLE_SITEMAP = 7;
    /* END WEBACCESS */
    
    public static function statusLabel() {
        return [
            self::WEBACCESS_GOOGLE => 'GOOGLE',
            self::WEBACCESS_YAHOO => 'YAHOO',
            self::WEBACCESS_BAIDU => 'BAIDU',
            self::WEBACCESS_FACEBOOK => 'FACEBOOK',
            self::WEBACCESS_BING => 'BING',
            self::WEBACCESS_COCCOC => 'COCCOC',
            self::WEBACCESS_OTHER => 'OTHER',
            7 => 'In web',
        ];
    }
    
    public function webLinkPre($pagelinkpre){
        $this->pagelinkpre = $pagelinkpre;
        $this->search_engine = self::WEBACCESS_OTHER;
        $engine = array();
        if(preg_match('/google.com|facebook.com|yahoo.com|bing.com|coccoc.com/', $pagelinkpre,$engine)){
            $engine = $engine[0];
            switch ($engine) {
                case 'google.com':
                    $this->search_engine = self::WEBACCESS_GOOGLE;
                    $this->getSearchName('#q=');
                    if($this->search_name == '')
                        $this->getSearchName('?q=');
                    if($this->search_name == '')
                        $this->getSearchName('&q=');
                    break;
                case 'yahoo.com':
                    $this->search_engine = self::WEBACCESS_YAHOO;
                    $this->getSearchName('&p=');
                    break;
                case 'facebook.com':
                    $this->search_engine = self::WEBACCESS_FACEBOOK;
                    break;
                case 'bing.com':
                    $this->search_engine = self::WEBACCESS_BING;
                    $this->getSearchName('&q=');
                    if($this->search_name == '')
                        $this->getSearchName('?q=');
                    if($this->search_name == '')
                        $this->getSearchName('#q=');
                    break;
                case 'coccoc.com':
                    $this->search_engine = self::WEBACCESS_COCCOC;
                    break;
                default:
                    $this->search_engine = 0;
                    break;
            }
        }
    }
    
    public function getSearchName($tt){
        $array = explode($tt,$this->pagelinkpre);
        $search_name = '';
        if(isset($array[1])){
            $array = explode("&",$array[1]);
            $search_name = $array[0];
        }
        $this->search_name = urldecode($search_name);
    }

}
