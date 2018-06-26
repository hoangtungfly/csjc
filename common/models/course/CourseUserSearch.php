<?php

namespace common\models\course;

class CourseUserSearch extends CourseUser {
    public function rules()
    {
        $rules = parent::rules();
        return array_merge($rules,[
            [['captcha'], 'captcha', 'on' => 'frontend'],
            [['captcha'], 'required', 'on' => 'frontend'],
        ]);
    }
}