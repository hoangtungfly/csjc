<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\product\controllers;

use backend\controllers\BackendController;
use common\models\product\CartModel;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;

class OrderController extends BackendController {
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
        $model = new CartModel();
        $model->unsetAttributes();
        $model->load(r()->get());
        $query = $model->find();

        $query->select([
            '`cart`.id',
            '`cart`.customer_firstname',
            '`cart`.customer_address',
            '`cart`.customer_phone',
            '`cart`.customer_email',
            '`cart`.customer_lastname',
            '`cart`.customer_company',
            '`cart`.customer_address_two',
            '`cart`.country_id',
            '`cart`.state_id',
            '`cart`.city',
            '`cart`.post_code',
            '`cart`.shipping_method',
            '`cart`.total_price',
            '`cart`.status',
            ]);

        $dataProvider = $model->searchAdmin($query);

        return $this->Prender('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'total' => $dataProvider->totalCount,
        ]);
    }
    public function actionCreate() {
        $model = new CartModel();
        return $this->Prender('form', [
                    'model' => $model,
        ]);
    }   
    public function actionSave() {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        if ($id) {
            //in case of update user
            $model = CartModel::findOne(["id" => $id]);
        } else {
            //in case of create a new user.
            $model = new CartModel();
        }
        $model->load(Yii::$app->request->post());  
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $model->save(false); //No need to validate model again.
        $this->redirect(Url::to('index'));
    }
    public function actionDelete() {
        return parent::actionDelete();
    }
    
    public function actionUpdate() {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $model = CartModel::findOne(["id" => $id]);
        if ($model) {            
            return $this->Prender('form', [
                        'model' => $model,
            ]);
        }
        else {
           $this->pageNotFound('Record not exists'); 
        }
    }
    
    public function actionDeleteall() {
        return parent::actionDeleteall();
    }  
    
    public function actionView() {
        return parent::actionView();
    }
}