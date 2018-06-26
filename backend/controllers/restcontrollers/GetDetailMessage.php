<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\controllers\restcontrollers;

use Yii;
use yii\helpers\Json;
use yii\rest\Action;
use common\models\kanga\User;
use common\models\kanga\Answer;
use common\models\kanga\HistoryAnswer;
use common\models\kanga\Question;

class GetDetailMessage extends Action {

    public function run() {
        $data = Yii::$app->request->post();
        $result = array();
        if (isset($data['access_token'])) {
            $token = $data['access_token'];
//           
            $user_company = User::findOne(['access_token' => $token]);
            if ($user_company) {
                $id = $data['history_id'];
                $message = HistoryAnswer::findOne(['id' => $id]);
                if ($message) {
                    $final_data = array();
                    $final_data['question'] = $message->question_content;
                    $final_data['answer_id'] = $message->answer_id;
                    $final_data['answer'] = Answer::getContent($message->answer_id);
                    $final_data['history_id'] = $id;
                    if ($message->flag > 0 || $message->vote > 0) {
                        $final_data['no_action'] = TRUE;
                    } else {
                        $final_data['no_action'] = FALSE;
                    }
                    $message->status = true;
                    $message->save();
                    $result['data'] = $final_data;
                    $result['status'] = 200;
                } else {
                    $result['message'] = 'Invalid Data';
                    $result['status'] = 404;
                }
            } else {
                $result['message'] = 'Authen Fail';
                $result['status'] = 401;
            }
        } else {
            $result['message'] = 'Missing Access Token';
            $result['status'] = 401;
        }
        return $result;
    }

}
