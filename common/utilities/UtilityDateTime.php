<?php

/**
 * @author Phongph <phongbro1805@gmail.com>
 * @description process datetime
 * @date 10/29/2013
 */

namespace common\utilities;

use common\models\settings\SystemSettingSearch;
use DateTime;

class UtilityDateTime {

    public $timezone = 0;

    /**
     * constructor
     */
    public function __construct() {
        $this->init();
    }

    /**
     * store object for singeleton design pattern
     * 
     * @var MongoRecord 
     */
    private static $instance;

    /**
     * @return UtilityDateTime
     */
    public static function model() {
        if (!self::$instance) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    /**
     * init
     */
    public function init() {
        $this->timezone = 0;
        //$this->timezone = \common\models\user\UserModel::getTimeZoneOfUser();
    }

    /**
     * get time with timezone
     * @param $datetime
     * @return type
     */
    public function intToTime($date, $format = "d-M-Y H:i:s") {
        $timezone = (float) $this->timezone;
        $date = is_numeric($date) ? $date : strtotime($date);
        return gmdate($format, $date + 3600 * ($timezone + date("0")));
    }

    /**
     * get datetime now
     * @return string
     */
    public function getDateTimeNow($format = "d-M-Y H:i:s") {
        return $this->intToTime(time(), $format);
    }

    /**
     * 
     * @param type $date
     * @param type $format
     * @return type
     */
    public function getDateTime($date, $format = "d-M-Y", $nicetime = false) {
        $format = !$format ? 'd-M-Y' : $format;
        if (UtilityHtmlFormat::isInteger($date)) {
            if ($nicetime === true && $date > (int) strtotime(date('Y-M-d') . ' -2 months')) {
                return self::niceTime($date);
            }
            return $this->intToTime($date, $format);
        } else {
            return null;
        }
    }

    /**
     * get time int
     * @param type $datime
     * @return type
     */
    public static function getIntTime($datime = '', $format = 'd-M-Y') {
        if ($datime == '') {
            return time('now');
        } else {
            return strtotime(self::convertFormatDateString($datime, $format));
        }
    }

    /**
     * convert format date time
     * @param type $datetime
     * @param type $format
     * @return type
     */
    public static function convertFormatDateString($datetime, $format = 'd-M-Y') {
        $m = DateTime::createFromFormat($format, $datetime);
        if ($m) {
            return $m->format($format . ' H:i:s');
        }
        return $datetime;
    }

    public static function getIntFromDate($date, $format = false) {
        if (!$format)
            $format = FORMAT_DATE;
        $array = explode(" ", trim(strtolower($format)));
        $formatDate = $array[0];
        $formatTime = '';
        if (isset($array[1]))
            $formatTime = $array[1];

        $array = array();
        preg_match("/[-\.\/]/", $formatDate, $array);
        if (!isset($array[0]))
            return 0;

        $conma = $array[0];

        $date = explode(" ", trim(strtolower($date)));
        $time = '';
        if (isset($date[1]))
            $time = $date[1];
        $date = $date[0];

        $arrayDate = explode($conma, $date);
        $arrayFormatDate = explode($conma, $formatDate);

        foreach ($arrayFormatDate as $key => $value) {
            switch ($value{0}) {
                case 'd':
                    $day = isset($arrayDate[$key]) ? $arrayDate[$key] : '';
                    break;
                case 'm':
                    $month = isset($arrayDate[$key]) ? $arrayDate[$key] : '';
                    break;
                case 'y':
                    $year = isset($arrayDate[$key]) ? $arrayDate[$key] : '';
                    break;
            }
        }
        $hour = $minute = $second = 0;
        if ($formatTime != '' && $time != "") {
            $arrayTime = explode(':', $time);
            $arrayFormatTime = explode(':', $formatTime);

            foreach ($arrayFormatTime as $key => $value) {
                switch ($value{0}) {
                    case 'h':
                        $hour = isset($arrayTime[$key]) ? $arrayTime[$key] : 0;
                        break;
                    case 'i':
                        $minute = isset($arrayTime[$key]) ? $arrayTime[$key] : 0;
                        break;
                    case 's':
                        $second = isset($arrayTime[$key]) ? $arrayTime[$key] : 0;
                        break;
                }
            }
        }
        if ($day && $month && $year) {
            return mktime($hour, $minute, $second, $month, $day, $year);
        }
        return false;
    }

    /**
     * return date without timezone
     */
    public static function dateNow() {
        return date('d-m-Y H:i:s');
    }

    /**
     * @phongph
     * return microtime
     */
    public static function debugStartTime() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }

    /**
     * @phongph
     * return microtime
     */
    public static function debugEndTime($time_start) {
        $time_end = self::debugStartTime();
        return ($time_end - $time_start);
    }

    /**
     * convert time to nice time
     * @param type $date
     * @return string
     * @author tuna<tunguyenanh@orenj.com>
     */
    public static function niceTime($date, $format = "d-M-Y") {
        if (empty($date)) {
            return "No date provided";
        }

        $periods = array("s", "m", "h", "d", "w", "mo", "y", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        $now = time();
        $unix_date = is_numeric($date) ? $date : strtotime($date);
        $date_driff = (strtotime(date('Y-M-d') . ' -2 months'));

        if ($unix_date < $date_driff) {
            return date($format, $unix_date);
        }

        // check validity of date
        if (empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date
        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "";
        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if ($difference != 1) {
            $periods[$j].= "";
        }

        return $difference . "$periods[$j] {$tense}";
    }

    /**
     * get age from date of birth 
     * @param type $date
     * @return string
     * @author tuna<tunguyenanh@orenj.com>
     */
    public static function getAge($date) {
        if (!is_numeric($date)) {
            return null;
        }
        $age = floor((time() - $date) / 31556926);
        $age = $age > 0 ? $age : null;
        return $age;
    }

    /**
     * @phongph
     * 
     * minus year from current date
     * format date is interger
     * return int year
     */
    public static function minusCurrentYear($date) {
        $curY = time();
        $h = intval(($curY - $date) / (60 * 60 * 24 * 365));
        return $h;
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime2::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    /**
     * get all days in range
     * @param type $first
     * @param type $last
     * @param type $step
     * @param type $format
     * @return type
     */
    public static function dateRange($first, $last, $step = '+1 day', $format = 'm-d-Y') {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
        while ($current <= $last) {

            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    public static function formatDate($time, $format = false) {
        if ($format === false) {
            $format = FORMAT_DATE;
        }
        $time_zone_int = SystemSettingSearch::time_zone_int();
        $time = preg_replace('/(\D)+/', '', $time);
        return $time ? date($format, $time + $time_zone_int) : '';
    }

    public static function formatDateTime($time) {
        $time = preg_replace('/(\D)+/', '', $time);
        $time_zone_int = SystemSettingSearch::time_zone_int();
        return $time ? date(FORMAT_DATE . ' H:i A', $time + $time_zone_int) : '';
    }

    public static function niceTimeFull($date, $format = "d-M-Y") {
        if (empty($date)) {
            return 'N/A';
        }

        $diff = abs(strtotime(date('Y-m-d')) - strtotime($date));
        $day = floor($diff / (60 * 60 * 24));

        $unix_date = is_numeric($date) ? $date : strtotime($date);
        $date_driff = (strtotime(date('Y-M-d') . ' -2 months'));

        if ($unix_date < $date_driff) {
            return date($format, $unix_date);
        }
        //	dump($day);
        //	if($day > 1){ return $date; exit; }
        $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
        $now = time();
        // check validity of date
        if (empty($unix_date)) {
            return "Bad date";
        }
        // is it future date or past date
        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "ago";
        } else {
            return 'now';
            //$difference = $unix_date - $now;
            //$tense = "from now";
        }
        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        return "$difference $periods[$j] {$tense}";
    }

    CONST MONDAY = 0;
    CONST TUESDAY = 1;
    CONST WENESDAY = 2;
    CONST THURSDAY = 3;
    CONST FRIDAY = 4;
    CONST STAURDAY = 5;
    CONST SUNDAY = 6;

    /* lấy thứ tự trong tuần */

    public static function getWeek() {
        $weekday = strtolower(date("l"));
        switch ($weekday) {
            case 'monday': $weekday = self::MONDAY;
                break;
            case 'tuesday': $weekday = self::TUESDAY;
                break;
            case 'wednesday': $weekday = self::WENESDAY;
                break;
            case 'thursday': $weekday = self::THURSDAY;
                break;
            case 'friday': $weekday = self::FRIDAY;
                break;
            case 'saturday': $weekday = self::STAURDAY;
                break;
            case 'sunday': $weekday = self::SUNDAY;
                break;
            default: $weekday = self::MONDAY;
                break;
        }
        return $weekday;
    }

    public static function getDatePayment() {
        return new DateTime(date("Y-m-d", time() + 2 * 3600 + 600));
    }

}