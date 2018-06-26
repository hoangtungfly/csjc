<?php


namespace frontend\controllers\restcontrollers;

use common\core\action\GlobalAction;
use common\models\settings\Sendemail;
use common\models\system\SysContact;
use common\utilities\UltilityEmail;
use common\utilities\UtilityArray;
use Yii;
use yii\rest\Action;

class Sendemaillink extends GlobalAction {

    public function run() {
        $model = new Sendemail();
        $post['Sendemail'] = r()->post();
        if ($model->load($post) && $model->validate()) {
            $model->save();
            $sendMail = new UltilityEmail();
            $sendMail->mailer->subject = $model->title;
            $sendMail->subject = $model->title;
            $sendMail->content = $model->link.'<br>'.  nl2br($model->content);
            $sendMail->mailer->setHtmlBody($sendMail->content, 'text/plain');
            $sendMail->send($model->email);
            $result = [
                'code' => 200,
                'data' => 'Send email successfully!',
            ];
        } else {
            $result = [
                'code' => 400,
                'data' => UtilityArray::jsonEncodeValidateAngular($model),
            ];
        }
        return $result;
    }

}
