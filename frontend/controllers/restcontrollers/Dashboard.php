<?php


namespace frontend\controllers\restcontrollers;

use Yii;
use yii\rest\Action;

class Dashboard extends Action {

    public function run() {
        $result = [
            'username' => Yii::$app->user->identity->email,
            'access_token' => Yii::$app->user->identity->getAuthKey(),
        ];
        return $result;
    }

}
