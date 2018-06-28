<?php
namespace common\core\enums;
use common\core\enums\base\GlobalEnumBase;

class CategoriesEnum extends GlobalEnumBase{
    const CATEGORY_TYPE_PRODUCT = 0;
    const CATEGORY_TYPE_NEWS = 1;
    const CATEGORY_ALIAS_NOT_ID = 0;
    const CATEGORY_ALIAS_ID = 1;
    const SELECT = 'id,name,alias,description,image,pid,hyperlink,color,category_id';
    
    const CATEGORY_SHOW_LIST = 0;
    const CATEGORY_SHOW_CONTACT = 1;
//    const CATEGORY_SHOW_POST = 2;
//    const CATEGORY_SHOW_VIDEO = 3;
    const CATEGORY_SHOW_ABOUT = 4;
    const CATEGORY_SHOW_COURSE = 5;
    
    public static function showCategoryListLabel() {
        return [
            self::CATEGORY_SHOW_LIST    => 'Category list',
            self::CATEGORY_SHOW_CONTACT    => 'Contact',
            self::CATEGORY_SHOW_COURSE    => 'Course',
//            self::CATEGORY_SHOW_POST    => 'Post',
//            self::CATEGORY_SHOW_VIDEO    => 'Video',
            self::CATEGORY_SHOW_ABOUT    => 'About',
        ];
    }
    
    const LANDINGPAGE_TYPE_MAIN = 'main';
    const LANDINGPAGE_TYPE_SLIDER = 'slider';
    const LANDINGPAGE_TYPE_TEXTLEFT = 'textleft';
    const LANDINGPAGE_TYPE_TEXTRIGHT = 'textright';
    const LANDINGPAGE_TYPE_ONEIMAGE = 'oneimage';
    const LANDINGPAGE_TYPE_TWOIMAGE = 'twoimage';
    const LANDINGPAGE_TYPE_TWOIMAGETEXT = 'twoimagetext';
    const LANDINGPAGE_TYPE_CONTACT = 'contact';
    const LANDINGPAGE_TYPE_REGISTER = 'register';
    const LANDINGPAGE_TYPE_MEMBERSHIP = 'membership';
    const LANDINGPAGE_TYPE_CONTENT = 'content';
    const LANDINGPAGE_TYPE_DOWNLOAD = 'download';
    const LANDINGPAGE_TYPE_FAQ = 'faq';
    const LANDINGPAGE_TYPE_VIDEOLIST = 'videolist';
    const LANDINGPAGE_TYPE_COLUMNS = 'columns';

    
    public static function landingPageType() {
        return [
            self::LANDINGPAGE_TYPE_SLIDER       => 'Slider(repeater) ',
            self::LANDINGPAGE_TYPE_MAIN         => 'Main(background, title, description) ',
            self::LANDINGPAGE_TYPE_ONEIMAGE     => 'One Image ',
            self::LANDINGPAGE_TYPE_TWOIMAGE     => 'Two Image ',
            self::LANDINGPAGE_TYPE_TWOIMAGETEXT => 'Two Image Text',
            self::LANDINGPAGE_TYPE_TEXTLEFT     => 'Text left image right',
            self::LANDINGPAGE_TYPE_TEXTRIGHT    => 'Text right image left',
            self::LANDINGPAGE_TYPE_CONTACT      => 'Contact',
            self::LANDINGPAGE_TYPE_REGISTER     => 'Register Partner',
            self::LANDINGPAGE_TYPE_MEMBERSHIP   => 'Membership',
            self::LANDINGPAGE_TYPE_DOWNLOAD     => 'Download',
            self::LANDINGPAGE_TYPE_CONTENT      => 'Content',
            self::LANDINGPAGE_TYPE_FAQ      	=> 'Faq',
            self::LANDINGPAGE_TYPE_VIDEOLIST    => 'Video List',
            self::LANDINGPAGE_TYPE_COLUMNS      => 'Columns',

       ];
    }
    
    const HOME_ALL = 0;
    const HOME_GUEST = 1;
    const HOME_USER = 2;
    
    public static function getHomeAll() {
        return [
            self::HOME_ALL  => 'All',
            self::HOME_GUEST  => 'Guest',
            self::HOME_USER  => 'User',
        ];
    }
    
    public static function hieuung() {
        return [
            1   => 'On',
            0   => 'Off',
        ];
    }
}