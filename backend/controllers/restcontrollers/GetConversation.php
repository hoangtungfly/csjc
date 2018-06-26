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
use common\models\kanga\Conversation;
use common\models\user\UserModel;

/**
 * CreateAction implements the API endpoint for creating a new model from the given data.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GetConversation extends Action {

    public function run() {
        $data = Yii::$app->request->post();
        $category_arr = array();
        $result = 0;
        if (isset($data['access_token'])) {
            $user = UserModel::findOne(['access_token' => trim($data['access_token'])]);
            if ($user) {
                $code = strtotime(date("Y-m-d H:i:s"));
                $code = bin2hex($code);
                $conv = new Conversation();
                $conv->code = $code;
                if ($conv->save()) {
                    $result = $conv->id;
                }
            }
        }
        return $result;
    }

}
