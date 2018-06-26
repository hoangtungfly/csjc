<?php


namespace frontend\controllers\restcontrollers;

use common\models\system\SysContact;
use common\models\user\LoginForm;
use common\utilities\UtilityArray;
use Yii;
use yii\rest\Action;

class Login extends Action {

    public function run() {
        $model = new LoginForm();
        $post['LoginForm'] = r()->post();
        if ($model->load($post) && $model->validate() && $model->login()) {
            $result = [
                'code' => 200,
                'data' => [
                    'access_token' => user()->identity->getAuthKey(),
                ],
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
