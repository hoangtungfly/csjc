<?php
namespace common\lib;

use common\lib\phpmailler\PHPMailer;

class Dmailer extends PHPMailer {
    public $SMTPDebug = 0;
//    public $Debugoutput = 'html';
    public $CharSet = 'UTF-8';
    public $SMTPAuth = true;
    public $Mailer = 'smtp';
    public $SMTPSecure = 'tls';
    public $Host = 'smtp.gmail.com';
    public $Port = 587; 
    public $FromName = 'Admin';
    public $Username = "choxaydung1@gmail.com";
    public $Password = "choxaydung123456";
//    public $Username = "anhdung17041986@gmail.com";
//    public $Password = "loidan1704";
    
    public function SetFrom($address = 'choxaydung1@gmail.com', $name = 'admin', $auto = 1) {
        return parent::SetFrom($address, $name, $auto);
    }
    
    public function addStr($model,$content) {
        if(isset($model->attributes)) {
            $array1 = array('[url]');
            $array2 = array(HTTP_HOST);
            foreach($model->attributes as $key => $value) {
                $array1[] = '['.$key.']';
                $array2[] = $value;
            }
            $content = str_replace($array1, $array2, $content);
        }
        return $content;
    }
    
    public function setSubject($content,$model = false) {
        if(is_object($model)) {
            $this->Subject = $this->addStr($model, $content);
        } else {
            $this->Subject = $content;
        }
    }
    public function setBody($content, $model = false, $basedir = '', $advanced = false) {
        if(is_object($model)) {
            $content = $this->addStr($model, $content);
        }
        return $this->msgHTML($content, $basedir, $advanced);
    }
    
    public function Send() {
        return parent::Send();
    }
}