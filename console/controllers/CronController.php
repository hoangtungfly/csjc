<?php
namespace console\controllers;

use application\webadmanager\models\UserAdmanager;
use yii\console\Controller;

class CronController extends Controller {

    public function actionTrialcancelfree() {
        UserAdmanager::trialCancelAfterFree();
    }
    
    public function actionTrialendingsoon() {
        UserAdmanager::trialEndingSoon();
    }
}