<?php

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;
class AuthEnum extends GlobalEnumBase{
    const ROLE_AUTHOR = 'author';
    const ROLE_EDITOR = 'editor';
    const ROLE_CONTRIBUTOR = 'contributor';
    const AUTH_ITEM_DIVIDE = '.';
    const AUTH_ITEM_ALL = '*';
    const AUTH_ROLE_STRING_DIVIDE = ',';
    const ROLE_LEVEL_LAST = 3;
    const ROLE_ACTION_VIEW = 'view';
    const ROLE_ACTION_UPDATE = 'update';
    const ROLE_ACTION_DELETE = 'delete';
    const ROLE_ACTION_CREATE = 'create';
    const SPECIAL_CONTROLLER_NEWS = 'news';
    const APP_TYPE_LABEL_SUPER_ADMIN = 'Super Admin';
    const APP_TYPE_LABEL_ADMIN = 'Admin';

    const APP_TYPE_SUPER_ADMIN = 3;
    const APP_TYPE_ADMIN = 2;
    
    public static function roleList(){
        return [
            self::ROLE_AUTHOR => self::ROLE_AUTHOR,
            self::ROLE_EDITOR => self::ROLE_EDITOR,
            self::ROLE_CONTRIBUTOR => self::ROLE_CONTRIBUTOR,
        ];
    }
    
    public static function roleListName(){
        return [
            self::APP_TYPE_SUPER_ADMIN => self::APP_TYPE_LABEL_SUPER_ADMIN,
            self::APP_TYPE_ADMIN => self::APP_TYPE_LABEL_ADMIN,
        ];
    }

    public static function roleAction(){
        return [
            self::ROLE_ACTION_VIEW => self::ROLE_ACTION_VIEW,
            self::ROLE_ACTION_UPDATE => self::ROLE_ACTION_UPDATE,
            self::ROLE_ACTION_DELETE => self::ROLE_ACTION_DELETE,
        ];
    }
    
    public static function roleNomalAction(){
        return [
            self::ROLE_ACTION_UPDATE => [
                self::ROLE_EDITOR => self::ROLE_EDITOR,
                self::ROLE_AUTHOR => self::ROLE_AUTHOR,
            ],
            self::ROLE_ACTION_CREATE => [],
            self::ROLE_ACTION_VIEW => [
                self::ROLE_EDITOR => self::ROLE_EDITOR,
                self::ROLE_AUTHOR => self::ROLE_AUTHOR,
            ],
            self::ROLE_ACTION_DELETE => [
                self::ROLE_EDITOR => self::ROLE_EDITOR,
                self::ROLE_AUTHOR => self::ROLE_AUTHOR,
            ],
        ];
    }
    
    public static function roleSpecialAction(){
        return [
            self::SPECIAL_CONTROLLER_NEWS => [
                self::ROLE_ACTION_UPDATE => [
                    self::ROLE_EDITOR => self::ROLE_EDITOR,
                    self::ROLE_AUTHOR => self::ROLE_AUTHOR,
                    self::ROLE_CONTRIBUTOR => self::ROLE_CONTRIBUTOR,
                ],
                self::ROLE_ACTION_CREATE => [
                    self::ROLE_EDITOR => self::ROLE_EDITOR,
                    self::ROLE_CONTRIBUTOR => self::ROLE_CONTRIBUTOR,
                ],
                self::ROLE_ACTION_VIEW => [
                    self::ROLE_EDITOR => self::ROLE_EDITOR,
                    self::ROLE_AUTHOR => self::ROLE_AUTHOR,
                ],
                self::ROLE_ACTION_DELETE => [
                    self::ROLE_EDITOR => self::ROLE_EDITOR,
                    self::ROLE_AUTHOR => self::ROLE_AUTHOR,
                ],
            ]
        ];
    }
}
