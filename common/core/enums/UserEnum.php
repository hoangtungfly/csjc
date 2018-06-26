<?php
namespace common\core\enums;
use common\core\enums\base\GlobalEnumBase;

class UserEnum extends GlobalEnumBase{
    const IS_NEW_USER  = 1;
    const NOT_NEW_USER  = 0;
    const IS_METRIXA_ADMIN = 1;
    const NOT_METRIXA_ADMIN = 0;
    const LOGIN_COUNT = 'login_count';
    const LOGIN_COUNT_MAX = 5;
    const SALT = 'kanga';
    const SESSION_USER_TEMP = 'session_user_temp';
    
    const ISADMANAGER_INTELLIGENT = 0;
    const ISADMANAGER_ADMANAGER = 1;
    
    public static function isAdmanagerLabel() {
        return [
            self::ISADMANAGER_INTELLIGENT   => 'Metrixa Intelligent',
            self::ISADMANAGER_ADMANAGER   => 'Admanager',
        ];
    }
    
    public static function userTypeLabel() {
        return [
            APP_TYPE_USER       => 'User',
            APP_TYPE_CUSTOMERS  => 'Admin',
        ];
    }
}
