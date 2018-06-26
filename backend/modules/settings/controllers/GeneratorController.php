<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\models\admin\MenuAdminSearch;
use common\models\admin\SettingsGridSearch;
use common\models\admin\SettingsTableSearch;
use common\utilities\UtilityArray;
use common\utilities\UtilityHtmlFormat;
use common\utilities\UtilityUrl;

class GeneratorController extends BackendController {

    public function beforeAction($action) {
        if (user()->isGuest || user()->identity->app_type != APP_TYPE_ADMIN) {
            $urlb = base64_encode(UtilityUrl::realURL());
            $this->redirect($this->createUrl('/site/login',['urlb' => $urlb]));
            app()->end();
        }
        return parent::beforeAction($action);
    }
    
    public function actionIndex() {
        $listSettings = MenuAdminSearch::find()->where('id != 94')->all();
        $listSettings = UtilityArray::ArrayPC($listSettings);
        return $this->Prender('index', [
            'listSettings'  => $listSettings,
        ]);
    }
    
    public function actionGridview() {
        $id = (int)$this->getParam('id');
        if($id && $model = MenuAdminSearch::findOne($id)) {
            $modelTable = SettingsTableSearch::findOne($model->table_id);
            $listGridview = SettingsGridSearch::listGridByTable($modelTable->table_id);
            $breadcrumbs = [];
            if($model->pid)
                $breadcrumbs[] = $model->parent;
            $breadcrumbs[] = $model;
            
            $attributeLabels = $model->attributeLabels();
            
            $index_header = $this->renderPartial('view/index/index_header', [
                'model'             => $model,
                'modelTable'        => $modelTable,
                'listGridview'      => $listGridview,
                'breadcrumbs'       => $breadcrumbs,
            ]);
            
            $breadcrumb = $this->renderPartial('view/breadcrumb', [
                'breadcrumbs'       => $breadcrumbs,
                'modelTable'        => $modelTable,
            ]);
            
            $title = $this->renderPartial('view/title', [
                'model'             => $model,
                'modelTable'        => $modelTable,
                'listGridview'      => $listGridview,
                'breadcrumbs'       => $breadcrumbs,
                'attributeLabels'   => $attributeLabels,
            ]);
            
            $index = $this->renderPartial('view/index', [
                'model'             => $model,
                'modelTable'        => $modelTable,
                'listGridview'      => $listGridview,
                'breadcrumbs'       => $breadcrumbs,
                'attributeLabels'   => $attributeLabels,
                'classTable'        => $modelTable->class,
            ]);
            file_put_contents($model->getLinkIndex(), $index_header . "\n\n" . $breadcrumb ."\n\n" . $title ."\n\n" . $index);
            $this->writeActionController($model,$modelTable,'index');
            $this->jsonResponse(200);
        } else {
            $this->jsonResponse(404,'Model had not found!',$modelTable);
        }
    }
    
    public function writeActionController($model,$modelTable,$action) {
        $contentFunctionWrite = $this->renderPartial('view/controller/' . $action, [
            'model'         => $model,
            'modelTable'    => $modelTable,
        ]);
        
        $link = $model->getLinkController();
        $contentFile = @file_get_contents($link);
        if($contentFile) {
            $contentFunction = UtilityHtmlFormat::getFunction($contentFile, $action);
            if($contentFunction) {
                $contentFile = str_replace($contentFunction, $contentFunctionWrite, $contentFile);
            } else {
                $contentFile = UtilityHtmlFormat::insertStringToContentByPosition($contentFile,"\n".$contentFunctionWrite."\n",strrpos($contentFile, '}'));
            }
            file_put_contents($link, $contentFile);
        }
    }
    
    public function actionCreateview() {
        $id = (int)$this->getParam('id');
        if($id && $model = MenuAdminSearch::findOne($id)) {
            $modelTable = SettingsTableSearch::findOne($model->table_id);
            
            $breadcrumbs = [];
            if($model->pid)
                $breadcrumbs[] = $model->parent;
            $breadcrumbs[] = $model;
            
            $attributeLabels = $model->attributeLabels();
            
            $breadcrumb = $this->renderPartial('view/breadcrumb', [
                'breadcrumbs'       => $breadcrumbs,
                'modelTable'        => $modelTable,
            ]);
            
            $index = $this->renderPartial('view/create', [
                'model'             => $model,
                'modelTable'        => $modelTable,
            ]);
            file_put_contents($model->getLinkCreate(), $breadcrumb . "\n\n" . $index);
            $this->writeActionController($model,$modelTable,'create');
            $this->jsonResponse(200);
        } else {
            $this->jsonResponse(404,'Model had not found!',$modelTable);
        }
    }
    
    public function actionUpdateview() {
        $id = (int)$this->getParam('id');
        if($id && $model = MenuAdminSearch::findOne($id)) {
            $modelTable = SettingsTableSearch::findOne($model->table_id);
            
            $breadcrumbs = [];
            if($model->pid)
                $breadcrumbs[] = $model->parent;
            $breadcrumbs[] = $model;
            
            $breadcrumb = $this->renderPartial('view/breadcrumb', [
                'breadcrumbs'       => $breadcrumbs,
                'modelTable'        => $modelTable,
            ]);
            
            $index = $this->renderPartial('view/update', [
                'model'             => $model,
                'modelTable'        => $modelTable,
            ]);
            file_put_contents($model->getLinkUpdate(), $breadcrumb . "\n\n" . $index);
            $this->writeActionController($model,$modelTable,'update');
            $this->jsonResponse(200);
        } else {
            $this->jsonResponse(404,'Model had not found!',$modelTable);
        }
    }
    
    public function actionFormview() {
        $id = (int)$this->getParam('id');
        if($id && $model = MenuAdminSearch::findOne($id)) {
            $modelTable = SettingsTableSearch::findOne($model->table_id);
            
            $breadcrumbs = [];
            if($model->pid)
                $breadcrumbs[] = $model->parent;
            $breadcrumbs[] = $model;
            
            $form = $this->renderPartial('view/form', [
                'model'             => $model,
                'modelTable'        => $modelTable,
            ]);
            file_put_contents($model->getLinkForm(), $form);
            $this->jsonResponse(200);
        } else {
            $this->jsonResponse(404,'Model had not found!',$modelTable);
        }
    }
}