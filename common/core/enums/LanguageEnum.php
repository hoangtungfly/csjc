<?php
namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;
use common\models\admin\SettingsMessageSearch;

class LanguageEnum extends GlobalEnumBase{
    CONST VI = 'vi';
    CONST EN = 'en';
    public static function languageLabel() {
        return [
            self::VI    => SettingsMessageSearch::t('lang','vietnamese'),
            self::EN    => SettingsMessageSearch::t('lang','english'),
        ];
    }
}