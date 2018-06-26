<?php


namespace frontend\controllers\restcontrollers;

use common\models\system\SysContact;
use common\utilities\UtilityArray;
use Yii;
use yii\rest\Action;

class Contact extends Action {

    public function run() {
        $model = new SysContact();
        $post['SysContact'] = r()->post();
        $model->setScenario('frontend');
        if ($model->load($post) && $model->validate()) {
            $model->save(false);
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                $response = [
                    'flash' => [
                        'class' => 'success',
                        'message' => 'Thank you for contacting us. We will respond to you as soon as possible.',
                    ]
                ];
            } else {
                $response = [
                    'flash' => [
                        'class' => 'error',
                        'message' => 'There was an error sending email.',
                    ]
                ];
            }
            $result = [
                'code' => 200,
                'data' => $response,
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
