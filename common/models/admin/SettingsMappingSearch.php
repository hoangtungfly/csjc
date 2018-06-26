<?php

namespace common\models\admin;

use common\core\cache\GlobalFileCache;
use common\models\admin\SettingsMapping;
use common\utilities\UtilityArray;
use common\utilities\UtilityHtmlFormat;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * SettingsMappingSearch represents the model behind the search form about `common\models\admin\SettingsMapping`.
 */
class SettingsMappingSearch extends SettingsMapping {

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($query) {
        if (!$query)
            $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'mapping_id' => $this->mapping_id,
            'created_time' => $this->created_time,
            'modified_time' => $this->modified_time,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'mapping_name', $this->mapping_name])
                ->andFilterWhere(['like', 'select_id', $this->select_id])
                ->andFilterWhere(['like', 'select_name', $this->select_name])
                ->andFilterWhere(['like', 'table_name', $this->table_name])
                ->andFilterWhere(['like', 'where', $this->where])
                ->andFilterWhere(['like', 'odr', $this->odr]);

        return $dataProvider;
    }

    public static function mapping($modelMapping, $array = array(), $model = false, $type = false) {
        $where = $modelMapping->where;
        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                if ($value != '') {
                    $wherepid = $key . ' = ' . $value;
                    $where = ($where != '') ? $where . ' AND ' . $wherepid : $wherepid;
                }
            }
        }
        if ($model) {
            $where = UtilityArray::replaceArray($model->attributes, $where);
        }
        $select = $modelMapping->select_id . ',' . $modelMapping->select_name;

        $cache = new GlobalFileCache();
        if ($type) {
            $select .= ',pid';
            $key = self::tbname($modelMapping->table_name) . '/mapping_multi_' . ($where ? '_' . UtilityHtmlFormat::stripUnicode($where) : '');
        } else {
            $key = self::tbname($modelMapping->table_name) . '/mapping_' . ($where ? '_' . UtilityHtmlFormat::stripUnicode($where) : '');
        }
        $app = $cache->get($key);
        if (!$app) {
            $query = new Query;
            $app = $query
                    ->select($select)
                    ->from(strtolower($modelMapping->table_name))
                    ->where($where)
                    ->orderBy($modelMapping->odr)
                    ->groupBy($modelMapping->group_by)
                    ->all();
            if ($type) {
                $arraymenu = UtilityArray::ArrayPC($app);
                $app = array();
                UtilityArray::arrayLevel($app, $arraymenu, true);
            } else {
                $app = ArrayHelper::map($app, $modelMapping->select_id, $modelMapping->select_name);
            }
            $cache->set($key, $app);
        }
        return $app;
    }

    public static function mappingAllMenu($pk, &$value) {
        $modelMapping = self::findOne($pk);
        if ($modelMapping->where != '')
            $modelMapping->where .= ' AND ';
        $where = $modelMapping->where;
        $modelMapping->where .= 'pid = 0';
        $arrayP0[] = self::mapping($modelMapping);
        if ($value != 0 && $value != '' && ($modelMapping->table_name == 'categories')) {
            $class = $modelMapping->class;
            $arrayParent = $class::breakcrumb($value);
            $value = array();
            foreach ($arrayParent as $key => $item) {
                $value[] = $item->id;
                $modelMapping->where = $where . ' pid = ' . $item->id;
                $arrayP0[] = self::mapping($modelMapping);
            }
        }
        return $arrayP0;
    }

    public static function mappingAll($pk, $tablename = '', $pid = null, $model = false, $type = false) {
        $modelMapping = self::findOne($pk);
        if ($modelMapping) {
            $modelMapping->cal_func = trim($modelMapping->cal_func);
            if ($modelMapping->cal_func != "") {
                return UtilityArray::callFunction($modelMapping->cal_func);
            } else {
                if ($modelMapping->mapping_name == 'Table Name') {
                    return UtilityArray::getNameInArrayTable($tablename, array('id'));
                } else if ($modelMapping->mapping_name == 'Table') {
                    $dsn = app()->components['db']['dsn'];
                    $array = explode('=', $dsn);
                    $db_name = $array[count($array) - 1];
                    $listTable = app()->db->createCommand("SHOW TABLES")->queryAll();
                    $idStr = 'Tables_in_' . $db_name;
                    $list = ArrayHelper::map($listTable, $idStr, $idStr);
                    return $list;
                } else {
                    $array = self::mapping($modelMapping, $pid, $model, $type);
                    return $array;
                }
            }
        }
    }

    public static function mappingOne($mapping_id, $id) {
        if (($mapping_id = (int) $mapping_id) && ($modelMapping = self::findOne($mapping_id))) {
            $where = ($modelMapping->where = trim($modelMapping->where)) ? $modelMapping->where . ' AND ' : $modelMapping->where;
            if (UtilityHtmlFormat::isInteger($id)) {
                $where .= $modelMapping->select_id . ' = ' . $id;
            } else {
                $where .= $modelMapping->select_id . " = '" . $id . "'";
            }
            $query = new Query;
            $app = $query
                    ->select($modelMapping->select_id . ',' . $modelMapping->select_name)
                    ->from(strtolower($modelMapping->table_name))
                    ->where($where)
                    ->orderBy($modelMapping->odr)
                    ->one();
            if ($app) {
                return [$app[$modelMapping->select_id] => $app[$modelMapping->select_name]];
            } else {
                return [];
            }
        }
        return false;
    }

    public static function genArrayTable($pk, $name = 'list', $type = false, $label = 'Select') {
        $model = self::findOne((int) $pk);
        $result = '';
        if ($model) {

            if ($model->cal_func != "") {

                $result = '$' . $name . "_mapping = ";

                if ($type) {
                    $result .= "['' => '-- '.Yii::t('admin', '{$label}').' --'] + ";
                }

                $result .= '\common\utilities\UtilityArray::callFunction(\'' . str_replace("'", "\\'", $model->cal_func) . '\');';
            } else {

                $result = '$' . $name . "_mapping = ";

                if ($type) {
                    $result .= "['' => '-- '.Yii::t('admin', '{$label}').' --'] + ";
                }
                if ($model->mapping_name == 'Table Name') {
                    $result .= "\common\utilities\UtilityArray::getNameInArrayTable(\$model->tableName());";
                } else if ($model->mapping_name == 'Table') {
                    $result .= "\common\utilities\UtilityArray::getTable();";
                } else {
                    $query = "'select `{$model->select_id}`,`{$model->select_name}` from `$model->table_name`";
                    if ($model->where != "")
                        $query .= " where " . $model->where;
                    $query .= "'";
                    $data_query = "app()->db->createCommand({$query})->queryAll()";
                    $result .= "\yii\helpers\ArrayHelper::map({$data_query},'{$model->select_id}','{$model->select_name}');";
                }
            }
        }
        return $result;
    }

    public static function genArrayTableFields($table_id, $name, $modelField = false, $type = false) {
        if (!$modelField) {
            $modelField = SettingsFieldSearch::find()->where('table_id = :table_id AND field_name = :field_name', [':table_id' => $table_id, ':field_name' => $name])->one();
        }
        $result = '';
        if ($modelField && $modelField->field_options != '') {
            $choice = json_decode($modelField->field_options);
            if (isset($choice->callfunction) && $choice->callfunction != "") {
                $result = '$' . $name . '_mapping = ';
                if ($type) {
                    $result .= "['' => '-- '.Yii::t('admin', '{$modelField->label}').' --'] + ";
                }
                $result .= "\common\utilities\UtilityArray::callFunction('{$choice->callfunction}');";
            } else if (isset($choice->options)) {
                $mapping = ArrayHelper::map($choice->options, 'value', 'label');
                $result = '$' . $name . '_mapping = ';
                if ($type) {
                    $result .= "['' => '-- '.Yii::t('admin', '{$modelField->label}').' --'] + ";
                }
                $result .= "[\n";
                if (is_array($mapping)) {
                    foreach ($mapping as $key => $value) {
                        $result .= "\t'$key' => '$value',\n";
                    }
                }
                $result .= "];\n";
            }
        }
        return $result;
    }

    public static function genPropertyArrayTable($id) {
        $model = self::findOne((int) $id);
        $result = '';
        if ($model) {
            if ($model->cal_func != "") {
                $result = '$list' . UtilityHtmlFormat::className($model->table_name) . 'Call';
            } else {
                $result = '$list' . UtilityHtmlFormat::className($model->table_name);
            }
        }
        return $result;
    }

    public static function genPropertyArrayTableFields($table_id, $name, $modelField = false) {
        if (!$modelField) {
            $modelField = SettingsFieldSearch::find()->where('table_id = :table_id AND field_name = :field_name', [':table_id' => $table_id, ':field_name' => $name])->one();
        }
        $result = '';
        if ($modelField && $modelField->field_options != '') {
            $result = '$list_' . $name;
        }
        return $result;
    }

    public static function getAll() {
        $key = self::getKeyFileCache('getall');
        $cache = new GlobalFileCache();
        $app = $cache->get($key);
        if (!$app) {
            $app = self::find()->orderBy('mapping_id desc')->all();
            $cache->set($key, $app);
        }
        return $app;
    }

    public static function genDatahtml($modelField, $type = true) {
        if ($modelField->mapping_id != 0) {
            $data['html'] = self::genArrayTable($modelField->mapping_id, $modelField->field_name, $type, $modelField->label);
            $data['property'] = self::genPropertyArrayTable($modelField->mapping_id);
        } else {
            $data['html'] = self::genArrayTableFields($modelField->table_id, $modelField->field_name, $modelField, $type);
            $data['property'] = self::genPropertyArrayTableFields($modelField->table_id, $modelField->field_name, $modelField);
        }
        return $data;
    }

    public function beforeSave($insert) {
        $this->deleteDefaultCache();
        return parent::beforeSave($insert);
    }

    public function beforeDelete() {
        $this->deleteDefaultCache();
        return parent::beforeDelete();
    }

    public function deleteDefaultCache() {
        $arrayKeyCache = array(
            self::tbname($this->table_name) . '/mapping_*',
            'getall',
        );
        $cache = new GlobalFileCache();
        foreach ($arrayKeyCache as $k => $key) {
            $cache->delete($key);
        }
    }

}
