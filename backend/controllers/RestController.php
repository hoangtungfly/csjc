<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use common\models\kanga\Answer;
use common\models\kanga\UserApi;
use common\models\kanga\HistoryAnswer;
use yii\web\Response;
use yii\filters\auth\HttpBasicAuth;

class RestController extends ActiveController {

    public $modelClass = 'common\models\kanga\Answer';

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = [ 'application/json' => Response::FORMAT_JSON];
//        $behaviors['authenticator'] = [
//            'class' => HttpBasicAuth::className(),
//        ];
        return $behaviors;
    }

    public function actions() {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);


        $actions['get_answer'] = [
            'class' => 'backend\controllers\restcontrollers\GetAnswer',
            'modelClass' => 'common\models\kanga\Question',
        ];
        $actions['get_category'] = [
            'class' => 'backend\controllers\restcontrollers\GetCategory',
            'modelClass' => 'common\models\kanga\Category',
        ];
        $actions['flag_answer'] = [
            'class' => 'backend\controllers\restcontrollers\FlagAnswer',
            'modelClass' => 'common\models\kanga\Answer',
        ];
        $actions['vote_answer'] = [
            'class' => 'backend\controllers\restcontrollers\VoteAnswer',
            'modelClass' => 'common\models\kanga\Answer',
        ];
        $actions['create_question'] = [
            'class' => 'backend\controllers\restcontrollers\InsertQuestion',
            'modelClass' => 'common\models\kanga\Question',
        ];
        $actions['create_answer'] = [
            'class' => 'backend\controllers\restcontrollers\InsertAnswer',
            'modelClass' => 'common\models\kanga\Answer',
        ];

        $actions['get_messages'] = [
            'class' => 'backend\controllers\restcontrollers\GetMessages',
            'modelClass' => 'common\models\kanga\HistoryAnswer',
        ];

        $actions['get_detail_messages'] = [
            'class' => 'backend\controllers\restcontrollers\GetDetailMessage',
            'modelClass' => 'common\models\kanga\HistoryAnswer',
        ];

        $actions['get_conversation'] = [
            'class' => 'backend\controllers\restcontrollers\GetConversation',
            'modelClass' => 'common\models\kanga\Conversation',
        ];

        $actions['get_history'] = [
            'class' => 'backend\controllers\restcontrollers\GetHistory',
            'modelClass' => 'common\models\kanga\HistoryAnswer',
        ];
        return $actions;
    }

    protected function verbs() {
        return [
            'get_answer' => ['POST', 'GET'],
            'create_answer' => ['POST'],
            'create_question' => ['POST'],
            'flag_answer' => ['POST'],
            'vote_answer' => ['POST'],
            'get_category' => ['POST', 'GET'],
            'get_messages' => ['POST', 'GET'],
            'get_detail_messages' => ['POST'],
            'get_conversation' => ['POST'],
            'get_history' => ['POST'],
        ];
    }

}
