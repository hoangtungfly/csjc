<?php

/**
 *
 * @author dungnguyenanh
 */

namespace backend\modules\system\controllers;

use backend\controllers\BackendController;
use common\models\admin\SettingsFieldSearch;
use common\models\admin\SettingsFiles;
use common\models\admin\SettingsImages;
use common\models\settings\SystemSettingSearch;
use yii\base\DynamicModel;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

class SystemsettingsController extends BackendController {
    public function beforeAction($action) {
        return parent::beforeAction($action);
    }
    public $type;
    
    public function actionIndex() {
        $this->type = 'system_settings_common';
        $type = $this->getParam('type');
        $this->type = $type ? $type : $this->type;
        return parent::actionUpdate();
    }
    
    public function update($model, $flag = false) {
        $listField = SettingsFieldSearch::listFieldByTable($this->setting_table->table_id);
        $attributes = SettingsFieldSearch::getAttributesByListField($listField);
        $attributes[] = 'isNewRecord';
        $list = SystemSettingSearch::getAll($this->type);
        $model = new DynamicModel($attributes);
        $model->isNewRecord = false;
        $this->addRule($model, $listField);
        $required = [];
        foreach ($listField as $item) {
            if ($item->required) {
                $required[] = $item->field_name;
            }
        }
        foreach ($list as $key => $value) {
            if (in_array($key, $attributes)) {
                $model->$key = $value;
            }
        }
        if ($model) {
            $this->process($model, $list);
            if ($flag) {
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

    public function addRule($model, $listField) {
        $required = [];
        $emailRequired = [];
        /* var $model DynamicModel */
        foreach ($listField as $key => $item) {
            if ($item->required) {
                $required[] = $item->field_name;
            }
            if ($item->field_type == 'email') {
                $emailRequired[] = $item->field_name;
            }
        }
        if ($required) {
            $model->addRule($required, 'required');
        }
        if ($required) {
            $model->addRule($emailRequired, 'email');
        }
    }

    public function process($model, $list = false) {
        $model->load(r()->get());

        if (r()->isPost) {
            $dataPost = r()->post();
            $dataModel = $dataPost['DynamicModel'];
            foreach ($dataModel as $key => $value) {
                $model->$key = $value;
            }
            if ($model->validate()) {
                $model = new SystemSettingSearch();
                SystemSettingSearch::deleteAll(['type' => $this->type,'lang' => app()->language]);
                $attributes = ['created_time', 'created_by', 'lang', 'option_key', 'option_value', 'type'];
                $rows = [];
                foreach ($dataModel as $key => $value) {
                    $rows[] = [time(), user()->id, app()->language, $key, $value, $this->type];
                    if($key == 'web_main' && isset($list[$key])) {
                        $model->replaceWebMain($value);
                    }
                }
                app()->db->createCommand()->batchInsert(SystemSettingSearch::tableName(), $attributes, $rows)->execute();
                $model->deleteDefaultFileCacheDefault();
                $this->jsonResponse(200);
            } else {
                echo json_encode(ActiveForm::validate($model));
                app()->end();
            }
        }
    }

}
