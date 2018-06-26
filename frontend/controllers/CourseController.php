<?php

namespace frontend\controllers;

use common\core\controllers\GlobalController;
use common\models\course\CourseUserSearch;
use common\models\settings\MailSettingsSearch;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class CourseController extends GlobalController {
    public function actionProccess() {
        $model = new CourseUserSearch();
        $post = r()->post();
        $model->setScenario('frontend');
        if ($model->load($post) && $model->validate()) {
            $model->save(false);
            MailSettingsSearch::sendCourseMailler($model);
            $result = [
                'code' => 200,
            ];
        } else {
            $result = ActiveForm::validate($model);
        }
        $this->jsonencode($result);
    }
}
