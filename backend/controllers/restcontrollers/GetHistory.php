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
use common\models\user\UserModel;
use common\models\kanga\Answer;
use common\models\kanga\HistoryAnswer;
use common\models\kanga\Question;

class GetHistory extends Action {

    public function run() {
        $data = Yii::$app->request->post();
        $result = array();
        if (isset($data['access_token'])) {
            $token = $data['access_token'];
            $user = UserModel::findOne(['access_token' => $token]);
            if ($user) {
                $conversation_code = isset($data['conversation_code']) ? $data['conversation_code'] : 0;
                $offset = isset($data['offset']) ? $data['offset'] : 0;
                $limit = isset($data['limit']) ? $data['limit'] : \Yii::$app->params['limit_history'];

                $category_id = isset($data['cid']) ? $data['cid'] : 0;

                if ($conversation_code == 0) {
                    $result['message'] = 'Not found';
                    $result['status'] = 404;
                } else {
                    $result['data'] = HistoryAnswer::getHistory($offset, $limit, $conversation_code, $category_id);
                    $result['status'] = 200;
                }
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
