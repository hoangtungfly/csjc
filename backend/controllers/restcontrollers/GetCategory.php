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
use common\models\kanga\KangaCategory;
use common\models\user\UserModel;

/**
 * CreateAction implements the API endpoint for creating a new model from the given data.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GetCategory extends Action {

    public function run() {
        $data = Yii::$app->request->post();
        $category_arr = array();
        $result = array();
        if (isset($data['access_token'])) {
            $token = $data['access_token'];
            $user = UserModel::findOne(['access_token' => $token]);
            if ($user) {
                $categoryObjs = Category::findAll(['status' => true]);
                foreach ($categoryObjs as $category) {
                    $category_arr[$category->id] = $category->title;
                }
                $result['data'] = $category_arr;
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
