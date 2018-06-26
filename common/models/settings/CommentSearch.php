<?php

namespace common\models\settings;

class CommentSearch extends Comment {

    public function rules() {
        return [
            [['content', 'name', 'email', 'captcha'], 'required'],
            [['content'], 'string'],
            [['email'], 'email'],
            [['captcha'], 'captcha', 'on' => 'frontend'],
            [['created_time', 'created_by', 'modified_time', 'modified_by', 'did', 'status'], 'integer'],
            [['title', 'name', 'email'], 'string', 'max' => 255],
            [['table_name'], 'string', 'max' => 20]
        ];
    }

}
