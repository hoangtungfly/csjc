<?php
/**
 *
 * @author tunglexuan
 */
namespace backend\modules\system\controllers;

use backend\controllers\BackendController;
use common\models\system\SysPages;
use Yii;
use yii\filters\VerbFilter;

class SyspagesController extends BackendController {
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
        $model = new SysPages();
        $model->unsetAttributes();
        $model->load($get);

        $query = $model->find();
        if (!isset($get['sort'])) {
            $query->orderBy("page_id desc");
        }
        $dataProvider = $model->searchAdmin($query);

        return $this->Prender('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'total' => $dataProvider->totalCount,
        ]);
    }

    public function actionCreate() {
        $model = new SysPages; 
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
        $id = (int)r()->get();
        $model = SysPages::findOne(['page_id'=>$id]);
        $this->process($model);
        return $this->Prender('form', ['model'=>$model,
                                        'id_form'=>'D_form_update',
                                        'title'=>Yii::t('sys_page',''),
                                        'url'=> $this->createUrl('update'),
                                        'urlb'=> base64encodeUrl($this->createUrl('/')),
                                        ]);
    }
    
    
    public function actionDelete() {
       $id = (int)$this->getParam('id');
        $model = SysPages::findOne($id);
        if($model) {
            $model->delete();
            $this->jsonResponse(200,'Delete successfully!');
        } else {
            $this->jsonResponse(400,'Record not exists');
        }
    }
    
    public function actionDeleteall() {
        $ids = $this->getParam('ids');
        $models = SysPages::find()->where([SysPages::getKey() => $ids])->all();
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