<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReplyContact
 *
 * @author hanguyenhai
 */
namespace common\models\settings;

use common\models\emailservice\SysEmailQueue;
use yii\base\Model;

class ReplyContact extends Model{
    public $title;
    public $email;
    public $content;
    
    public function rules() {
        return [
            // username and password are required
            [['email', 'content'], 'required'],
            // rememberMe needs to be a boolean
            [['email'], 'email'],
            [['email', 'content', 'title'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'title' => 'Title',
            'content' => 'Content',
            'email' => 'Email',
        ];
    }
    
    public function sendMessages() {
        if ($this->validate()) {
            $mail = new SysEmailQueue();
            $this->content = nl2br($this->content);
            return $mail->sendDirectly($this->email, $this->title, $this->content);
        }
    }
}
