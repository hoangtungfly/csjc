<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use yii\filters\VerbFilter;

class StatisticalController extends BackendController {
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
        return parent::actionIndex();
    }
    
    public function actionCreate() {
        return parent::actionCreate();
    }
    
    public function actionUpdate() {
        return parent::actionUpdate();
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