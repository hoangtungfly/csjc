<?php

namespace frontend\controllers;

use common\core\controllers\GlobalController;
use common\models\admin\SettingsMessageSearch;
use common\models\contact\ContactSearch;
use common\models\settings\MailSettingsSearch;
use yii\widgets\ActiveForm;
use function app;

/**
 * Site controller
 */
class ContactController extends GlobalController {
    public function actionProccess() {
        $model = new ContactSearch();
        $post = r()->post();
        $model->setScenario('frontend');
        $lang = $this->getParam('lang');
        if($lang) {
            app()->language = $lang;
        }
        if ($model->load($post) && $model->validate()) {
            $model->save(false);
            MailSettingsSearch::sendContactMailler($model);
            $result = [
                'code' => 200,
                'title' => SettingsMessageSearch::t('contact','title_success','Liên hệ thành công'),
                'message' => SettingsMessageSearch::t('contact','message_success','Bạn đã liên hệ thành công. Chúng tôi sẽ xem xét để liên hệ lại bạn trong vòng ít phút tới!'),
            ];
        } else {
            $result = ActiveForm::validate($model);
        }
        $this->jsonencode($result);
    }
}
