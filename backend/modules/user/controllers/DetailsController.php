<?php

/**
 *
 * @author dungnguyenanh
 */

namespace backend\modules\user\controllers;

use backend\controllers\BackendController;
use common\models\user\UserModel;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;

class DetailsController extends BackendController {

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
        $model = new UserModel();
        $model->unsetAttributes();
        $model->load(r()->get());
        $query = $model->find();

        $query->select([
            '`user`.user_id',
            '`user`.firstname',
            '`user`.lastname',
            '`user`.email',
            '`user`.phone',
            '`user`.birthday',
        ]);

        $dataProvider = $model->searchAdmin($query);

        return $this->Prender('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'total' => $dataProvider->totalCount,
        ]);
    }

    public function actionCreate() {
        $model = new UserModel();
        return $this->Prender('form', [
                    'model' => $model,
        ]);
    }

    public function actionSave() {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        if ($id) {
            //in case of update user
            $model = UserModel::findOne(["user_id" => $id]);
        } else {
            //in case of create a new user.
            $model = new UserModel();
        }
        $model->load(Yii::$app->request->post());        
        $model->birthday = $model->birthday && strtotime($model->birthday) ? strtotime($model->birthday) : null;
        if(!$id) $model->password = md5(md5($model->password));
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        $model->save(false); //No need to validate model again.
        $this->redirect(Url::to('index'));
    }

    public function actionUpdate() {
        $id = Yii::$app->getRequest()->getQueryParam('id');
        $model = UserModel::findOne(["user_id" => $id]);
        if ($model) {            
            return $this->Prender('form', [
                        'model' => $model,
            ]);
        }
        else {
           $this->pageNotFound('Record not exists'); 
        }
    }    

    public function actionDelete() {
       return parent::actionDelete();
    }

    public function actionDeleteall() {
        return parent::actionDeleteall();
    }

}
