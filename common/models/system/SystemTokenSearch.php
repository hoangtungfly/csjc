<?php

namespace common\models\system;

use common\core\enums\SystemTokenEnum;

class SystemTokenSearch extends SystemToken {
    /* type token */

    //type for register user
    protected $randomString = 'AsQ123123ASDS123Avxc123';


    /**
     * @phongph
     * before saving data into database
     */
    public function beforeSave($insert) {
        /* convert create_time_int */
        $this->created_time = !$this->created_time ? time() : $this->created_time;
        $this->expiration = !$this->expiration ? SystemTokenEnum::EXPIRED_TIME_DEFAULT:  $this->expiration;
        return parent::beforeSave($insert);
    }

    /**
     * render a token
     * @param type $salt
     * @return type
     */
    public function createToken($salt = '') {
        return md5($salt . $this->renderUniqueId()) . md5($this->randomString . time());
    }

    /**
     * render an unique ID string
     * @return string
     */
    public function renderUniqueId() {
        usleep(10);
        return uniqid(time());
    }

    /**
     * save token
     * update token if token of object was existed
     * using activeRecord
     */
    public function insertToken() {
        $this->token_key = $this->token_key ? $this->token_key : $this->createToken();
        $this->expiration = $this->expiration ? $this->expiration : SystemTokenEnum::EXPIRED_TIME_DEFAULT;
        /* check data exist */
        self::deleteAll('object_type =:object_type AND object_id =:object_id', [':object_type' => $this->object_type, ':object_id' => $this->object_id]);
        return $this->save();
    }

    /**
     * @phongph
     * validate token
     * @return TokenCommonModel $result
     */
    public function validateToken($token_key) {
        $model = $this->findOne($token_key);
        if ($model) {
            $end = time() - ($model->created_time + $model->expiration);
            if ($end > 0) {
                $this->addError('expiration', 'token expired');
                return false;
            }
            return $model;
        } else
            return false;
    }

}
