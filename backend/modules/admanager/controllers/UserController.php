<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\admanager\controllers;

use application\webadmanager\models\AccountSearch;
use application\webadmanager\models\CustomersSearch;
use application\webadmanager\models\UserAdmanager;
use backend\controllers\BackendController;
use common\lib\DPHPExcel;
use common\models\payments\PaymentOrderObjects;
use common\models\payments\PaymentOrders;
use common\models\plan\PlanSearch;
use common\utilities\UtilityDateTime;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class UserController extends BackendController {
    public function actionIndex() {
        $customerid = (int)$this->getParam('customerid');
        if($customerid && ($model = CustomersSearch::findOne($customerid))) {
            $list = UserAdmanager::find()->select('userid,email,name,firstname,businessname,countrycode,contactphone,address,app_type')->where(['customerid' => $customerid])->orderBy('app_type desc,userid desc')->all();
            $listFacebook = AccountSearch::find()->select('id,accountid,email,fullname,createddate,modifieddate,createdby,modifiedby')->where(['customerid' => $customerid])->all();
//            $list_account_user = [];
//            if($listFacebook) {
//                foreach($listFacebook as $item) {
//                    if($item->createdby) {
//                        $list_account_user[$item->createdby] = $item->createdby;
//                    }
//                    if($item->modifiedby) {
//                        $list_account_user[$item->modifiedby] = $item->modifiedby;
//                    }
//                }
//            }
//            $list_user = ArrayHelper::map(UserAdmanager::find()->select('userid,email')->where(['userid' => $list_account_user])->all(),'userid','email');
            $list_user = ArrayHelper::map($list,'userid','email');
            $modelOrder = new PaymentOrders();
            $modelOrder->unsetAttributes();
            $dataProvider = $modelOrder->searchHome(r()->get(),$model->customerid);
            $dataProvider->pagination->defaultPageSize = 1000;
            return $this->Prender('index', [
                'list'  => $list,
                'model' => $model,
                'plan'  => PlanSearch::findOne($model->plan_id),
                'list_user' => $list_user,
                'listFacebook'  => $listFacebook,
                'listOrder'    => $dataProvider->getModels(),
            ]);
        }
        
    }
    public function actionUpdateuser() {
        $id = (int)$this->getParam('id');
        if($id && ($model = UserAdmanager::findOne($id))) {
            $this->jsonencode([
                'code'      => 200,
                'html'      => $this->renderPartial('edituser',[
                    'model'     => $model,
                ])
            ]);
        }
    }
    
    public function actionUpdateuserproccess() {
        $id = (int)$this->getParam('id');
        if($id && ($model = UserAdmanager::findOne($id))) {
            $data = r()->post();
            $model->loadAll($data);
            if($model->validate()) {
                $model->save(false);
                $this->jsonResponse(200);
            } else {
                $this->jsonValidate($model);
            }
        }
    }
    
    public function actionUpdatefacebook() {
        $id = (int)$this->getParam('id');
        if($id && ($model = AccountSearch::findOne($id))) {
            $this->jsonencode([
                'code'      => 200,
                'html'      => $this->renderPartial('editfacebook',[
                    'model'     => $model,
                ])
            ]);
        }
    }
    
    public function actionUpdatefacebookproccess() {
        $id = (int)$this->getParam('id');
        if($id && ($model = AccountSearch::findOne($id))) {
            $data = r()->post();
            $model->load($data);
            if($model->validate()) {
                $model->save(false);
                $this->jsonResponse(200);
            } else {
                $this->jsonValidate($model);
            }
        }
    }
    
    public function actionInvoice() {
        $id = (int)$this->getParam('id');
        if($id && ($model = PaymentOrders::findOne($id))) {
            $modelObject = PaymentOrderObjects::findOne(['order_id' => $id]);
            $this->jsonencode([
                'code'      => 200,
                'html'      => $this->renderPartial('invoice',[
                    'model'         => $model,
                    'modelObject'   => $modelObject,
                    'plan'          => PlanSearch::findOne($modelObject->object_id),
                ]),
            ]);
        }
    }
    
    public function actionExport() {
        $customerid = (int)$this->getParam('customerid');
        if($customerid && ($model = CustomersSearch::findOne($customerid))) {
            $modelOrder = new PaymentOrders();
            $modelOrder->unsetAttributes();
            $dataProvider = $modelOrder->searchHome(r()->get(), $customerid);
            $dataProvider->pagination->defaultPageSize = 100000;
            /* @var $dataProvider  ActiveDataProvider */
            $list = $dataProvider->getModels();
            $rs = [];
            if($list) {
                foreach($list as $key => $data) {
                    $rs[] = [
                        [$data->getInvoiceNo()],
                        [$data->transaction_id],
                        [UtilityDateTime::formatDate($data->created_time, 'd-M-Y H:i A')],
                        [$data->getPlanLabel()],
                        [$data->getTotalMessage()],
                    ];
                }
            }
            $excel = new DPHPExcel(APPLICATION_PATH . '/files/transaction.xlsx', false);
            $excel->writeExcel($rs, 0, 3, 0, 'transaction.xlsx');
            die();
        }
    }
}