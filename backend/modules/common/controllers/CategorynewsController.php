<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\common\controllers;

use backend\controllers\BackendController;
use common\models\category\CategoriesSearch;
use yii\filters\VerbFilter;
use yii\helpers\Html;

class CategorynewsController extends BackendController {
    
    
    public function actionTest() {
        $list = CategoriesSearch::find()->all();
        foreach($list as $key => $item) {
            $item->name = html_entity_decode($item->name);
            $item->save();
        }
        var_dump('thanh cong');die();
    }
    
    public function actionStatus() {
        parent::actionStatus();
        $model =  new CategoriesSearch();
        $model->deleteDefaultFileCacheDefault();
    }
}