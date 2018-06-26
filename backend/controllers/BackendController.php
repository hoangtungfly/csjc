<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BackendController
 *
 * @author hanguyenhai
 */

namespace backend\controllers;

use common\core\controllers\GlobalController;
use common\models\admin\MenuAdminSearch;
use common\models\admin\SettingsFiles;
use common\models\admin\SettingsImages;
use common\models\admin\SettingsTableSearch;
use common\utilities\UtilityArray;
use common\utilities\UtilityHtmlFormat;
use common\utilities\UtilityUrl;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

class BackendController extends GlobalController {

    public $breadcrumbs = [];
    public $table_id = 0;
    public $curl = false;
    public $setting_table = false;
    public $menu_admin = false;
    public $action;
    public $menu_admin_id;
    
    public function init() {
        parent::init();
    }

    public function beforeAction($action) {
        $this->setSessionLanguage();
        $this->action = $action;
        if (user()->isGuest || user()->identity->app_type != APP_TYPE_ADMIN) {
            $urlb = base64_encode(UtilityUrl::realURL());
            $this->redirect($this->createUrl('/site/login',['urlb' => $urlb]));
            app()->end();
        }
        return parent::beforeAction($action);
    }
    
    public function getTable($action) {
        /*GET MENUADMIN
         * BEGIN
         */
        $this->curl = Url::current();
        $module = $action->controller->module->id;
        $controller = $action->controller->id;
        $action_menu = $this->getAction($action->id);
        $menu_admin_id = $this->getParam('menu_admin_id');
        if($menu_admin_id) {
            $this->menu_admin = MenuAdminSearch::findOne($menu_admin_id);
        }
        if(!$this->menu_admin) {
            $this->menu_admin = MenuAdminSearch::findOne([
                'module'        => $module,
                'controller'    => $controller,
                'action'        => $action_menu,
            ]);
        }
        if($this->menu_admin) {
            $this->menu_admin_id = $this->menu_admin->id;
            if($this->menu_admin->pid) {
                $this->breadcrumbs[] = MenuAdminSearch::findOne($this->menu_admin->pid);
            }
            $this->breadcrumbs[] = $this->menu_admin;
            $this->table_id = $this->menu_admin->table_id;
            $this->setting_table = SettingsTableSearch::findOne($this->table_id);
            
        }
        /*END*/
    }
    
    public function createUrl($route, $params = array()) {
        return parent::createUrl($route, $params);
    }
    
    public function getAction($action) {
        $array = array('1','index','update','create','copy','delete','deleteall','view','copyall', 'multiadd');
        if(array_search($action, $array)) {
            return 'index';
        } else {
            return $action;
        }
    }
    
    
    public function actionIndex() {
        $this->getTable($this->action);
        if($this->setting_table) {
            $model = new $this->setting_table->class;
            return $this->index($model,true);
        } else {
            $this->pageDenied('Setting table has not found id');
        }
    }
    
    public function index($model, $flag = false) {
        $model->unsetAttributes();
        $attrGet = r()->get();
        $model->load($attrGet);
        $array = explode('\\',$this->setting_table->class);
        $class = $array[count($array) - 1];
        if(isset($attrGet[$class]) && count($attrGet) > 0) {
            $modelAttributes = $attrGet[$class];
            $primaryKey = $model->getKey();
            if(isset($modelAttributes[$primaryKey]) && $modelAttributes[$primaryKey] != "") {
                $model->$primaryKey = $modelAttributes[$primaryKey];
            }
        }
        if($flag) {
            $views = '@app/views/layouts/settings/index';
        } else {
            $views = 'index';
        }
        $params = [
            'model'         => $model,
        ];
        return $this->Prender($views,$params);
    }
    
    public function actionCreate() {
        $this->getTable($this->action);
        if($this->setting_table) {
            $model = new $this->setting_table->class;
            return $this->create($model, true);
        } else {
            $this->pageDenied('Setting table has not found id');
        }
    }

    public function create($model, $flag = false) {
        $this->process($model);
        if($flag) {
            $views = '@app/views/layouts/settings/create';
        } else {
            $views = 'create';
        }
        return $this->Prender($views, [
            'model' => $model,
        ]);
    }
    
    public function actionUpdate() {
        $this->getTable($this->action);
        if($this->setting_table) {
            $model = new $this->setting_table->class;
            return $this->update($model, true);
        } else {
            $this->pageDenied('Setting table has not found id');
        }
    }
    
    public function update($model, $flag = false) {
        $id = $this->getParam('id');
        $model = $model->findOne($id);
        if($model) {
            $this->process($model);
            if($flag) {
                $views = '@app/views/layouts/settings/update';
            } else {
                $views = 'update';
            }
            return $this->Prender($views, [
                'model' => $model,
            ]);
        } else {
            $this->pageNotFound('Record not exists');
        }
    }
    
    public function process($model, $list = false) {
        $model->load(r()->get());
        $dataPost = r()->post();
        if($model->loadAll($dataPost)) {
            $flag = $model->isNewRecord ? true : false;
            $model->trimAttrValue();
            if($model->validate()) {
                $model->save();
                if(isset($dataPost['imageiddelete']))
                    SettingsImages::deleteImageById(trim($dataPost['imageiddelete']));
                if(isset($dataPost['imageiddeletename']))
                    SettingsImages::deleteImageByName(trim($dataPost['imageiddeletename']));
                if(isset($dataPost['fileiddelete']))
                    SettingsFiles::deleteFileById(trim($dataPost['fileiddelete']));
                if(isset($dataPost['fileiddeletename']))
                    SettingsFiles::deleteFileByName(trim($dataPost['fileiddeletename']));
                if($flag) {
                    if(isset($dataPost['imageid']))
                        SettingsImages::addImageById(trim($dataPost['imageid']),$model);
                    if(isset($dataPost['fileid']))
                        SettingsFiles::addFileById(trim($dataPost['fileid']), $model);
                }
                $this->jsonResponse(200);
            } else {
                echo json_encode(ActiveForm::validate($model));
                app()->end();
            }
        }
    }
    
    public function actionDelete() {
        $this->getTable($this->action);
        if($this->setting_table) {
            $model = new $this->setting_table->class;
            $this->delete($model, true);
        } else {
            $this->jsonResponse(400,'Setting table has not found id');
        }
    }
    
    public function delete($model, $flag = false) {
        $id = $this->getParam('id');
        $model = $model->findOne($id);
        if($model) {
            $model->delete();
            $this->jsonResponse(200,'Delete successfully!');
        } else {
            $this->jsonResponse(400,'Record not exists');
        }
    }
    
    public function actionDeleteall() {
        $this->getTable($this->action);
        if($this->setting_table) {
            $model = new $this->setting_table->class;
            $this->deleteall($model, true);
        } else {
            $this->jsonResponse(400,'Setting table has not found id');
        }
    }   
    
    public function deleteall($model, $flag = false) {
        $ids = $this->getParam('ids');
        $list = $model->find()->where([$model->getKey() => $ids])->all();
        if($list) {
            foreach($list as $key => $item) {
                $item->delete();
            }
            $this->jsonResponse(200,'Delete successfully!');
        } else {
            $this->jsonResponse(400,'Record not exists');
        }
    }

    public function actionStatus() {
        $table = $this->getParam('table');
        $primaryKey = $this->getParam('primaryKey');
        $id = (int)$this->getParam('id');
        $statusName = $this->getParam('statusName');
        $value = (int)$this->getParam('value');
        if($table != "" && $primaryKey != "" && !preg_match('/,/',$primaryKey) && $id && $statusName != "") {
            app()->db->createCommand("update $table set `$statusName` = $value where $primaryKey = $id")->execute();
        }
    }
    
    public function actionStatusa() {
        $table = $this->getParam('table');
        $primaryKey = $this->getParam('primaryKey');
        $id = (int)$this->getParam('id');
        $statusName = $this->getParam('statusName');
        $model = $table::findOne($id);
        $model->$statusName = $model->$statusName ? 0 : 1;
        $model->save(false);
        $this->jsonResponse(200,$model->$statusName);
    }
    
    public function actionView() {
        $this->getTable($this->action);
        if($this->setting_table) {
            $model = new $this->setting_table->class;
            
            $id = (int)$this->getParam('id');
            $model = $model->findOne($id);
            
            $link = APPLICATION_PATH .'/backend/modules/'.app()->controller->module->id.'/views/'.app()->controller->id.'/view.php';
            return $this->renderPartial(is_file($link) ? 'view' : '@app/views/layouts/settings/view', [
                'model'     => $model,
            ]);
        } else {
            $this->pageDenied('Setting table has not found id');
        }
    }
    
    public function actionCopy() {
        $this->getTable($this->action);
        if($this->setting_table) {
            $class = new $this->setting_table->class;
            
            $id = (int)$this->getParam('id');
            $model = $class::findOne($id);
            if($model) {
                $attributes = $model->attributes;
                $attributes = UtilityArray::ua('created_time,modified_time,created_by,modified_by', $attributes);
                $name = false;
                foreach($attributes as $key => $value) {
                    if(preg_match('/name/',$key)) {
                        $name = $key;
                        break;
                    }
                }
                
                $modelCopy = new $class;
                $modelCopy->attributes = $attributes;
                if($name) {
                    $nameValue = $attributes[$name];
                    do {
                        $demCopy = UtilityHtmlFormat::nameCopy($nameValue);
                        $nameValue = preg_replace("/ - copy[0-9]*$/","",$nameValue)." - copy".$demCopy;
                        $count = $class::find()->where("$name = :name",array(":name" => $nameValue))->count();
                    } while($count);
                    
                    $modelCopy->$name = $nameValue;
                }
                $modelCopy->save(false);
                    
                $this->jsonResponse(200,'Copy successfully!');
            } else {
                $this->jsonResponse(400,'Have not found model');
            }
        } else {
            $this->jsonResponse(400,'Have not found id');
        }
    }
    
    
    public function actionCopyall() {
        $this->getTable($this->action);
        if($this->setting_table) {
            $class = new $this->setting_table->class;
            
            $ids = $this->getParam('ids');
            if(count($ids) > 0) {
                if(isset($ids) && count($ids) > 0) {
                    $primaryKey = $class::getKey();
                    $list = $class::find()->where([$primaryKey => $ids])->all();
                    if($list) {
                        foreach($list as $key => $model) {
                            $attributes = $model->attributes;
                            $attributes = UtilityArray::ua('created_time,modified_time,created_by,modified_by', $attributes);
                            $name = false;
                            foreach($attributes as $key => $value) {
                                if(preg_match('/name/',$key)) {
                                    $name = $key;
                                    break;
                                }
                            }

                            $modelCopy = new $class;
                            $modelCopy->attributes = $attributes;
                            if($name) {
                                $nameValue = $attributes[$name];
                                do {
                                    $demCopy = UtilityHtmlFormat::nameCopy($nameValue);
                                    $nameValue = preg_replace("/ - copy[0-9]*$/","",$nameValue)." - copy".$demCopy;
                                    $count = $class::find()->where("$name = :name",array(":name" => $nameValue))->count();
                                } while($count);

                                $modelCopy->$name = $nameValue;
                            }
                            
                            $modelCopy->save(false);
                        }
                        $this->jsonResponse(200,'Copy successfully!');
                    } else {
                        $this->jsonResponse(400,'Have not found list');
                    }
                } else {
                    $this->jsonResponse(400,'Have not found id');
                }
            }
        } else {
            $this->jsonResponse(400,'Have not found settings table');
        }
    }
    
    public function actionLeftdetail() {
        return $this->renderAjax('@app/views/layouts/partial/left_detail');
    }
    
    public function actionMultiadd() {
        $this->getTable($this->action);
        if($this->setting_table) {
            $model = new $this->setting_table->class;
            return $this->multiadd($model, true);
        } else {
            $this->pageDenied('Setting table has not found id');
        }
    }
    
    public function multiadd($model, $flag = true) {
        $model->load(r()->get());
        if(r()->isPost) {
            $multiPost = r()->post();
            $className = className($this->setting_table->class);
            $error = [];
            $listModel = [];
            $flagError = false;
            
            foreach($multiPost[$className] as $key => $item) {
                $model = new $this->setting_table->class;
                $post = array(
                    $className => $item,
                );
                $model->loadAll($post);
                $model->trimAttrValue();
                if(!$model->validate()) {
                    $error[] = ActiveForm::validate($model);
                    $flagError = true;
                } else {
                    $error[] = false;
                }
                $listModel[] = $model;
            }
            if(!$flagError) {
                foreach($listModel as $key => $model) {
                    $model->save();
                }
                $this->jsonResponse(200);
            } else {
                $this->jsonResponse(300,$error);
            }
        }
        if($flag) {
            $views = '@app/views/layouts/settings/multiadd';
        } else {
            $views = 'multiadd';
        }
        return $this->Prender($views, [
            'model' => $model,
        ]);
    }
}
