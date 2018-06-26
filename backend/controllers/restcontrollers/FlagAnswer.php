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
use common\models\kanga\LinkAnswer;
use common\models\kanga\HistoryAnswer;
use common\models\user\UserModel;

/**
 * CreateAction implements the API endpoint for creating a new model from the given data.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FlagAnswer extends Action {

    public function run() {
        $data = Yii::$app->request->post();
        $category_arr = array();
        $result = array();
        if (isset($data['access_token'])) {
            $token = $data['access_token'];
            $user = UserModel::findOne(['access_token' => $token]);
            if ($user) {
                $history_id = $data['history_id'];
                $linkObj = HistoryAnswer::findOne(['id' => $history_id]);
                if ($linkObj) {
                    $linkObj->vote = 0;
                    $linkObj->flag = 1;
                    $linkObj->save();
                    $result['message'] = 'Success';
                    $result['status'] = 200;
                } else {
                    $result['message'] = 'Invalid question id or answer id';
                    $result['status'] = 404;
                }
            } else {
                $result['message'] = 'Authen Fail';
                $result['status'] = 401;
            }
        } else {
            $result['message'] = "Missing Access Token";
            $result['status'] = 400;
        }
        return $result;
    }

}
