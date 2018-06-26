<?php

/**
 *
 * @author dungnguyenanh
 */

namespace backend\modules\product\controllers;

use backend\controllers\BackendController;
use common\models\product\ProductSearch;
use yii\filters\VerbFilter;

class ProductController extends BackendController {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

            public function actionIndex() {
        $get = r()->get();
        $model = new \common\models\product\Product();
        $model->unsetAttributes();
        $model->load($get);

        $query = $model->find();
                
        $query->select([
        '`product`.id',
                '`product`.name',
                '`product`.description',
                ]);
                        
                if(!isset($get['sort'])) {
            $query->orderBy("id desc");
        }
                $dataProvider = $model->searchAdmin($query);

        return $this->Prender('index',[
            'model'         => $model,
            'dataProvider'  => $dataProvider,
            'total'         => $dataProvider->totalCount,
        ]);
    }
//
//    public function actionCreate() {
//        return parent::actionCreate();
//    }
//
//    public function actionUpdate() {
//        return parent::actionUpdate();
//    }
//
//    public function actionDelete() {
//        return parent::actionDelete();
//    }
//
//    public function actionDeleteall() {
//        return parent::actionDeleteall();
//    }
//
//    public function actionCopy() {
//        return parent::actionCopy();
//    }
//
//    public function actionAllcopy() {
//        return parent::actionAllcopy();
//    }
//
//    public function actionView() {
//        return parent::actionView();
//    }

}
