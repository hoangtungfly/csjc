<?php

namespace common\models\notification;

use application\webadmanager\models\CustomersSearch;
use application\webadmanager\models\UserAdmanager;
use common\core\enums\StatusEnum;
use common\core\enums\UserEnum;
use common\models\admin\SettingsMessageSearch;

class NotificationSearch extends Notification {
    
    const NOTIFICATION_TYPE_TRIAL = 'trial';
    const NOTIFICATION_TYPE_PAYMENT_FALSE = 'payment_false';
    const NOTIFICATION_TYPE_MI_FIRST_LOGIN = 'mi_first_login';
    
    public static function getNotificationNotRead($user_id = false) {
        if(!$user_id && !user()->isGuest)  {
            $user_id = user()->id;
        }
        if(!$user_id) {
            return 0;
        }
        return self::find()->where(['user_id' => $user_id,'read' => StatusEnum::STATUS_DEACTIVED])->count();
    }
    
    public static function getListNotifByUserId($user_id = false) {
        if(!$user_id) {
            $user_id = user()->id;
        }
        return self::find()->where(['user_id' => $user_id])->orderBy(['read' => SORT_ASC,'id' => SORT_DESC])->all();
    }
    
    public static function autoNotificationLoginMI($user_id = false) {
        if(!$user_id) {
            $user_id = user()->id;
        }
        $modelUser = UserAdmanager::findOne($user_id);
        $modelCustomer = CustomersSearch::findOne($modelUser->customerid);
        if($modelCustomer && $modelCustomer->istrial = StatusEnum::STATUS_ACTIVED && $modelUser->usertype == UserEnum::ISADMANAGER_INTELLIGENT) {
            if(!($model = NotificationSearch::findOne(['user_id' => $user_id,'type' => self::NOTIFICATION_TYPE_MI_FIRST_LOGIN]))) {
                $expired = strtotime($modelCustomer->expireddate);
                $day = ceil(($expired - time()) / 86400);
                if($day <= 30) {
                    $model = new NotificationSearch();
                    $model->user_id = $user_id;
                    $model->day = 30;
                    $model->type = self::NOTIFICATION_TYPE_MI_FIRST_LOGIN;
                    $model->read = StatusEnum::STATUS_DEACTIVED;
                    $model->name = SettingsMessageSearch::t('notification','mi_first_login_expried','MI first login expired');
                    $model->content = SettingsMessageSearch::t('notification','mi_first_login_message_expried','Please update the card information on form Your information. After 30 day free trial, you must  payment to use tool.');
                    $model->save(false);
                }
            }
        }
    }
    
    public static function autoNotificationLogin($user_id = false) {
        if(!$user_id) {
            $user_id = user()->id;
        }
        $modelUser = UserAdmanager::findOne($user_id);
        $modelCustomer = CustomersSearch::findOne($modelUser->customerid);
        if($modelCustomer) {
            $expired = strtotime($modelCustomer->expireddate);
            $day = ceil(($expired - time()) / 86400);
            if($day < 0) $day = 0;
            if($day > 30) $day = 30;
            if(!($model = NotificationSearch::findOne(['user_id' => $user_id,'day' => $day,'type' => self::NOTIFICATION_TYPE_TRIAL]))) {
                    NotificationSearch::deleteAll('user_id = :user_id AND type = :type',[
                        ':user_id'  => $user_id,
                        'type'      => self::NOTIFICATION_TYPE_TRIAL,
                    ]);
                    if($day <= 10 && $day >= 0) {
                        if($modelCustomer->iscancelplan == StatusEnum::STATUS_DEACTIVED) {
                            $model = new NotificationSearch();
                            $model->user_id = $user_id;
                            $model->day = $day;
                            $model->type = self::NOTIFICATION_TYPE_TRIAL;
                            $model->read = StatusEnum::STATUS_DEACTIVED;
                            $model->name = SettingsMessageSearch::t('notification','name','Trial expired');
                            $day_str = $day == 1 ? '1 day' : $day.' days';
                            $model->content = str_replace('{day}',$day_str,SettingsMessageSearch::t('notification','message','You have {day} to use for free.'));
                            $model->save(false);
                        }
                    }
            }
        }
    }
    
    public static function autoNotificationPaymentFalse($user_id = false) {
        if(!$user_id) {
            $user_id = user()->id;
        }
        $modelUser = UserAdmanager::findOne($user_id);
        $modelCustomer = CustomersSearch::findOne($modelUser->customerid);
        if($modelCustomer) {
            if(!($model = NotificationSearch::findOne(['user_id' => $user_id,'type' => self::NOTIFICATION_TYPE_PAYMENT_FALSE]))) {
                $model = new NotificationSearch();
            }
            $model->user_id = $user_id;
            $model->type = self::NOTIFICATION_TYPE_PAYMENT_FALSE;
            $model->read = StatusEnum::STATUS_DEACTIVED;
            $model->name = SettingsMessageSearch::t('notification','name_payment_false','Payment false');
            $model->content = SettingsMessageSearch::t('notification','content_payment_false','Your transaction was not processed successfully. Please check card information and ensure that your preferred payment details are up-to-date');
            $model->save(false);
        }
    }
    
    public static function updateNotificationRead($user_id = false) {
        if(!$user_id) {
            $user_id = user()->id;
        }
        $list = NotificationSearch::find()->where(['user_id' => $user_id,'read' => StatusEnum::STATUS_DEACTIVED])->all();
        if($list) {
            foreach($list as $key => $item) {
                $item->read = StatusEnum::STATUS_ACTIVED;
                $item->save();
            }
        }
    }
           
}