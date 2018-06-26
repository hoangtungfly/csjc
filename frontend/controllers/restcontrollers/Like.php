<?php


namespace frontend\controllers\restcontrollers;

use common\core\action\GlobalAction;
use common\core\enums\StatusEnum;
use common\models\settings\Thank;

class Like extends GlobalAction {

    public function run() {
        $ip = getenv("REMOTE_ADDR");
        $comment_id = (int)$this->getParam('id');
        $status = (int)$this->getParam('status');
        $model = Thank::findOne(['ip' => $ip,'comment_id' => $comment_id]);
        if(!$model) {
            $model = new Thank();
            $model->comment_id = $comment_id;
            $model->status = $status;
            $model->ip = $ip;
            $model->save();
            return ['code' => 200];
        } else {
            return ['code' => 400,'msg' => 'Bạn đã '.($model->status == StatusEnum::STATUS_ACTIVED ? '' : 'không ').'thích bài viết này!'];
        }
        
    }

}
