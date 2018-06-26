<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use yii\filters\VerbFilter;

class TempController extends BackendController {
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
        $model = new \common\models\settings\Temp();
        $model->unsetAttributes();
        $model->load($get);

        $query = $model->find();
                        
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
    
    
    public function actionCreate() {
        $model = new \common\models\settings\Temp();
        $model->load(r()->get());
        $this->process($model);
        return $this->Prender('create', [
            'model' => $model,
        ]);
    }
    
    
    public function actionUpdate() {
        $id = $this->getParam('id');
        $model = \common\models\settings\Temp::findOne($id);
        $model->load(r()->get());
        $this->process($model);
        return $this->Prender('update', [
            'model' => $model,
        ]);
    }
    
    public function actionDelete() {
        return parent::actionDelete();
    }
    
    public function actionDeleteall() {
        return parent::actionDeleteall();
    }
    
    public function actionCopy() {
        return parent::actionCopy();
    }
    
    public function actionAllcopy() {
        return parent::actionAllcopy();
    }
    
    public function actionView() {
        return parent::actionView();
    }
}