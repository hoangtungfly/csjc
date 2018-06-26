<?php

namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\models\admin\MenuAdmin;
use common\models\admin\MenuAdminSearch;
use common\utilities\UtilityArray;
use Yii;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * AuthGroupsController implements the CRUD actions for AuthGroups model.
 */
class MenuadminController extends BackendController {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function process($model, $list = false) {
        if ($model->load(r()->post())) {
            $flag = $model->isNewRecord ? true : false;
            $model->trimAttrValue();
            if ($model->validate()) {
                $model->save();
                if ($flag) {
                    $this->createFile($model);
                } else {
                    $this->createFile($model);
                    $primaryKey = $model->primaryKey();
                    if ($primaryKey && $primaryKey[0]) {
                        $primaryKey = $primaryKey[0];
                    } else {
                        echo 'Has primary key > 1';
                        die();
                    }
                }
                $this->jsonResponse(200);
                app()->end();
            } else {
                echo json_encode(ActiveForm::validate($model));
                app()->end();
            }
        }
    }

    /**
     * 
     * @param MenuAdminSearch $model
     */
    public function createFile($model) {
        $linkMain = Yii::getAlias('@app') . '/modules/';
        $linkModule = $linkMain . $model->module;

        /* create dir module */
        if (!is_dir($linkModule)) {
            mkdir($linkModule);
        }

        /* Create file module */
        $module = $model->module;
        $module{0} = strtoupper($module{0});
        $file_module = $linkModule . '/' . $module . 'Module.php';
        if (!is_file($file_module)) {
            file_put_contents($file_module, $this->renderPartial('generator/module', [
                        'model' => $model,
                        'module' => $module,
            ]));
            $linkMainLocal = Yii::getAlias('@app') . '/config/main-local.php';

            $replace = "'" . $model->module . "' => [\n";
            $replace .="\t'class' => 'backend\\modules\\" . $model->module . "\\" . $module . "Module',\n\t],\n\t";

            $content = file_get_contents($linkMainLocal);
            $content = str_replace('// not delete', $replace . '// not delete', $content);
            file_put_contents($linkMainLocal, $content);
        }

        /* Create dir controllers */
        $linkController = $linkModule . '/controllers';
        if (!is_dir($linkController)) {
            mkdir($linkController);
        }

        /* Create file controller */
        $controller = $model->controller;
        $controller{0} = strtoupper($controller{0});
        $linkFileController = $linkController . '/' . $controller . 'Controller.php';
        if (!is_file($linkFileController)) {
            file_put_contents($linkFileController, $this->renderPartial('generator/controller', [
                        'model' => $model,
                        'controller' => $controller,
            ]));
        }

        /* Create dir views */
        $linkViews = $linkModule . '/views';
        if (!is_dir($linkViews)) {
            mkdir($linkViews);
        }

        /* Create dir views controller */
        $linkViewController = $linkViews . '/' . $model->controller;
        if (!is_dir($linkViewController)) {
            mkdir($linkViewController);
        }
    }

    public function actionDelete() {
        $id = (int) $this->getParam('id');
        $model = MenuAdmin::findOne($id);
        if ($model) {
            $model2 = MenuAdmin::findOne(['pid' => $model->id]);
            if (!$model2) {
                $model->delete();
                $this->jsonResponse(200, "Delete menuadmin successfully!");
            } else {
                $this->jsonResponse(400, "Delete menuadmin not successfully! Because this record has children.");
            }
        } else {
            $this->jsonResponse(400, "Record not exists");
        }
    }

    public function actionDeleteall() {
        $ids = $this->getParam('ids');
        $list = MenuAdmin::find()->where(['id' => $ids])->all();
        if ($list) {
            $listDelete = UtilityArray::ArrayPC($list);
            if (isset($listDelete[0])) {
                foreach ($listDelete[0] as $key => $item) {
                    if (isset($listDelete[$key])) {
                        foreach ($listDelete[$key] as $key2 => $item2) {
                            if (isset($listDelete[$key2])) {
                                foreach ($listDelete[$key2] as $key3 => $item3) {
                                    $item3->delete();
                                }
                            }
                            $item2->delete();
                        }
                    }
                    $item->delete();
                }
            } else {
                foreach ($list as $key => $item) {
                    $item->delete();
                }
            }
            $this->jsonResponse(200, "Delete all menuadmin successfully!");
        } else {
            $this->jsonResponse(400, "Record not exists");
        }
    }

    public function actionArrange() {
        $listMenu = MenuAdminSearch::find()->select('id,name,pid')->where('status = 1')->orderBy('odr')->all();
        if ($listMenu) {
            $listArrange = UtilityArray::ArrayPC($listMenu);
            return $this->renderPartial("arrange", [
                        'listArrange' => $listArrange,
            ]);
        }
    }

    public function actionArrangesuccess() {
        if ($dataPost = r()->post()) {
            $arrayIdValue = explode('|', $dataPost['update']);
            foreach ($arrayIdValue as $key => $str) {
                $array = explode(',', $str);
                $id = $array[0];
                $value = $array[1];
                $model = MenuAdmin::findOne($id);
                if ($model) {
                    $model->odr = $value + 1;
                    $model->save(false);
                }
            }
            $this->jsonResponse(200);
        }
    }

}