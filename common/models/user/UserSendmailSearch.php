<?php

namespace common\models\user;

use common\core\enums\StatusEnum;

class UserSendmailSearch extends UserSendmail {
    CONST SEND_EMAIL_CANCEL_FREE = 'send_email_cancel_free';
    CONST SEND_EMAIL_AFTER_7_DAYS = 'send_email_after_7_days';
    public static function checkSendMailByType($user_id, $type, $day_send_email = 0, $subject = '', $content = '') {
        if($day_send_email) {
            $model = self::findOne(['user_id' => $user_id,'type' => $type,'day_send_email' => $day_send_email]);
        } else {
            $model = self::findOne(['user_id' => $user_id,'type' => $type]);
        }
        if(!$model) {
            $model = new UserSendmailSearch();
            $model->user_id = $user_id;
            $model->type = $type;
            if(!$day_send_email) {
                $day_send_email = date('Y-m-d');
            }
            $model->day_send_email = $day_send_email;
            $model->subject = $subject;
            $model->content = $content;
            $model->status = StatusEnum::STATUS_ACTIVED;
            $model->save(false);
            return false;
        } else {
            if($model->status == StatusEnum::STATUS_ACTIVED) {
                return true;
            } else {
                $model->status = StatusEnum::STATUS_ACTIVED;
                $model->save(false);
                return false;
            }
        }
        
    }
}
