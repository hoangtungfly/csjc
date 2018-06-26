<?php
/**
 *
 * @author tunglexuan
 */
namespace backend\modules\category\controllers;

use backend\controllers\BackendController;
use common\models\category\Category;
use Yii;
use yii\widgets\ActiveForm;


class CategoryController extends BackendController {
    
    public function actionIndex() {
        $get = r()->get();
        $model = new Category();
        $model->unsetAttributes();
        $model->loadAll($get);

        $query = $model->find();

        $query->select([

        '`category`.id',
                '`category`.name',
                '`category`.pid',
                '`category`.meta_title',
                '`category`.meta_keyword',
                '`category`.meta_description',
                '`category`.domain',
                '`category`.status',
                '`category`.limitproduct',
                '`category`.lang',
                ]);
                        
                if(!isset($get['sort'])) {
                    $query->orderBy("id desc");
                }
                $dataProvider = $model->searchAdmin($query);
        return $this->Prender('index',[
            'model'         => $model,
            'dataProvider'  => $dataProvider,
            'total'         => $dataProvider->totalCount,

            '`category`.id',
            '`category`.name',
            '`category`.pid',
            '`category`.meta_title',
            '`category`.meta_keyword',
            '`category`.meta_description',
            '`category`.domain',
            '`category`.status',
            '`category`.limitproduct',
            '`category`.lang',
        ]);

        if (!isset($get['sort'])) {
            $query->orderBy("id desc");
        }
        $dataProvider = $model->searchAdmin($query);

        return $this->Prender('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'total' => $dataProvider->totalCount,

        ]);
    }

    public function actionCreate() {
       $model = new Category;
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
       return $this->Prender('_form', ['model'=>$model,
                                        'id_form'=>'D_form_create',
                                        'title'=>Yii::t('sys_page','create_category'),
                                        'url'=> $this->createUrl('create'),
                                        'urlb'=> base64encodeUrl($this->createUrl('/')),
                                        ]);
   }
    
     public function actionUpdate() {
        $id = (int)  $this->getParam('id');
        $model = Category::findOne(['id'=>$id]);
        $this->process($model);
        return $this->Prender('_form', ['model'=>$model,
                                        'id_form'=>'form_update',
                                        'title'=>Yii::t('sys_page','update'),
                                        'url'=> $this->createUrl('update'),
                                        'urlb'=> base64encodeUrl($this->createUrl('/')),
                                        ]);
    }
    
   
    public function actionDelete() {
        $id = (int)$this->getParam('id');
        $model = Category::findOne($id);
        if($model) {
            $model->delete();
            $this->jsonResponse(200,'Delete successfully!');
        } else {
            $this->jsonResponse(400,'Record not exists');
        }
    }
    
    public function actionDeleteall() {
       $ids = $this->getParam('ids');
        $models = Category::find()->where([Category::getKey() => $ids])->all();
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