<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\controllers\restcontrollers;

use Yii;
use yii\helpers\Json;
use common\models\kanga\KangaCategory;
use common\models\kanga\User;

/**
 * CreateAction implements the API endpoint for creating a new model from the given data.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class InsertQuestion extends \yii\rest\CreateAction {

    public function run() {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }
        $data = Yii::$app->request->post();
        if (isset($data['access_token'])) {

            $token = $data['access_token'];
            $category_id = $data['category_id'];
            if (!Category::findOne(['id' => $category_id])) {
                return "Invalid category id";
            }
            $user_company = User::findOne(['access_token' => $token]);
            if ($user_company) {
                $all_data = Json::decode(Yii::$app->request->post('data'));
                $total_insert = count($all_data);
                $count_success = 0;
                foreach ($all_data as $data) {
                    $data['category_id'] = $category_id;
                    $data['created_by'] = $user_company->id;
                    $data['has_answer'] = 0;
                    $model = new $this->modelClass([
                        'scenario' => $this->scenario,
                    ]);
                    $model->load($data, '');
                    if ($model->save()) {
                        $count_success++;
                    }
                }
                if ($count_success > 0) {
                    $response = Yii::$app->getResponse();
                    $response->setStatusCode(201);
                }
                return $count_success;
            } else {
                $answer = 'Authen Fail';
            }
        } else {
            return "Missing Access Token";
        }
    }

}
