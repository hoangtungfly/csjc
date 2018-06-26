<?php

namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\models\admin\SettingsFieldSearch;
use common\models\admin\SettingsFormSearch;
use common\models\admin\SettingsMappingSearch;
use common\models\admin\SettingsTableSearch;
use common\utilities\UtilityArray;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class BuildformController extends BackendController {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionLoadlisttable() {
        if ($this->isAjax()) {
            $listTable = SettingsTableSearch::getAll();
            $html = $this->render('listtable', array('listTable' => $listTable));
            $this->jsonResponse(200, $html);
        }
    }

    public function actionLoadlistmapping() {
        if ($this->isAjax()) {
            $listMapping = SettingsMappingSearch::getAll();
            $html = '';
            if ($listMapping) {
                $array = ArrayHelper::map($listMapping, 'id', 'name');
                foreach ($array as $key => $value) {
                    $html .= '<option value="' . $key . '">' . $value . '</option>';
                }
            }
            $this->jsonEncode(array('code' => 200, 'html' => $html));
        }
    }

    public function actionIndex() {
        $listTable = SettingsTableSearch::getAll();
        $modelTable = $listTable[0];
        $table_id = (int) $this->getParam('table_id');
        $multi_add = (int) $this->getParam('multi_add');
        if ($table_id) {
            foreach ($listTable as $key => $item) {
                if ($table_id == $item->table_id) {
                    $modelTable = $item;
                    break;
                }
            }
        } else {
            $table_id = $modelTable->table_id;
        }
        $tb = UtilityArray::getNameInArrayTableNotAlias($modelTable->table_name);
        $listForm = SettingsFormSearch::listFormByTable($table_id, $multi_add);
        if (!$listForm) {
            $listForm[0] = SettingsFormSearch::insertFormByTableAndMultiadd($table_id, $multi_add);
        }
        $modelForm = $listForm[0];
        $form_id = (int) $this->getParam('form_id');
        $form_id = $form_id ? $form_id : $modelForm->form_id;

        $listField = SettingsFieldSearch::listFieldByForm($form_id);
        SettingsFieldSearch::AddAttributeName($listField, $tb);
        $mapping = ArrayHelper::map(SettingsMappingSearch::getAll(), 'mapping_id', 'mapping_name');

        return $this->Prender('index', array(
                    'listTable' => $listTable,
                    'tb' => $tb,
                    'mapping' => $mapping,
                    'table_id' => $table_id,
                    'listForm' => $listForm,
                    'modelForm' => $modelForm,
                    'form_id' => $form_id,
                    'listField' => $listField,
                    'multi_add' => $multi_add,
        ));
    }

    public function actionLoadform() {
        if ($this->isAjax()) {
            $table_id = (int) $this->getParam('id');
            $multi_add = (int) $this->getParam('multi_add');
            $modelTable = SettingsTableSearch::findOne($table_id);
            $tb = UtilityArray::getNameInArrayTableNotAlias($modelTable->table_name);

            $listForm = SettingsFormSearch::listFormByTable($table_id, $multi_add);
            if (!$listForm) {
                $listForm[0] = SettingsFormSearch::insertFormByTableAndMultiadd($table_id, $multi_add);
            }
            $modelForm = $listForm[0];
            $form_id = (int) $this->getParam('form_id');
            $form_id = $form_id ? $form_id : $modelForm->form_id;

            $listField = SettingsFieldSearch::listFieldByForm($form_id);

            $mapping = UtilityArray::ClassToArray(SettingsMappingSearch::getAll(), 'mapping_id', 'mapping_name');

            $this->Prender('form', array(
                'table_id' => $table_id,
                'tb' => $tb,
                'mapping' => $mapping,
                'listForm' => $listForm,
                'modelForm' => $modelForm,
                'form_id' => $form_id,
                'listField' => $listField,
                'multi_add' => $multi_add,
                    ), 200);
        }
    }

    public function actionGetform() {
        if ($this->isAjax()) {
            $form_id = $this->getParam('id');
            $modelForm = SettingsFormSearch::findOne($form_id);
            $table_id = $modelForm->table_id;
            $modelTable = SettingsTableSearch::findOne($table_id);
            $tb = UtilityArray::getNameInArrayTableNotAlias($modelTable['table_name']);

            $listField = SettingsFieldSearch::listFieldByForm($form_id);
            SettingsFieldSearch::AddAttributeName($listField, $tb);

            $mapping = UtilityArray::ClassToArray(SettingsMappingSearch::getAll(), 'mapping_id', 'mapping_name');

            $html = $this->renderPartial('_item', array(
                'table_id' => $table_id,
                'tb' => $tb,
                'mapping' => $mapping,
                'modelForm' => $modelForm,
                'form_id' => $form_id,
                'listField' => $listField,
            ));
            $this->jsonResponse(200, $html);
        }
    }

    public function actionDeleteform() {
        $form_id = (int) $this->getParam('id');
        $modelForm = SettingsFormSearch::findOne($form_id);
        $table_id = $modelForm->table_id;
        $modelForm->delete();
        $this->renderForm($table_id);
    }

    public function actionCreateform() {
        $table_id = (int) $this->getParam('id');
        $multi_add = (int) $this->getParam('multi_add');
        $form = new SettingsFormSearch;
        $form->table_id = $table_id;
        $form->form_name = 'unname';
        $form->odr = $this->getParam('count') + 1;
        $form->multi_add = $multi_add;
        $form->save(false);
        $this->renderForm($table_id);
    }

    public function renderForm($table_id) {
        $listForm = SettingsFormSearch::listFormByTable($table_id);
        $html = $this->renderPartial('section', array(
            'listForm' => $listForm,
        ));
        $this->jsonResponse(200, $html);
    }

    public function actionUpdateform() {
        if ($this->isAjax()) {
            $dataPOST = $this->getPOST();
            $Dform = $dataPOST['SettingsFormSearch'];
            $Dform['fields'] = urldecode($Dform['fields']);
            $modelForm = SettingsFormSearch::findOne($Dform['form_id']);
            $modelForm->attributes = $Dform;
            $modelForm->save(false);
            SettingsFieldSearch::deleteAll('form_id = :form_id', array(':form_id' => $modelForm->form_id));
            $SettingsField = json_decode($Dform['fields']);
            if ($SettingsField) {
                foreach ($SettingsField as $key => $value) {
                    $value->field_options = json_encode($value->field_options);
                    $modelField = new SettingsFieldSearch;
                    $modelField->attributes = (array) $value;
                    $modelField->field_name = isset($value->name) ? $value->name : '';
                    $modelField->form_id = $modelForm->form_id;
                    $modelField->table_id = $modelForm->table_id;
                    $modelField->multi_add = $modelForm->multi_add;
                    $modelField->save(false);
                }
            }

            $this->jsonResponse(200);
        }
    }

}
