<?php

namespace common\models\admin;

use common\core\cache\GlobalFileCache;
use common\core\model\LinkPagerAngular;
use common\models\admin\SettingsTable;
use common\models\category\CategoriesSearch;
use common\utilities\UtilityArray;
use common\utilities\UtilityDateTime;
use common\utilities\UtilityFile;
use common\utilities\UtilityHtmlFormat;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/**
 * SettingsTableSearch represents the model behind the search form about `common\models\admin\SettingsTable`.
 */
class SettingsTableSearch extends SettingsTable {

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

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'table_id' => $this->table_id,
            'created_time' => $this->created_time,
            'modified_time' => $this->modified_time,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'status' => $this->status,
            'checkview' => $this->checkview,
            'checksearch' => $this->checksearch,
            'beginimport' => $this->beginimport,
            'columncheck' => $this->columncheck,
            'columnaction' => $this->columnaction,
            'columnid' => $this->columnid,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'table_name', $this->table_name])
                ->andFilterWhere(['like', 'condition', $this->condition])
                ->andFilterWhere(['like', 'orderby', $this->orderby])
                ->andFilterWhere(['like', 'attrsearch', $this->attrsearch])
                ->andFilterWhere(['like', 'attrarange', $this->attrarange])
                ->andFilterWhere(['like', 'attrchoice', $this->attrchoice])
                ->andFilterWhere(['like', 'join', $this->join])
                ->andFilterWhere(['like', 'excel', $this->excel]);

        return $dataProvider;
    }

    public static function getAll() {
        $key = self::getKeyFileCache('getall');
        $cache = new GlobalFileCache();
        $app = $cache->get($key);
        if (!$app) {
            $app = self::find()->all();
            $cache->set($key, $app);
        }
        return $app;
    }

    public function afterSave($insert, $changedAttributes) {
        $this->deleteDefaultFileCacheDefault();
        parent::afterSave($insert, $changedAttributes);
    }

    public function deleteDefaultFileCacheDefault() {
        $arrayKeyCache = array(
            'getformbytable_' . $this->table_id,
            'getfieldbytable_' . $this->table_id,
            'getall*',
        );
        $list = MenuAdminSearch::find()->where(['table_id' => $this->table_id])->all();
        foreach ($list as $item) {
            UtilityFile::deleteFile(LINK_PUBLIC_ADMIN_PARTIAL . $item->module . '/' . $item->controller . '/' . $item->action . '.html');
        }
        $this->deleteCacheFile($arrayKeyCache);
    }

    public function beforeDelete() {
        SettingsFieldSearch::deleteAll('table_id = ' . $this->table_id);
        SettingsFormSearch::deleteAll('table_id = ' . $this->table_id);
        SettingsGridSearch::deleteAll('table_id = ' . $this->table_id);
        $this->deleteDefaultFileCacheDefault();
        return parent::beforeDelete();
    }

    public function getListData($menu_admin) {
        $result = [];
        $model = new $this->class;
        $model->unsetAttributes();
        $attrGet = r()->get();
        $className = className($this->class);
        $model->load([$className => $attrGet]);
        $table_name = $model->tableName();
        $primaryKey = $model->getKey();
        $modelAttributes = false;
        if (isset($attrGet) && count($attrGet) > 0) {
            $modelAttributes = $attrGet;
            if (isset($modelAttributes[$primaryKey]) && $modelAttributes[$primaryKey] != "") {
                $model->$primaryKey = $modelAttributes[$primaryKey];
            }
        }

        $listGrid = SettingsGridSearch::listGridByTable($this->table_id);
        $arraySelect = ['`' . $table_name . '`.' . $primaryKey];
        $arrayName = [];
        $arrayType = [];
        $whereJoin = [];
        $arrayAlias = [];
        $arrayMapping = [];
        if ($listGrid) {
            /* @var $item SettingsGridSearch */
            foreach ($listGrid as $key => $item) {
                $attribute = $item->attribute;
                $arraySelect[] = $attribute;

                //mapping
                $mapping = [];
                $alias = preg_replace('/`|(\.[a-zA-Z0-9-_]+)/', '', $attribute);
                $name = preg_replace('/`([a-zA-Z0-9-_])+`\./', '', $attribute);
                $modelValue = $alias == $table_name ? $model->$name : '';
                $mappingMenu = [];
                if ($item->mapping_id != '' && $item->mapping_id != 0) {
                    $mapping = SettingsMappingSearch::mappingAll($item->mapping_id, '', null, false, $item->filter == 'multimenu' ? true : false);
                    if ($item->filter == 'multiallmenu') {
                        $mappingMenu = SettingsMappingSearch::mappingAllMenu($item->mapping_id, $modelValue);
                    }
                } else if ($item->choice == 1) {
                    $modelField = SettingsFieldSearch::find()->where('table_id = ' . $item->table_id . ' AND field_name = "' . $name . '"')->one();
                    if ($modelField && $modelField->field_options != '') {
                        $choice = json_decode($modelField->field_options);
                        if (isset($choice->callfunction) && $choice->callfunction != "") {
                            $mapping = UtilityArray::callFunction($choice->callfunction);
                        } else if (isset($choice->options) && count($mapping) == 0) {
                            $mapping = ArrayHelper::map($choice->options, 'value', 'label');
                        }
                    }
                }
                
                if (count($mapping)) {
                    $result[$name . '_mapping'] = $mapping;
                }
                
                if (count($mappingMenu)) {
                    $result[$name . '_mappingMenu'] = $mappingMenu;
                }

                if ($modelAttributes && isset($modelAttributes[$name]) && $item->alias_attribute != "" && strpos($item->alias_attribute, ',') !== NULL) {
                    $array = explode(',', $item->alias_attribute);
                    $a1 = trim($array[0]);
                    $a2 = trim($array[1]);
                    $whereJoin[$name] = [$a1, $a2];
                }
                $name1 = $name;
                while (isset($arrayType[$name1])) {
                    $name1 .= '1';
                }
                $arrayType[$name1] = $item->value;
                $arrayName[] = ['attribute' => $name1, 'name' => $name, 'value' => $item->value];
                $arrayAlias[] = $alias;
                $arrayMapping[] = $mapping;
            }
        }
        /* @var $query ActiveQuery */
        $query = $model->find();
        $query->select($arraySelect)->where($this->condition);
        $this->join = trim($this->join);
        if ($this->join != "") {
            $join = json_decode($this->join);
            foreach ($join as $key => $std) {
                $query->joinWith($std->with, true, $std->type);
            }
        }
        if (count($whereJoin)) {
            $md = $model->find()->one();
            foreach ($whereJoin as $k => $array) {
                $tb = $array[0];
                $attr = $array[1];
                if ($tbl_tb = $md->$tb) {
                    $tbl_name = $tbl_tb->tableName();
                    $query->andFilterWhere(['like', $tbl_name . '.' . $attr, $model->$k]);
                }
                $model->$k = null;
            }
        }
        if ($this->orderby != '' && !isset($attrGet['sort'])) {
            $query->orderBy($this->orderby);
        }
        /* @var $dataProvider ActiveDataProvider */
        $dataProvider = $model->searchAdmin($query);
        if (isset($attrGet['pagesize'])) {
            $dataProvider->pagination->defaultPageSize = $attrGet['pagesize'];
        }
        $result['total'] = $dataProvider->getTotalCount();
        $listData = $dataProvider->getModels();
        $list = false;
        if ($listData) {
            $list = [];
            foreach ($listData as $key => $item) {
                $itemList = [$primaryKey => $item->{$primaryKey}];
                foreach ($arrayName as $key => $array) {
                    $itemGrid = $listGrid[$key];
                    $value = $this->getValueItem($arrayAlias[$key], $table_name, $item, $array['name'], $itemGrid, $arrayMapping[$key]);
                    $this->getValueByType($array['value'], $array['attribute'], $value, $itemList, $itemGrid, $item);
                }
                $list[] = $itemList;
            }
        }
        $result[$table_name . '_list'] = $list;
        $attributes = UtilityArray::removeValueNull($model->attributes);
        $pagesize = isset($attrGet['pagesize']) ? $attrGet['pagesize'] : PAGESIZE;
        $attributes['pagesize'] = (string)$pagesize;
        $result[$className] = $attributes;
        $link_menu = preg_replace('/\?(.*)/', '', $menu_admin->linkMenu());
        $result['pager'] = LinkPagerAngular::run($result['total'], $pagesize, $link_menu);
        if(ANGULARJS_WRITEFILE) {
            file_get_contents(HTTP_HOST . '/settings/generator/gridview?id=' . $menu_admin->id);
        }
        return $result;
    }

    public function getValueByType($type, $attribute, $value, &$itemList, $itemGrid, $item) {
        switch ($type) {
            case '' : $itemList[$attribute] = $value;
                break;
            case 'noencode' : $itemList[$attribute] = $item->$attribute;
                break;
            case 'iconcheck' : $itemList[$attribute] = $value;
                break;
            case 'a' :
                $itemList[$attribute] = $value;
                break;
            case 'json' :
                $html = '';
                if ($value != "") {
                    $array = (array) json_decode($value);
                    foreach ($array as $key => $v) {
                        $html .= "<p>$key => $v</p>";
                    }
                }
                $itemList[$attribute] = $html;
                break;
            case 'arrayjson' :
                $html = '';
                if ($value != "") {
                    $array = (array) json_decode($value);
                    foreach ($array as $key => $valueItem) {
                        $valueItem = (array) $valueItem;
                        
                        foreach ($valueItem as $k => $v) {
                            $html .= '<p>';
                            if($k == 'category_id' && ($m = CategoriesSearch::findOne($v))) {
                                $v = $m->name;
                            }
                            $html .= " $k => $v ";
                            $html .= '</p>';
                        }
                        
                    }
                }
                $itemList[$attribute] = $html;
                break;
            case 'img' :
                $itemList[$attribute] = $item->getimage([], $value);
                $itemList[$attribute . '30'] = $item->getimage([30, 30], $value);
                break;
            case 'number' :
                $itemList[$attribute] = UtilityHtmlFormat::numberFormat($value);
                break;
            case 'price' :
                $itemList[$attribute] = UtilityHtmlFormat::numberFormatPrice($value);
                break;
            case 'float' :
                $itemList[$attribute] = UtilityHtmlFormat::numberFloat($value);
                break;
            case 'floatPrice' :
                $itemList[$attribute] = UtilityHtmlFormat::numberFloatPrice($value);
                break;
            case 'date' :
                $itemList[$attribute] = UtilityDateTime::formatDate($value);
                break;
            case 'datetime' :
                $itemList[$attribute] = UtilityDateTime::formatDateTime($value);
                break;
            case 'ip' :
                $itemList[$attribute] = UtilityHtmlFormat::convertStringToIp($value);
                break;
            case 'checkbox' :
                $itemList[$attribute] = $value;
                if ($itemGrid->template != "") {
                    $arrayParent = explode(';', $itemGrid->template);
                    foreach ($arrayParent as $key => $v) {
                        $array = explode(' ', trim(preg_replace('/(\s)+/', ' ', $v)));
                        $label = $array[0];
                        unset($array[0]);
                        if ($label == $value) {
                            $itemList[$attribute . '_label'] = isset($array[1]) ? implode(' ', $array) : '';
                            break;
                        }
                    }
                }
                break;
            default :
                $itemList[$attribute] = $value;
                break;
        }
    }

    public function getValueItem($alias, $table_name, $data, $name, $item, $mapping) {
        $value = $alias != $table_name ? $data->$alias->$name : $data->$name;
        $classTableAll = $this->class;
        if ($item->alias_attribute != "" && preg_match('/,/', $item->alias_attribute)) {
            $array = explode(',', $item->alias_attribute);
            $a1 = trim($array[0]);
            $a2 = trim($array[1]);
            if ($name == 'pid') {
                if ($data->pid != 0)
                    $model = $classTableAll::getInstance($data->$name);
                else
                    $model = false;
            } else {
                $model = $data->$a1;
            }
            if ($model) {
                $value = $model->$a2;
            } else {
                $value = '';
            }
        }
        /* count SQL */
        if ($item->countsql != '') {
            $countsql = UtilityArray::replaceArray($data->getAttributes(), $item->countsql);
            $value = app()->db->createCommand($countsql)->queryScalar();
        }
        // link
        $link = '';
        /* LINK */
        if ($item->link != '' && $item->value != 'button') {
            $link = UtilityArray::replaceArray($data->getAttributes(), $item->link);
        }

        $valueOld = $value;
        //value mapping
        if (count($mapping) > 0) {
            if (isset($mapping[$value])) {
                $value = $mapping[$value];
            } else {
                if (preg_match('/,/', $value)) {
                    $ar = explode(',', $value);
                    $value = array();
                    foreach ($ar as $k => $v) {
                        if (isset($mapping[$v])) {
                            $value[] = ' ' . preg_replace('/^[-]+ /', '', $mapping[$v]);
                        }
                    }
                    $value = count($value) > 0 ? implode(',', $value) : '';
                } else {
                    $value = '';
                }
            }
        }
        $template = '';
        if ($item->template != '' && $item->value != 'checkbox') {
            $attributes = $data->getAttributes();
            $attributes['value'] = $item->format ? Html::encode($value) : $value;

            $template = UtilityArray::replaceArray($attributes, $item->template);
            $valueArray = explode('|', trim($value));
            $value = '';
            if (str_replace('{0}', '', $template) != $template) {
                foreach ($valueArray as $k1 => $v1) {
                    $valueArray2 = explode(',', $v1);
                    $str = $template;
                    foreach ($valueArray2 as $k2 => $v2) {
                        $str = str_replace('{' . $k2 . '}', (ctype_digit(strval($v2)) ? number_format($v2) : $v2), $str);
                    }
                    $value .= $str;
                }
            } else {
                $value = $template;
            }
        }
        return $value;
    }

    public function getValueItemString($alias, $table_name, $data, $name, $item, $mapping) {
        $value = $alias != $table_name ? '$data->' . $alias . '->' . $name : '$data->' . $name;
        $html = "";
        $classTableAll = $this->class;
        if ($item->alias_attribute != "" && preg_match('/,/', $item->alias_attribute)) {
            $array = explode(',', $item->alias_attribute);
            $a1 = trim($array[0]);
            $a2 = trim($array[1]);
            if ($name == 'pid') {
                $html .= "\$value = '';\n";
                $html .= "if (\$data->pid != 0) { \n";
                $html .= '$model = \\' . $classTableAll . "::getInstance(\$data->" . $name . ");\n";
                $html .= "} else {\n";
                $html .= "\$model = false;\n";
                $html .= "}\n";
            } else {
                $html .= '$model = $data->' . $a1 . ";\n";
            }
            $html .= "if (\$model) {\n";
            $html .= "\$value = \$model->$a2;\n";
            $html .= "}\n";
            $value = "\$value";
        }
        /* count SQL */
        if ($item->countsql != '') {
            $html .= "\$countsql = \common\utilities\UtilityArray::replaceArray(\$data->getAttributes(), \"" . str_replace('"', '\\"', $item->countsql) . "\");\n";
            $html .= "\$value = app()->db->createCommand(\$countsql)->queryScalar();\n";
            $value = "\$value";
        }

        /* LINK */
        $link = '';
        if ($item->link != '' && $item->value != 'button') {
            $html .= "\$itemList['$name'_link] = \common\utilities\UtilityArray::replaceArray(\$data->getAttributes(), \"" . str_replace('"', '\\"', $item->link) . "\");\n";
        }

        $valueOld = $value;
        //value mapping
        if (count($mapping) > 0) {
            $html .= "\$value = \common\utilities\UtilityArray::getValueByKey(\${$name}_mapping,$value);\n";
            $value = "\$value";
        }
        return [
            'html'  => $html,
            'value' => $value,
        ];
    }
    
    public function getValueByTypeString($type, $attribute, $itemGrid, $value) {
        $html = '';
        switch ($type) {
            case 'json' :
                $html .= "\$itemList['$attribute'] = \common\utilities\UtilityArray::printJson($value);\n";
                break;
            case 'arrayjson' :
                $html .= "\$itemList['$attribute'] = \common\utilities\UtilityArray::printArrayJson($value);\n";
                break;
            case 'img' :
                $html .= "\$itemList['$attribute'] = \$data->getimage([], $value);\n";
                $html .= "\$itemList['{$attribute}30'] = \$data->getimage([30, 30], $value);\n";
                break;
            case 'number' :
                $html .= "\$itemList['$attribute'] = \common\utilities\UtilityHtmlFormat::numberFormat($value);\n";
                break;
            case 'price' :
                $html .= "\$itemList['$attribute'] = \common\utilities\UtilityHtmlFormat::numberFormatPrice(\$str);\n";
                break;
            case 'float' :
                $html .= "\$itemList['$attribute'] = \common\utilities\UtilityHtmlFormat::numberFloat(\$str);\n";
                break;
            case 'floatPrice' :
                $html .= "\$itemList['$attribute'] = \common\utilities\UtilityHtmlFormat::numberFloatPrice(\$str);\n";
                break;
            case 'date' :
                $html .= "\$itemList['$attribute'] = \common\utilities\UtilityDateTime::formatDate($value);\n";
                break;
            case 'datetime' :
                $html .= "\$itemList['$attribute'] = \common\utilities\UtilityDateTime::formatDateTime($value);\n";
                break;
            case 'ip' :
                $html .= "\$itemList['$attribute'] = \common\utilities\UtilityHtmlFormat::convertStringToIp($value);\n";
                break;
            case 'checkbox' :
                $html .= "\$itemList['$attribute'] = $value;\n";
                if ($itemGrid->template != "") {
                    $arrayParent = explode(';', $itemGrid->template);
                    $html .= "\$itemList['{$attribute}_label'] = $value == ";
                    foreach ($arrayParent as $key => $v) {
                        $array = explode(' ', trim(preg_replace('/(\s)+/', ' ', $v)));
                        $label = $array[0];
                        unset($array[0]);
                        if(!$key) {
                            $html .= $label . " ? '" . (isset($array[1]) ? implode(' ', $array) : "''") . "' : '";
                            
                        } else {
                            $html .= (isset($array[1]) ? implode(' ', $array) : "''"). "';\n";
                        }
                    }
                }
                break;
            default :
                $html .= "\$itemList['".$attribute."'] = $value;\n";
                break;
        }
        return $html;
    }

}
