<?php

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;

class KylinEnum extends GlobalEnumBase {

    CONST GOOGLE = 0;
    CONST FACEBOOK = 1;
    CONST BING = 4;

    public static function search_engine_type() {
        return [
            self::GOOGLE => 'Google',
            self::FACEBOOK => 'Facebook',
            self::BING => 'Bing',
        ];
    }

    CONST EXPORT = 1;
    CONST DATACHART = 2;
    CONST DATATABLE = 3;

    public static function report_type() {
        return [
            self::EXPORT => 'Export',
            self::DATACHART => 'Datachart',
            self::DATATABLE => 'Datatable',
        ];
    }

    CONST PAID_MARKETING = 0;
    CONST CONVERSION = 1;

    public static function convtrack_type() {
        return [
            self::PAID_MARKETING => 'Paid marketing',
            self::CONVERSION => 'Conversion',
        ];
    }
    
    public static function compare_type() {
        return [
            0   => '',
            1   => 'Compare',
        ];
    }

    public static function AdwordType() {
        return [
            1 => 'Campaign',
            2 => 'AdGroup',
            3 => 'Keyword',
            4 => 'Ads',
            5 => 'Targeting',
            6 => 'Extension',
            7 => 'PositivesKeyword',
            8 => 'NegativesKeyword',
            9 => 'TextAd',
            10 => 'SearchAd',
            11 => 'ListingAd',
            12 => 'DisplayAd',
            13 => 'ImageAd',
            14 => 'WapTextAd',
            15 => 'WapImageAd',
            17 => 'PositivesPlacements',
            18 => 'NegativesPlacements',
            19 => 'Audiences',
            20 => 'PositivesAudiences',
            21 => 'Age',
            22 => 'Gender',
            23 => 'InterestsRemarketing',
            24 => 'Topics',
            25 => 'Placements',
            26 => 'Locations',
            27 => 'PositivesLocations',
            28 => 'NegativesLocations',
            29 => 'LocationExtension',
            30 => 'SiteLink',
            31 => 'DisplayKeywords',
            32 => 'Account',
            33 => 'Company',
            34 => 'Site',
        ];
    }

    public static function AdwordParentType() {
        return [
            '' => '-- Select --',
            1 => 'Campaign',
            2 => 'AdGroup',
            3 => 'Keyword',
            4 => 'Ads',
            5 => 'Targeting',
            6 => 'Extension',
            7 => 'PositivesKeyword',
            8 => 'NegativesKeyword',
            9 => 'TextAd',
            10 => 'SearchAd',
            11 => 'ListingAd',
            12 => 'DisplayAd',
            13 => 'ImageAd',
            14 => 'WapTextAd',
            15 => 'WapImageAd',
            17 => 'PositivesPlacements',
            18 => 'NegativesPlacements',
            19 => 'Audiences',
            20 => 'PositivesAudiences',
            21 => 'Age',
            22 => 'Gender',
            23 => 'InterestsRemarketing',
            24 => 'Topics',
            25 => 'Placements',
            26 => 'Locations',
            27 => 'PositivesLocations',
            28 => 'NegativesLocations',
            29 => 'LocationExtension',
            30 => 'SiteLink',
            31 => 'DisplayKeywords',
            32 => 'Account',
            33 => 'Company',
            34 => 'Site',
        ];
    }

}
