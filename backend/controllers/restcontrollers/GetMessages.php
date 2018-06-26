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

class GetMessages extends Action {

    public function run() {
        $data = Yii::$app->request->post();
        $result = array();
        if (isset($data['access_token'])) {
            $token = $data['access_token'];
            $update = (isset($data['update_status'])) ? true : false;
            $user_company = User::findOne(['access_token' => $token]);
            if ($user_company) {
                $messages = HistoryAnswer::find()->where(['status' => false])->andWhere(['NOT', ['answer_id' => null]])->all();
                $total = count($messages);
                $detail = array();
                foreach ($messages as $mess) {
                    $tmp_arr = array();
                    $answer = Answer::getContent($mess->answer_id);
                    $tmp_arr['question'] = $mess->question_content;
                    $tmp_arr['answer'] = $answer;
                    $tmp_arr['answer_id'] = $mess->answer_id;
                    $tmp_arr['history_id'] = $mess->id;
                    if ($mess->flag > 0 || $mess->vote > 0) {
                        $tmp_arr['no_action'] = TRUE;
                    } else {
                        $tmp_arr['no_action'] = FALSE;
                    }
                    $detail[] = $tmp_arr;

                    if ($update) {
                        $mess->status = true;
                        $mess->save();
                    }
                }
                $final_data = array();
                $final_data['count'] = $total;
                $final_data['data'] = $detail;
                $result['data'] = $final_data;
                $result['status'] = 200;
            } else {
                $result['message'] = 'Authen Fail';
                $result['status'] = 401;
            }
        } else {
            $result['message'] = "Missing Access Token";
            $result['status'] = 401;
        }
        return $result;
    }

}
