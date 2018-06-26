<?php


namespace frontend\controllers\restcontrollers;

use common\core\action\GlobalAction;
use common\models\settings\CommentSearch;
use common\utilities\UtilityArray;

class Comment extends GlobalAction {

    public function run() {
        $model = new CommentSearch();
        $post = r()->post();
        $model->setScenario('frontend');
        if ($model->load($post) && $model->validate()) {
            $model->save();
            $result = [
                'code' => 200,
                'data' => 'Comment thành công!',
            ];
        } else {
            echo json_encode(\yii\widgets\ActiveForm::validate($model));
            app()->end();
        }
        return $result;
    }

}
