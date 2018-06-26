<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\common\controllers;

use backend\controllers\BackendController;
use yii\filters\VerbFilter;

class CategoryproductController extends BackendController {
    
    public function actionStatus() {
        parent::actionStatus();
        $model =  new \common\models\category\CategoriesSearch();
        $model->deleteDefaultFileCacheDefault();
    }
}