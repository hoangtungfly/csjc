<?php

namespace common\models\settings;

use common\core\enums\EmailsettingEnum;
use common\core\enums\LanguageEnum;
use common\lib\Dmailer;
use common\models\company\CompanyCategorySearch;
use common\models\company\CompanyPbxSearch;
use common\models\company\CompanySearch;
use common\models\company\CompanySizeSearch;
use common\models\contact\ContactSearch;
use common\models\order\OrderProductSearch;
use common\models\order\OrderSearch;
use common\models\product\BrandSearch;
use common\models\product\ManufacturerSearch;
use common\models\product\MobileSearch;
use common\models\product\ProductSearch;
use common\models\settings\MailSettings;
use common\utilities\UtilityHtmlFormat;

/**
 * MailSettingsSearch represents the model behind the search form about `common\models\settings\MailSettings`.
 */
class MailSettingsSearch extends MailSettings {

    /**
     * Decode attributes
     */
    public function decodeAttribute() {
        if ($this->mail_attribute) {
            return json_decode($this->mail_attribute);
        }
        return array();
    }

    /**
     * Get mail title from model
     */
    public function getMailSubject($data = array()) {
        if ($this->isNewRecord)
            return '';
        return $this->replaceHtml($data, $this->mail_subject);
    }

    /**
     * get content of mail from key
     */
    public static function getMailSubjectFromKey($key = '', $data = array()) {
        if (!$key)
            return '';
        $mail = self::findOne($key);
        if ($mail) {
            return $mail->replaceHtml($data, $mail->mail_subject);
        }
        return '';
    }

    /**
     * Get mail title from model
     */
    public function getMailTitle($data = array()) {
        if ($this->isNewRecord)
            return '';
        return $this->replaceHtml($data, $this->mail_title);
    }

    /**
     * get content of mail from key
     */
    public static function getMailTitleFromKey($key = '', $data = array()) {
        if (!$key)
            return '';
        $mail = self::findOne($key);
        if ($mail) {
            return $mail->replaceHtml($data, $mail->mail_title);
        }
        return '';
    }

    //
    /**
     * Get mail content from model
     */
    public function getMailContent($data = array()) {
        if ($this->isNewRecord)
            return '';
        return $this->replaceHtml($data, $this->mail_msg);
    }

    /**
     * get content of mail from key
     */
    public static function getMailContentFromKey($key = '', $data = array()) {
        if (!$key)
            return '';
        $mail = self::findOne($key);
        if ($mail) {
            return $mail->replaceHtml($data, $mail->mail_msg);
        }
        return '';
    }

    /**
     * replace content with data
     */
    public function replaceHtml($data = null, $content = '') {
        if (!$data)
            return $content;
        $data['url'] = HOST_PUBLIC;
        $data['HOST_PUBLIC'] = HOST_PUBLIC;
        $data['link_contact'] = HOST_PUBLIC . '/site/contact';
        $data['mail_support'] = SystemSettingSearch::getValue('mail_support');
        $data['sys_phone'] = SystemSettingSearch::getValue('sys_phone');
        $data['sys_email'] = SystemSettingSearch::getValue('mail_support');
        $data['sys_mail'] = SystemSettingSearch::getValue('mail_support');
        $data['link_contact'] = SystemSettingSearch::getValue('link_contact');
        return UtilityHtmlFormat::replaceTemplate($content, $data);
    }
    
    public static function sendOrderMailler($order, $type = false) {
        if($order && is_object($order)) {
            $model = self::findOne(['mail_key' => EmailsettingEnum::ORDER_SENDMAIL_TEMPLATE]);
            if($model) {
                /*@var $order OrderSearch */
                $mailer = new Dmailer();
                $mailer->setSubject($model->mail_subject, $order);
                $array_replace = $order->getObject($order);
                $list = OrderProductSearch::find()->select([
                    'product.id','product.alias','product.image','product.name','order_product.count','order_product.price','order_product.product_id',
                ])->where(['order_id' => $order->id])->join('INNER JOIN','product','order_product.product_id = product.id')->asArray()->all();
                foreach($list as $key => $item) {
                    $list[$key]['total'] = number_format($item['count'] * $item['price']);
                }
                $array_replace['product'] = ProductSearch::getArrayByObject($list, false, [30,30]);
                if($array_replace['product']) {
                    foreach($array_replace['product'] as $key => $item) {
                        $array_replace['product'][$key]['link_main'] = HTTP_HOST.$item['link_main'];
                    }
                }
                $content = UtilityHtmlFormat::replaceTemplate($model->mail_msg, $array_replace);
                $mailer->setBody($content);
                if($type) {
                    echo $content;
                }
                $mailer->AddAddress(SystemSettingSearch::getValue('email'));
                $mailer->Send();
            }
        }
    }
    
    public static function sendContactMailler($contact, $type = false) {
        if($contact && is_object($contact)) {
            $model = self::findOne(['mail_key' => EmailsettingEnum::CONTACT_SENDMAIL_TEMPLATE]);
            if($model) {
                /*@var $contact ContactSearch */
                $mailer = new Dmailer();
                $mailer->setSubject($model->mail_subject, $contact);
                $array_replace = $contact->getObject($contact);
                
                $list_mobile = MobileSearch::getAllDropown();
                $list_manufacturer = ManufacturerSearch::getAllDropown();
                $list_brand = BrandSearch::getAllDropown();
                $array_replace['mobile'] = isset($list_mobile[$contact->mobile_id]) ? $list_mobile[$contact->mobile_id] : $contact->mobile_id;
                $array_replace['brand'] = isset($list_mobile[$contact->brand_id]) ? $list_mobile[$contact->brand_id] : $contact->brand_id;
                $array_replace['manufacturer'] = isset($list_mobile[$contact->manufacturer_id]) ? $list_mobile[$contact->manufacturer_id] : $contact->manufacturer_id;
                
                $content = UtilityHtmlFormat::replaceTemplate($model->mail_msg, $array_replace);
                $mailer->setBody($content);
                if($type) {
                    echo $content;
                }
                $mailer->AddAddress(SystemSettingSearch::getValue('email'));
                $mailer->Send();
            }
        }
    }
    public static function sendCompanyMailler($contact, $type = false) {
        if($contact && is_object($contact)) {
            $model = self::findOne(['mail_key' => EmailsettingEnum::COMPANY_SENDMAIL_TEMPLATE]);
            if($model) {
                /*@var $contact CompanySearch */
                $mailer = new Dmailer();
                $mailer->setSubject($model->mail_subject, $contact);
                $array_replace = $contact->getObject($contact);
                
                $list_type = CompanyCategorySearch::getAllDropown();
                $list_size = CompanySizeSearch::getAllDropown();
                $list_pbx = CompanyPbxSearch::getAllDropown();
                $list_lang = LanguageEnum::languageLabel();
                $array_replace['type'] = isset($list_type[$contact->company_category_id]) ? $list_type[$contact->company_category_id] : $contact->company_category_id;
                $array_replace['size'] = isset($list_size[$contact->company_size_id]) ? $list_size[$contact->company_size_id] : $contact->company_size_id;
                $array_replace['pbx'] =  isset($list_pbx[$contact->company_pbx_id]) ? $list_pbx[$contact->company_pbx_id] : $contact->company_pbx_id;
                $array_replace['lang'] = isset($list_lang[$contact->lang]) ? $list_lang[$contact->lang] : $contact->lang;
                
                $content = UtilityHtmlFormat::replaceTemplate($model->mail_msg, $array_replace);
                $mailer->setBody($content);
                if($type) {
                    echo $content;
                }
                $mailer->AddAddress(SystemSettingSearch::getValue('email'));
                $a = $mailer->Send();
            }
        }
    }
    
    public static function sendCourseMailler($contact, $type = false) {
        if($contact && is_object($contact)) {
            $model = self::findOne(['mail_key' => EmailsettingEnum::COURSE_SENDMAIL_TEMPLATE]);
            if($model) {
                /*@var $contact ContactSearch */
                $mailer = new Dmailer();
                $mailer->setSubject($model->mail_subject, $contact);
                $array_replace = $contact->getObject($contact);
                $content = UtilityHtmlFormat::replaceTemplate($model->mail_msg, $array_replace);
                $mailer->setBody($content);
                if($type) {
                    echo $content;
                }
                $mailer->AddAddress(SystemSettingSearch::getValue('email'));
                $mailer->Send();
            }
        }
    }

}
