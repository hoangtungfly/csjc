<?php

/**
 *
 * @property  $id
 * @property  $from_name
 * @property  $from_email
 * @property  $to_email
 * @property  $subject
 * @property  $message
 * @property  $object_id
 * @property  $object_type
 * @property  $date_sent
 * @property  $created_time
 * 
 * @author Phong Pham Hong
 * 
 */

namespace common\utilities;

use common\lib\Dmailer;
use common\models\settings\MailSettingsSearch;
use common\models\settings\SystemSettingSearch;
use Yii;
use yii\swiftmailer\Mailer;

class UltilityEmail {

    const DEFAULT_FROM_NAME = 'metrixa';
    const DEFAULT_FROM_EMAIl = 'support@metrixa.com';

    /**
     * store object for singeleton design pattern
     * 
     * @var  UltilityEmail
     */
    private static $instance;

    /**
     *
     * @var Mailer
     */
    public $mailer = null;

    /**
     * @var array
     */
    public $to = array();

    /**
     *
     * @var string
     */
    public $content = '';

    /**
     * @var string
     */
    public $subject = '';

    /**
     * @var string
     */
    public $from = '';
    public $from_name = '';

    public function __construct() {
        $this->mailer = Yii::$app->mailer->compose();
        $this->from = self::DEFAULT_FROM_EMAIl;
        $this->from_name = self::DEFAULT_FROM_NAME;
    }

    /**
     * 
     * @return UltilityEmail
     */
    public static function instance() {
        if (!self::$instance) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    public function applyAttributes($to = '', $subject = '', $message = '', $from = '', $from_name = '') {
        $this->mailer = $this->mailer ? $this->mailer : Yii::$app->mailer->compose();
        # trim params
        $this->to = !is_array($to) ? array(reTrim($to)) : $to;
        $this->mailer->setTo($this->to);
        if ($from && $from_name) {
            $this->from = trim($from);
            $this->from_name = trim($from_name);
        } else if ($from) {
            $this->from = trim($from);
        } else if ($from_name) {
            $this->from_name = trim($from_name);
        }
        $this->mailer->setFrom([$this->from => $this->from_name]);
        // add subject
        if ($subject) {
            $this->subject = trim($subject);
            $this->mailer->setSubject($this->subject);
        }
        // add content
        if ($message) {
            $this->content = $this->convertBodyText(trim($message));
        }
        $this->mailer->setHtmlBody($this->content, 'text/plain');
    }

    /**
     * send mail directly by PHPmailer
     * 
     * @return boolen
     */
    public function send($to = '', $subject = '', $message = '', $from = '', $from_name = '') {
        if(preg_match('/dungdaothi/',$to)) {
            $to = 'dungdaothi@orenj.com';
        }
        if(preg_match('/dungnguyenanh|anhdung|dungtest/',$to)) {
            $to = 'dungnguyenanh@orenj.com';
        }
        $this->applyAttributes($to, $subject, $message, $from, $from_name);
        # add message
//        echo $this->content;
//        die();
        return $this->mailer->send();
        
        $mailer = new Dmailer();
        $mailer->setSubject($this->subject, []);
        $mailer->setBody($this->content);
        $mailer->AddAddress($to);
        $mailer->Send();
    }


    /**
     * get content from tempalte setting
     * 
     * @param string $key
     * @param array $param
     * @return UltilityEmail
     */
    public function getTemplateText($key, $param = array(), $param_subject = []) {
        $mailSetting = MailSettingsSearch::findOne(['mail_key' => $key]);
        if ($mailSetting) {
            $this->from_name = $mailSetting->mail_title;
            $param_subject = $param_subject ? $param_subject : $param;
            $this->subject = $this->mailer->subject = $mailSetting->getMailSubject($param_subject);
            $this->content = $this->convertBodyText($mailSetting->getMailContent($param));
            $this->mailer->setHtmlBody($this->content, 'text/plain');
        }
        return $this;
    }

    /**
     * convert br to nl
     * 
     * @param string
     * @return string
     */
    public function convertBodyText($string) {
        return $string;
//        return str_replace('<br />', PHP_EOL, $string);
    }

}
