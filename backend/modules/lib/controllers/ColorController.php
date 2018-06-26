<?php
/**
 *
 * @author tunglexuan
 */
namespace backend\modules\lib\controllers;

use backend\controllers\BackendController;
use common\models\lib\LibColor;
use Yii;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

class ColorController extends BackendController {
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }
    
    public function actionIndex(){
       $model = new LibColor();
       $get = r()->get();
       $find = $model->find();
       
       $dataProvider = $model->searchAdmin($find);
       
       return $this->Prender('index',[
           'model'=>$model,
           'dataProvider'=>$dataProvider,
       ]);
    }
    
    public function actionCreate() {
        $model = new LibColor;
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
                                        'title'=>Yii::t('lib','create_color'),
                                        'url'=> $this->createUrl('create'),
                                        'urlb'=> base64encodeUrl($this->createUrl('/')),
                                        ]);
    }

    public function actionUpdate() {
        $id = (int)  $this->getParam('id');
        $model = LibColor::findOne(['id'=>$id]);
        $this->process($model);
        return $this->Prender('form', ['model'=>$model,
                                        'id_form'=>'form_update',
                                        'title'=>Yii::t('lib','update_color'),
                                        'url'=> $this->createUrl('update'),
                                        'urlb'=> base64encodeUrl($this->createUrl('/')),
                                        ]);
    }
    
    
    public function actionDelete() {
       $id = (int)$this->getParam('id');
        $model = LibColor::findOne($id);
        if($model) {
            $model->delete();
            $this->jsonResponse(200,'Delete successfully!');
        } else {
            $this->jsonResponse(400,'Record not exists');
        }
    }
    
    public function actionDeleteall() {
        $ids = $this->getParam('ids');
        $models = LibColor::find()->where([LibColor::getKey() => $ids])->all();
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