<?php

namespace common\models\plan;

use application\webadmanager\models\CustomersSearch;
use application\webadmanager\models\UserAdmanager;
use common\core\enums\StatusEnum;

class UserPlanSearch extends UserPlan {
    const PAYMENT_STATUS_NOT = 0;
    const PAYMENT_STATUS_SUCCESS = 1;
    const PAYMENT_STATUS_FALSE = 2;
    
    
    public static $user_time;
    public static function getPlanUserTime() {
        if(!self::$user_time) {
            $model = self::find()->where(['user_id' => user()->id])->orderBy(['created_time' => SORT_DESC])->one();
            if(!$model) {
                $model = self::insertUserPlan(1);
            }
            $modelCustomer = CustomersSearch::findOne(user()->identity->customerid);
            if($modelCustomer) {
                self::$user_time = ceil((strtotime($modelCustomer->expireddate) - time()) / 86400);
                if(self::$user_time < 0) self::$user_time = 0;
            } else {
                self::$user_time = false;
            }
        }
        return self::$user_time;
    }
    
    public static function getPlantByUserId($user_id = false) {
        if(!$user_id) {
            $user_id = user()->id;
        }
        if($user_id) {
            $model = self::find()->where(['user_id' => (int)$user_id])->orderBy(['created_time' => SORT_DESC])->one();
            return $model;
        }
        return false;
    }
    
    public static function insertUserPlan($plan_id = false, $user_id = false) {
        $model = false;
        if(!user()->isGuest) {
            if(!$user_id) {
                $user_id = user()->id;
            }
            if(!($model = UserPlanSearch::findOne($user_id))) {
                $model = new UserPlanSearch();
            }
            $model->user_id = $user_id;
            $model->plan_id = $plan_id;
            $model->created_time = time();
            $model->end_time = time() + 30 * 86400;
            $modelUser = UserAdmanager::findOne($user_id);
            $customer = CustomersSearch::findOne($modelUser->customerid);
            if($customer->iscancelplan == StatusEnum::STATUS_ACTIVED) {
                $model->status =  StatusEnum::STATUS_DEACTIVED;
            } else {
                $model->status =  StatusEnum::STATUS_ACTIVED;
            }
            
            $model->save();
        }
        return $model;
    }
    
    public static function cancelPlanByUserId($user_id = false) {
        if(!$user_id) {
            $user_id = user()->id;
        }
        if($customer = CustomersSearch::findOne(user()->identity->customerid)) {
            $customer->iscancelplan = StatusEnum::STATUS_ACTIVED;
            $customer->save(false);
            $listUserCustomerId = app()->db2->createCommand("select group_concat(userid) from tbl_um_user where customerid = :customerid",[
                ':customerid'   => user()->identity->customerid,
            ])->queryScalar();
            app()->db->createCommand('update user_plan set status = 0 where user_id in ('.$listUserCustomerId.')')->execute();
        }
    }
}