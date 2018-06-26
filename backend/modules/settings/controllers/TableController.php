<?php

namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\models\admin\SettingsFieldSearch;
use common\models\admin\SettingsFormSearch;
use common\models\admin\SettingsGridSearch;
use common\models\admin\SettingsTableSearch;
use common\utilities\UtilityArray;
use common\utilities\UtilityHtmlFormat;
use yii\filters\VerbFilter;

class TableController extends BackendController {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionCopy() {

        $id = (int) $this->getParam('id');
        $model = SettingsTableSearch::findOne($id);
        if ($model) {
            $attributes = $model->attributes;
            $attributes = UtilityArray::ua('created_time,modified_time,created_by,modified_by', $attributes);
            $name = false;
            foreach ($attributes as $key => $value) {
                if (preg_match('/name/', $key)) {
                    $name = $key;
                    break;
                }
            }

            if ($name) {
                $modelCopy = new SettingsTableSearch;
                $modelCopy->attributes = $attributes;
                $nameValue = $attributes[$name];
                do {
                    $demCopy = UtilityHtmlFormat::nameCopy($nameValue);
                    $nameValue = preg_replace("/ - copy[0-9]*$/", "", $nameValue) . " - copy" . $demCopy;
                    $count = SettingsTableSearch::find()->where("$name = :name", array(":name" => $nameValue))->count();
                } while ($count);

                $modelCopy->$name = $nameValue;
                $modelCopy->save(false);

                $listForm = SettingsFormSearch::find()->where(['table_id' => $id])->all();
                if ($listForm) {
                    foreach ($listForm as $key => $itemForm) {
                        $modelFormCopy = new SettingsFormSearch;
                        $attributesForm = $itemForm->attributes;
                        $attributesForm = UtilityArray::ua('created_time,modified_time,created_by,modified_by', $attributesForm);
                        $modelFormCopy->attributes = $attributesForm;
                        $modelFormCopy->table_id = $modelCopy->table_id;
                        $modelFormCopy->save(false);
                        $listFields = SettingsFieldSearch::find()->where(['form_id' => $itemForm->form_id])->all();
                        if ($listFields) {
                            foreach ($listFields as $keyField => $itemField) {
                                $modelFieldCopy = new SettingsFieldSearch;
                                $attributesField = $itemField->attributes;
                                $attributesField = UtilityArray::ua('created_time,modified_time,created_by,modified_by', $attributesField);
                                $modelFieldCopy->attributes = $attributesField;
                                $modelFieldCopy->table_id = $modelFormCopy->table_id;
                                $modelFieldCopy->form_id = $modelFormCopy->form_id;
                                $modelFieldCopy->save(false);
                            }
                        }
                    }
                }

                $listGrid = SettingsGridSearch::find()->where(['table_id' => $id])->all();
                if ($listGrid) {
                    foreach ($listGrid as $key => $itemGrid) {
                        $modelGridCopy = new SettingsGridSearch;
                        $attributesGrid = $itemGrid->attributes;
                        $attributesGrid = UtilityArray::ua('created_time,modified_time,created_by,modified_by', $attributesGrid);
                        $modelGridCopy->attributes = $attributesGrid;
                        $modelGridCopy->table_id = $modelCopy->table_id;
                        $modelGridCopy->save(false);
                    }
                }

                $this->jsonResponse(200, 'Copy successfully!');
            } else {
                $this->jsonResponse(400, 'Have not found name');
            }
        } else {
            $this->jsonResponse(400, 'Have not found model');
        }
    }

}
