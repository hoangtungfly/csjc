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
use common\models\kanga\KangaCategory;

/**
 * CreateAction implements the API endpoint for creating a new model from the given data.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GetAnswer extends Action {

    public function run() {
        $data = Yii::$app->request->post();

        $content_question = trim($data['content_question']);
        $result = array();

        if ($content_question == "") {
            $result['status'] = 400;
        }
        $content_question = $this->stripUnicode($content_question);
        if (isset($data['access_token'])) {
            $code = isset($data['conversation_code']) ? $data['conversation_code'] : '';
            $token = $data['access_token'];
            $category_id = isset($data['cid']) ? $data['cid'] : '' ;
            $client_user_id = isset($data['client_user_id']) ? $data['client_user_id'] : '';
            $site = isset($data['site']) ? $data['site'] : '';
            $user = UserModel::findOne(['access_token' => $token]);
            if ($user) {
                $result = Answer::searchEngine($content_question, $user, $category_id, $code, $client_user_id, $site);
            } else {
                $result['status'] = 401;
            }
        } else {
            $result['status'] = 401;
        }
        return $result;
    }

    public function stripUnicode($str) {
        if (!$str)
            return false;
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ạ|Ả|Ã|Ă|Ắ|Ằ|Ặ|Ẳ|Ẵ|Â|Ấ|Ầ|Ậ|Ẩ|Ẫ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni)
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        return $str;
    }

}
