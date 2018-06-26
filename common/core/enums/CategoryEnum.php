<?php
namespace common\core\enums;
use common\core\enums\base\GlobalEnumBase;

class CategoryEnum extends GlobalEnumBase{
    const CATEGORY_TYPE_PRODUCT = 0;
    const CATEGORY_TYPE_NEWS = 1;
    const CATEGORY_ALIAS_NOT_ID = 0;
    const CATEGORY_ALIAS_ID = 1;
    const SELECT = 'id,name,alias,description,image,pid,hyperlink,color,category_id';
    
    const CATEGORY_SHOW_LIST = 0;
//    const CATEGORY_SHOW_CONTACT = 1;
//    const CATEGORY_SHOW_POST = 2;
//    const CATEGORY_SHOW_VIDEO = 3;
    const CATEGORY_SHOW_ABOUT = 4;
    
    public static function showCategoryListLabel() {
        return [
            self::CATEGORY_SHOW_LIST    => 'Category list',
//            self::CATEGORY_SHOW_CONTACT    => 'Contact',
//            self::CATEGORY_SHOW_POST    => 'Post',
//            self::CATEGORY_SHOW_VIDEO    => 'Video',
            self::CATEGORY_SHOW_ABOUT    => 'About',
        ];
    }
}
