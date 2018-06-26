<?php

namespace common\models\settings;

class GetPostSearch extends GetPost {
    public static function insertGetPost() {
        $model = new GetPostSearch();
        $model->get_content = json_encode(r()->get());
        $model->post_content = json_encode(r()->post());
        $model->save();
    }
}