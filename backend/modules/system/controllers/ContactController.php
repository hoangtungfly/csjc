<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\system\controllers;

use backend\controllers\BackendController;
use yii\filters\VerbFilter;

class ContactController extends BackendController {
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
        $model = new \common\models\system\SysContact();
        $model->unsetAttributes();
        $model->load($get);

        $query = $model->find();
                
        $query->select([
        '`sys_contact`.contact_id',
                '`sys_contact`.contact_name',
                '`sys_contact`.contact_email',
                '`sys_contact`.contact_subject',
                '`sys_contact`.contact_body',
                ]);
                        
                if(!isset($get['sort'])) {
            $query->orderBy("contact_id desc");
        }
                $dataProvider = $model->searchAdmin($query);

        return $this->Prender('index',[
            'model'         => $model,
            'dataProvider'  => $dataProvider,
            'total'         => $dataProvider->totalCount,
        ]);
    }
    
    
    public function actionCreate() {
        $model = new \common\models\system\SysContact();
        $model->load(r()->get());
        $this->process($model);
        return $this->Prender('create', [
            'model' => $model,
        ]);
    }
    
    
    public function actionUpdate() {
        $id = $this->getParam('id');
        $model = \common\models\system\SysContact::findOne($id);
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