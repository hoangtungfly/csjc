<?php
/**
 *
 * @author tunglexuan
 */
namespace backend\modules\lib\controllers;

use backend\controllers\BackendController;
use common\models\lib\LibCountries;
use common\models\lib\LibLanguages;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\VerbFilter;

class LanguageController extends BackendController {
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
        $model = new LibLanguages();
        $model->loadAll($get);
        
        $query = $model->find();
        if(!isset($get['sort'])){
            $query->orderBy('language_id desc');
        }
        
        $dataProvider = $model->searchAdmin($query);
        
        return $this->Prender('index',[
            'model'=>$model,
            'dataProvider'=>$dataProvider,
        ]);
    }
    
    public function actionCreate() {
       $model = new LibLanguages;
       if(Yii::$app->request->isPost){
           $model->load(r()->post());
           $model->trimAttrValue();
           if($model->validate()){
               $model->save(false);
               echo $this->jsonResponse(200);
           }else{
               return ActiveForm::validate($model);
           }
       }
       return $this->Prender('form', ['model'=>$model,
                                        'id_form'=>'D_form_create',
                                        'title'=>Yii::t('lib','create_language'),
                                        'url'=> $this->createUrl('create'),
                                        'urlb'=> base64encodeUrl($this->createUrl('/')),
                                        ]);
    }
    
    public function actionUpdate() {
        $id = (int)$this->getParam('id');
        $model = LibLanguages::findOne($id);
        if($model){
            $model->load(r()->get());
            $model->loadAll(r()->post());
            $model->trimAttrValue();
            if($model->validate()){
                $model->save(false);
                $this->jsonResponse(200,'Update success');
            }  else {
                echo $this->json_encode(ActiveForm::validate($model));
                app()->end();
            }
        }
        else{
            $this->pageNotFound('Not found model');
        }
        return $this->Prender('form', ['model'=>$model,
                                        'id_form'=>'form_update',
                                        'title'=>Yii::t('lib','update_language'),
                                        'url'=> $this->createUrl('create'),
                                        'urlb'=> base64encodeUrl($this->createUrl('/')),
                                        ]);
        return parent::actionUpdate();
    }
    
    public function actionDelete() {
        $id = $this->getParam('id');
        $model = LibLanguages::findOne(['country_code'=>$id]);
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