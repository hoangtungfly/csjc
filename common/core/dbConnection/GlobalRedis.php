<?php

/**
 * Redis uses Predis client as redis php client{@link https://github.com/yiisoft/yii2-redis predis}.
 * Class is used for Yii 
 * @author Phong Pham Hong
 * @date 01.13.2015
 */

namespace common\core\dbConnection;

class GlobalRedis extends \yii\redis\Connection {

    /**
     * convert array to json string when save database
     * convert string to array if value is json string on GET data
     * 
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call($name, $params) {
        $redisCommand = strtoupper(\yii\helpers\Inflector::camel2words($name, false));
        switch ($redisCommand) {
            case 'GET':
            case 'HGET':
                $value = parent::__call($name, $params);
                return self::stringToArray($value);
            case 'SET':
                if (count($params) < 2) {
                    throw new \InvalidArgumentException();
                }
                $params[1] = self::arrayToString($params[1]);
                $params[2] = isset($params[2]) ? intval($params[2]) : null;
                $expire = $params[2];
                if ($expire) {
                    $p[] = $params[1];
                    $p[] = $expire;
                    parent::__call('EXPIRE', $p);
                }
                unset($params[2]);
                break;
            case 'HSET':
                if (count($params) < 3) {
                    throw new \InvalidArgumentException();
                }
                $params[2] = self::arrayToString($params[2]);
                break;
        }
        return parent::__call($name, $params);
    }

    /**
     * Convert array to String
     * Using json_encode method
     * @param type $array
     * @return String $result
     */
    public static function arrayToString($array = array()) {
        return !is_array($array) ? $array : json_encode($array);
    }

    /**
     * Convert string to array
     * @param $string String
     * @return mixed string|array
     */
    public static function stringToArray($string = '') {
        $v = json_decode($string, true);
        return is_array($v) ? $v : $string;
    }

}
