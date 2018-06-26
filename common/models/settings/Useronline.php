<?php

namespace common\models\settings;

use common\core\dbConnection\GlobalActiveRecord;
use common\utilities\UtilityDateTime;
use yii\validators\RequiredValidator;

/**
 * This is the model class for table "useronline".
 *
 * @property integer $id
 * @property string $ip
 * @property integer $type
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
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
class Useronline extends GlobalActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'useronline';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['type', 'created_time', 'created_by', 'modified_time', 'modified_by', 'timeindate', 'weekday', 'month', 'year'], 'integer'],
            [['ismobile'], 'required'],
            [['ip', 'ismobile'], 'string', 'max' => 20],
            [['city', 'country', 'org', 'region'], 'string', 'max' => 100],
            [['loc'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'type' => 'Type',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
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

    public static function useronline($type, $get = false) {
        if (isset(app()->session['useronline'])) {
            if (isset($get) && $get['ip'] == $_SERVER['SERVER_ADDR']) {
                return false;
            }
            $pk = app()->session['useronline'];
            $useronline = self::findOne($pk);
            if ($useronline) {
                $timeupdate = $useronline->modified_time > 0 ? $useronline->modified_time : $useronline->created_time;
                if (time() - $timeupdate > 3600) {
                    $flagInsert = true;
                } else {
                    $useronline->modified_time = time();
                    if ($type == $useronline->type) {
                        $flagInsert = false;
                        $useronline->save(false);
                    } else {
                        $flagInsert = true;
                    }
                }
            } else {
                $flagInsert = true;
            }
        } else {
            $flagInsert = true;
        }
        if ($flagInsert) {
            $useronline = new Useronline();
            $useronline->ip = isset($get['ip']) ? $get['ip'] : getenv("REMOTE_ADDR");
            $useronline->city = isset($get['city']) ? $get['city'] : '';
            $useronline->country = isset($get['country']) ? $get['country'] : '';
            $useronline->loc = isset($get['loc']) ? $get['loc'] : '';
            $useronline->org = isset($get['org']) ? $get['org'] : '';
            $useronline->region = isset($get['region']) ? $get['region'] : '';
            $useronline->type = $type;
            $useronline->modified_time = time();
            $useronline->weekday = UtilityDateTime::getWeek();
            $useronline->month = date('m');
            $useronline->year = date('Y');
            $useronline->save(false);
            app()->session['useronline'] = $useronline->id;
        }
    }

}
