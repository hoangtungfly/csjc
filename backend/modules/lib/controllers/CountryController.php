<?php
/**
 *
 * @author tunglexuan
 */
namespace backend\modules\lib\controllers;

use backend\controllers\BackendController;
use common\models\lib\LibCountries;
use Yii;
use yii\filters\VerbFilter;

class CountryController extends BackendController {
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
        $model = new LibCountries();
        $model->unsetAttributes();
        $model->loadAll($get);

        $query = $model->find();
        if (!isset($get['sort'])) {
            $query->orderBy("country_code desc");
        }
        $dataProvider = $model->searchAdmin($query);

        return $this->Prender('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'total' => $dataProvider->totalCount,
        ]);
    }
    
    public function actionCreate() {
        $model = new LibCountries; 
        if(Yii::$app->request->isPost){
           $model->load(r()->post());
           $model->trimAttrValue();
           if($model->validate()){
               $model->save(false);
               $this->jsonResponse(200);
           }
           else{
                echo json_encode(ActiveForm::validate($model));
                app()->end(); 
           }
       }
       return $this->Prender('form', ['model'=>$model,
                                        'id_form'=>'D_form_create',
                                        'title'=>Yii::t('sys_page','create_title'),
                                        'url'=> $this->createUrl('create'),
                                        'urlb'=> base64encodeUrl($this->createUrl('/')),
                                        ]);
    }
    
    public function actionUpdate() {
        $id = $this->getParam('id');
        $model = LibCountries::findOne(['country_code'=>$id]);
        $this->process($model);
        return $this->Prender('form', ['model'=>$model,
                                        'id_form'=>'form_update',
                                        'title'=>Yii::t('sys_page',''),
                                        'url'=> $this->createUrl('update'),
                                        'urlb'=> base64encodeUrl($this->createUrl('/')),
                                        ]);
    }
    
    public function actionDelete() {
        $id = $this->getParam('id');
        $model = LibCountries::findOne(['country_code'=>$id]);
        if($model){
            $model->delete();
            return $this->jsonResponse(200,'Delete Sucess');
        }
        return $this->jsonResponse(400,'Not found model');
    }
    
    public function actionDeleteall() {
        $ids = $this->getParam('ids');
        $models = LibCountries::find()->where([LibCountries::getKey() => $ids])->all();
        if($models) {
            foreach($models as $key => $item) {
                $item->delete();
            }
            $this->jsonResponse(200,'Delete successfully!');
        } else {
            $this->jsonResponse(400,'Record not exists');
        }
    }
}