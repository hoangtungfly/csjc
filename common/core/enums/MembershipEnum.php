<?php

/**
 * define status Ex: actived, deleted...
 * 
 * @author Dung Nguyen Anh
 */

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;
use Yii;

class MembershipEnum extends GlobalEnumBase {
    const CANDIDATE_JOB = 'jobc';
    const CANDIDATE_CV = 'cv';
    const CANDIDATE_ALL = 'all';
    
    const EMPLOYER_JOB = 'job';
    const EMPLOYER_SEARCH = 'seach';
    const EMPLOYER_ALL = 'all';
    const EMPLOYER_LIMIT = 'limit';
    
    public static function getTitleCandidate() {
        return [
            self::CANDIDATE_JOB => Yii::t('membership','candidate_title_job'),
            self::CANDIDATE_CV => Yii::t('membership','candidate_title_cv'),
            self::CANDIDATE_ALL => Yii::t('membership','candidate_title_all'),
        ];
    }
    
    public static function getTitleEmployer() {
        return [
            self::EMPLOYER_JOB => Yii::t('membership','employer_title_job'),
            self::EMPLOYER_SEARCH => Yii::t('membership','employer_title_seach'),
            self::EMPLOYER_ALL => Yii::t('membership','employer_title_all'),
            self::EMPLOYER_LIMIT => Yii::t('membership','candidate_title_all'),
        ];
    }
    
//    public static function getHeaderCandidate() {
//        return [
//            self::CANDIDATE_JOB => Yii::t('membership','candidate_header_job'),
//            self::CANDIDATE_CV => Yii::t('membership','candidate_header_cv'),
//        ];
//    }
//    
//    public static function getHeaderEmployer() {
//        return [
//            self::EMPLOYER_JOB => Yii::t('membership','employer_header_job'),
//            self::EMPLOYER_SEARCH => Yii::t('membership','employer_header_seach'),
//        ];
//    }
}
