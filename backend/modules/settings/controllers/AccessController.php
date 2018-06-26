<?php

namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\core\dbConnection\GlobalActiveRecord;
use common\core\enums\StatusEnum;
use common\models\admin\SettingsFieldSearch;
use common\models\admin\SettingsFormSearch;
use common\models\admin\SettingsGridSearch;
use common\models\admin\SettingsIconSearch;
use common\models\admin\SettingsMappingSearch;
use common\models\admin\SettingsTableSearch;
use common\utilities\UtilityArray;
use common\utilities\UtilityDirectory;
use common\utilities\UtilityHtmlFormat;
use common\utilities\UtilityUrl;
use Yii;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

class AccessController extends BackendController {

    function actionCheckgrid() {
        if ($this->isAjax()) {
            $table_id = (int) $this->getParam('table_id');
            $id = (int) $this->getParam('id');
            if ($id == -1 || $id == -2 || $id == -3) {
                $model = SettingsTableSearch::findOne($table_id);
                if ($model) {
                    switch ($id) {
                        case -1: $name = "columnid";
                            break;
                        case -2: $name = "columnaction";
                            break;
                        case -3: $name = "columncheck";
                            break;
                    }
                    $model->$name = ($model->$name == 1) ? 0 : 1;
                    $model->save(false);
                }
            } else {
                $model = SettingsGridSearch::findOne($id);
                if ($model) {
                    $model->status = $model->status ? 0 : 1;
                    $model->save(false);
                }
            }
        }
    }

    public function actionArrange() {
        if ($this->isAjax()) {
            $table_id = (int) $this->getParam('table_id');
            $modelTable = SettingsTableSearch::findOne($table_id);
            if (!$modelTable)
                return true;
            $className = $modelTable->class;

            $primaryKey = $className::getKey();

            $attr = $this->getParam('attr');
            $attrodr = $this->getParam('attrodr');
            $flag = $this->getParam('flag') == 1 ? true : false;
            $label = $this->getParam('label');
            $gridid = '';
            if (isset($_GET['gridid'])) {
                $array = explode('||', $_GET['gridid']);
                $gridid .= ' AND ' . $array[0] . ' = ' . $array[1];
            }
            if ($flag) {
                if (isset($_POST['update'])) {
                    $arrayIdValue = explode('|', $_POST['update']);
                    foreach ($arrayIdValue as $key => $str) {
                        $array = explode(',', $str);
                        $id = $array[0];
                        $value = $array[1];
                        $model = $className::findOne($id);
                        $model->$attrodr = $value + 1;
                        $model->pid = $_POST['pid'];
                        $model->save(false);
                    }
                    $this->jsonResponse(200);
                }
                $list = $className::find()->where($attr . ' = ' . StatusEnum::STATUS_ACTIVED . $gridid)->orderBy($attrodr)->all();
                if ($list) {
                    $listArrange = UtilityArray::arrayPC($list);
                    $html = $this->renderPartial('arrange', [
                        'listArrange' => $listArrange,
                        'className' => $className,
                        'attr' => $attr,
                        'url' => UtilityUrl::realURL(),
                        'primaryKey' => $primaryKey,
                        'label' => $label,
                    ]);
                    $this->jsonResponse(200, $html);
                }
            } else {
                if (isset($_POST['update'])) {
                    $arrayIdValue = explode('|', $_POST['update']);
                    foreach ($arrayIdValue as $key => $str) {
                        $array = explode(',', $str);
                        $id = $array[0];
                        $value = $array[1];
                        $model = $className::findOne($id);
                        $model->$attrodr = $value + 1;
                        $model->save(false);
                    }
                    $this->jsonResponse(200);
                }
                $listArrange = $className::find()->where($attr . ' = ' . StatusEnum::STATUS_ACTIVED . $gridid)->orderBy($attrodr)->all();
                if ($listArrange) {
                    $html = $this->renderPartial('arrangeone', [
                        'listArrange' => $listArrange,
                        'className' => $className,
                        'attr' => $attr,
                        'url' => UtilityUrl::realURL(),
                        'primaryKey' => $primaryKey,
                        'label' => $label,
                    ]);
                    $this->jsonResponse(200, $html);
                }
            }
        }
    }

    public function actionUpdatestatus() {
        $name = $this->getParam('name');
        $class = base64_decode($this->getParam('class'));
        $value = (int) $this->getParam('value');
        $arrayId = $this->getParam('ids');
        if (count($arrayId) > 0) {
            $primaryKey = $class::getKey();
            $list = $class::find()->where([$primaryKey => $arrayId])->all();

            foreach ($list as $key => $item) {
                $item->$name = $value;
                $item->save(false);
            }

            $this->jsonResponse(200);
        }
    }

    public function actionArrangeform() {
        $table_id = $this->getParam('table_id');
        $arrayOrder = $this->getParam('arrayOrder');
        if ($arrayOrder && is_array($arrayOrder) && count($arrayOrder) > 0) {
            $key = 1;
            foreach ($arrayOrder as $value) {
                app()->db->createCommand('update `settings_form` set `odr` = :odr where form_id = :form_id', [
                    ':odr' => $key,
                    ':form_id' => $value,
                ])->execute();
                $key++;
            }
            $model = SettingsFormSearch::findOne($value);
            $model->deleteDefaultFileCacheDefault();
        }
    }

    public function actionLoadname() {
        if ($this->isAjax()) {
            $id = (int) $this->getParam('id');
            $modelTable = SettingsTableSearch::findOne($_POST['id']);
            if ($modelTable) {
                $data[''] = '-- select --';
                $data += UtilityArray::getNameInArrayTable($modelTable->table_name);
//                if (trim($modelTable->join) != '') {
//                    $modelTable->join = trim($modelTable->join);
//                    $array = explode('|', $modelTable->join);
//                    foreach ($array as $k => $v) {
//                        $v = explode(',', $v);
//                        if (isset($v[1])) {
//                            $a = explode(' ', UtilityHtmlFormat::deleteSpace($v[1]));
//                            $tableName = str_replace('`', '', $a[0]);
//                            $alias = (isset($a[1])) ? str_replace('`', '', $a[1]) : $tableName;
//                            $data += UtilityArray::getNameInArrayTable($tableName, array('id', 'modified_by'));
//                        }
//                    }
//                }
                $this->jsonResponse(200, $data);
            }
        }
    }

    public function actionUpdatefast() {
        if (r()->isAjax) {
            $table_id = (int) $this->getParam('table_id');
            $id = (int) $this->getParam('id');
            $nameupdate = trim($this->getParam('nameupdate'));
            $modelTable = SettingsTableSearch::findOne($table_id);
            if ($modelTable) {
                $class = $modelTable->class;
                $model = $class::findOne($id);
                if ($model) {
                    $modelField = SettingsFieldSearch::findOne(['table_id' => $table_id, 'field_name' => $nameupdate]);
                    echo $this->renderPartial('updatefast', array(
                        'model' => $model,
                        'modelField' => $modelField,
                        'modelTable'    => $modelTable,
                        'id'    => $id,
                    ));
                } else {
                    echo 'table update is not found';
                }
            } else {
                echo 'settings_table is not found';
            }
        }
    }

    public function actionUpdatefastprocess() {
        if (r()->isAjax) {
            $table_id = (int) $this->getParam('table_id');
            $id = (int) $this->getParam('id');
            $nameupdate = trim($this->getParam('nameupdate'));
            $modelTable = SettingsTableSearch::findOne($table_id);
            if ($modelTable) {
                $class = $modelTable->class;
                $model = $class::findOne($id);
                if ($model) {
                    $model->load(r()->post());
                    $model->trimAttrValue();
                    if ($model->validate()) {
                        $model->save(false);
                        if(in_array($nameupdate, ['image','logo','avatar'])) {
                            $this->jsonResponse(200,[
                                'link_30'    => $model->getimage([30,30],$model->$nameupdate),
                                'link_main' => $model->getimage([],$model->$nameupdate),
                            ]);
                        } else {
                            $this->jsonResponse(200);
                        }
                        
                    } else {
                        echo json_encode(ActiveForm::validate($model));
                        app()->end();
                    }
                } else {
                    echo 'table update is not found';
                }
            } else {
                echo 'settings_table is not found';
            }
        }
    }

    public function actionIcon() {
        if ($this->isAjax()) {
            $data = ArrayHelper::map(SettingsIconSearch::find()->select('name')->all(), 'name', 'name');
            $this->jsonResponse(200, $data);
        }
    }

    public function actionLoadchosen() {
        if ($this->isAjax()) {
            $id = $this->getParam('id');
            $fieldid = (int) $this->getParam('fieldid');
            $name = $this->getParam('name');
            $valueid = $this->getParam('value');
            $modelField = SettingsFieldSearch::findOne($fieldid);
            if ($modelField) {
                $modelTable = SettingsTableSearch::findOne($modelField->table_id);
                if ($modelTable) {
                    $class = $modelTable->class;
                    if ($id) {
                        $model = $class::findOne($id);
                    } else {
                        $model = new $class;
                    }
                    $data[''] = '-- ' . Yii::t('admin', $modelField->label) . ' --';
                    if ($model) {
                        $model->$name = $valueid;
                        if ($modelField->mapping_id != 0) {
                            $data += SettingsMappingSearch::mappingAll($modelField->mapping_id, $model->tableName(), NULL, $model);
                        } else {
                            $field_options = (array) json_decode($modelField->field_options);
                            if (isset($field_options['callfunction']) && trim($field_options['callfunction']) != "") {
                                $callfunction = trim($field_options['callfunction']);
                                $data += UtilityArray::callFunction($callfunction);
                            } else {
                                if ($field_options['options'] && count($field_options['options']) > 0) {
                                    $data += ArrayHelper::map($field_options['options'], 'value', 'label');
                                }
                            }
                        }

                        $this->jsonResponse(200, $data);
                    } else {
                        echo 'model not found';
                    }
                } else {
                    echo 'modelTable not found';
                }
            } else {
                echo 'modelField not found';
            }
        }
    }

    public function actionLoadmenu() {
        echo $this->renderPartial('@app/views/layouts/partial/menu');
    }

    public function actionTokeninput() {
        $mapping_id = (int) $this->getParam('mapping_id');
        $key_search = trim($this->getParam('term'));
        echo GlobalActiveRecord::getTokenValue(false, $mapping_id, $key_search);
        die();
    }

    public function actionDeletecache() {
        UtilityDirectory::deleteDiretory([
            APPLICATION_PATH . '/cache',
            APPLICATION_PATH . '/runtime',
            DIR_LINKPUBLIC_PARTIAL,
            APPLICATION_PATH .'/backend/runtime',
            APPLICATION_PATH .'/frontend/runtime',
        ]);
        $this->jsonResponse(200);
    }

}
