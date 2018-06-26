<?php

namespace common\models\plan;

use application\webadmanager\models\CustomersSearch;
use common\core\cache\GlobalFileCache;
use common\core\enums\StatusEnum;
use common\models\admin\SettingsMessageSearch;
use common\utilities\UtilityHtmlFormat;

class PlanSearch extends Plan {
    public static $getListAll;
    public static $getListAllSelect;
    public static function getAll() {
        if(!self::$getListAll) {
            $keyCache = self::getKeyFileCache('getAll');
            $cache = new GlobalFileCache();
            $result = $cache->get($keyCache);
            if (!$result) {
                $result = self::find()->where(['status' => 1])->orderBy('odr')->all();
                $cache->set($keyCache, $result);
            }
            self::$getListAll = $result;
        }
        return self::$getListAll;
    }
    
    
    public static function getAllSelect() {
        if(!self::$getListAllSelect) {
            $list = self::getAll();
            if(is_array($list)) {
                self::$getListAllSelect = [];
                foreach($list as $key => $item) {
                    if($item->price) {
                        self::$getListAllSelect[$item->id] = $item->name;
                    }
                }
            }
        }
        return self::$getListAllSelect;
    }
    
    public static function selectYourPlan($showDefault = false,$plan_price = false) {
        $list = self::getAll();
        if($showDefault) {
            $result = ['' => 'Select your plan'];
        }
        if(is_array($list)) {
            foreach($list as $key => $item) {
                if($item->price) {
                    if($plan_price === false || $item->price >= $plan_price) {
                        $result[$item->id] = $item->name.' ('.$item->displayPriceMonth().')';
                    }
                }
            }
        }
        return $result;
    }
    
    public function displayPriceMonth() {
        return $this->price ? str_replace('{price}',  UtilityHtmlFormat::numberFloatPriceCode($this->price),  SettingsMessageSearch::t('pricing','per_month_title')) : '';
    }
    
    public function displayPriceProfile() {
        return $this->price ? str_replace('{price}',  UtilityHtmlFormat::numberFloat($this->price,0),  SettingsMessageSearch::t('pricing','profile_display_price_title','<sup class="font-size-small">$</sup> {price} <span>/month</span>')) : '';
    }
    
    public function displayMessageFree($day = false,$status = false) {
        if(!$day) {
            $day = (int) UserPlanSearch::getPlanUserTime();
            if($day < 0) $day = 0;
        }
        if($day > 30) $day = 30;
        
        if(!preg_match('/day/',$day)) {
            $day = $day != 1 ? $day.' days' : $day. ' day';
        }
        $customer = CustomersSearch::findOne(user()->identity->customerid);
        if($customer->iscancelplan == StatusEnum::STATUS_DEACTIVED) {
            if($customer->istrial == StatusEnum::STATUS_ACTIVED) {
                $message = SettingsMessageSearch::t('profile','package_message_free','You have <span class="text-red">{day}</span> to use for free.');
            } else {
                $message = SettingsMessageSearch::t('profile','package_message_active','You have <span class="text-red">{day}</span> to use tool.');
            }
            return str_replace('{day}',$day,$message);
        } else {
            return SettingsMessageSearch::t('profile','package_message_cancel');
        }
    }
}
