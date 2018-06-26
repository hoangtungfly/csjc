<?php

namespace frontend\controllers\restcontrollers;

use common\core\action\GlobalAction;
use common\models\news\NewsSearch;
use common\models\settings\Rating;

class Insertrating extends GlobalAction {

    public function run() {
        $post = r()->post();
        $ip = getenv("REMOTE_ADDR");
        $did = (int) $post['did'];
        $table_name = $post['table_name'];
        $model = Rating::findOne(['ip' => $ip,'did' => $did,'table_name' => $table_name]);
        if (!$model) {
            $model = new Rating();
            $model->point = (int) $post['point'];
            $model->did = $did;
            $model->table_name = $table_name;
            $model->ip = $ip;
            $model->save();
            $modelNews = NewsSearch::findOne($model->did);
            if ($modelNews) {
                return $modelNews->getRating();
            }
            return [];
        } else {
            return [
                'message'   => 'Bạn đã đánh giá bài này rồi!',
            ];
        }
    }

}
