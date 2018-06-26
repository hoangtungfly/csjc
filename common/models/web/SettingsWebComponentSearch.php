<?php

namespace common\models\web;

use common\core\cache\GlobalFileCache;
use common\core\dbConnection\GlobalActiveRecord;
use common\core\enums\StatusEnum;
use common\utilities\UtilityArray;
use common\utilities\UtilityFile;
use common\utilities\UtilityFunction;
use common\utilities\UtilityHtmlFormat;

class SettingsWebComponentSearch extends SettingsWebComponent {

    public function afterSave($insert, $changedAttributes) {
        $this->updateFunction($this->getLinkClass(), $this->function_name, $this->getContentFunction());
        if ($this->cache) {
            $this->updateFunction($this->getLinkClass(), 'afterSave', $this->contentFunctionAftersave(), false);
            $this->updateFunction($this->getLinkClass(), 'deleteDefaultFileCacheDefault', $this->contentDefaultFileCacheDefault(), false);
            $this->updateFunction($this->getLinkClass(), 'beforeDelete', $this->contentFunctionBeforeSave(), false);
            $this->updateFunctionCache();
        }
        $this->updateSelectStr();
        $this->deleteDefaultFileCacheDefault();
        parent::afterSave($insert, $changedAttributes);
    }

    public function updateSelectStr() {
        $comma = $this->function_name . ".";
        $select = "";
        $arraySelectStr = [];
        $class = className($this->class);
        if ($this->all_one) {
            $comma = "item.";
            $fixe = $this->parent ? "['{$this->fixe}0']" : '';
            $arraySelectStr[] = "item in " . $this->function_name.$fixe;
            $arraySelectStr[] = ' ng-repeat="item in ' . $this->function_name . $fixe . '" ';
        }
        if (!$this->buildArgv()) {
            $result = UtilityArray::callFunction('return \\' . $this->class.'::'.$this->function_name.'();');
            if($result) {
                $select = [];
                if(isset($result[0]) && $result[0]) {
                    $result = $result[0];
                    $comma = "item.";
                } else {
                    $comma = $this->function_name . ".";
                    if($this->parent && isset($result[$this->fixe.'0'])) {
                        $result = array_shift($result[$this->fixe.'0']);
                        $comma = "item.";
                    }
                }
                foreach($result as $key => $item) {
                    $select[] = $key;
                }
                $select = implode(',',$select);
            }
        }
        if(!$select) {
            if ($this->select) {
                $select = $this->select;
            } else {
                $select_function = "return " . GlobalActiveRecord::getSelectQuery($this->class) . ';';
                $select = UtilityArray::callFunction($select_function);
            }
        }
        if (in_array($class, ['CategoriesSearch', 'NewsSearch', 'ProductSearch'])) {
            $select .= ',link_main';
        }
        if (in_array($class, ['NewsSearch', 'ProductSearch'])) {
            $select .= ',image_main';
        }
        if ($select) {
            $arraySelect = explode(',', $select);
            foreach ($arraySelect as $value) {
                $value = '{{' . $comma . preg_replace("/.*?(\.)/",'', $value) . '}}';
                if(!in_array($value, $arraySelectStr)) {
                    $arraySelectStr[] = $value;
                }
            }
            $this->select_str = implode(',', $arraySelectStr);
            app()->db->createCommand("update settings_web_component set select_str = :select_str where id = :id ",[
                ':select_str' => $this->select_str,
                ':id'         => $this->id,
            ])->execute();
        }
    }

    public function beforeDelete() {
        $this->deleteDefaultFileCacheDefault();
        return parent::beforeDelete();
    }

    public function deleteDefaultFileCacheDefault() {
        $arrayKeyCache = array(
            'ListWebComponent*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }

    public function updateFunctionCache() {
        $contentFile = filegetcontents($this->getLinkClass());
        if ($contentFile) {
            $contentFunction = UtilityFunction::getFunction($contentFile, 'deleteDefaultFileCacheDefault');
            if ($contentFunction) {
                if (strpos($contentFunction, $this->function_name) === false) {
                    $contentFunctionWrite = str_replace("\$arrayKeyCache = array(\n        ", "\$arrayKeyCache = array(\n            '{$this->function_name}*',\n        ", $contentFunction);
                    $contentFile = str_replace($contentFunction, trim($contentFunctionWrite), $contentFile);
                    UtilityFile::fileputcontents($this->getLinkClass(), $contentFile);
                }
            }
        }
    }

    public function contentDefaultFileCacheDefault() {
        $content = "    public function deleteDefaultFileCacheDefault() {\n        ";
        $content .= "\$arrayKeyCache = array(\n        ";
        $content .= ");\n        ";
        $content .= "\$this->deleteCacheFile(\$arrayKeyCache);\n    ";
        $content .= "}";
        return $content;
    }

    public function contentFunctionAftersave() {
        $content = "    public function afterSave(\$insert, \$changedAttributes) {\n        ";
        $content .= "\$this->deleteDefaultFileCacheDefault();\n        ";
        $content .= "parent::afterSave(\$insert, \$changedAttributes);\n        ";
        $content .= "parent::afterSave(\$insert, \$changedAttributes);\n    ";
        $content .= "}";
        return $content;
    }

    public function contentFunctionBeforeSave() {
        $content = "    public function beforeDelete() {\n        ";
        $content .= "\$this->deleteDefaultFileCacheDefault();\n        ";
        $content .= "}";
        return $content;
    }

    public function getLinkClass() {
        return APPLICATION_PATH . '/' . str_replace('\\', '/', $this->class) . '.php';
    }

    public $space = "        ";

    public function getContentFunction() {
        $content = '    public' . ($this->static ? ' static' : '') . ' function ' . $this->function_name . '(';
        $content .= $this->buildArgv();
        $content .= ") {\n        ";
        if ($this->cache) {
            $this->space .= "    ";
            $content.= "\$keyCache = self::getKeyFileCache('{$this->function_name}');\n        ";
            $content.= "\$cache = new \common\core\cache\GlobalFileCache();\n        ";
            $content.= "\$result = \$cache->get(\$keyCache);\n        ";
            $content.= "if (!\$result) {\n            ";
            $content.= "\$result = [];\n            ";
        }
        if ($this->by_command) {
            $command = str_replace("'", "\\'", $this->command);
            $content .= "\$result = " . $this->buildArrayHelperMap("app()->db->createCommand(\"$command\")->queryScalar()") . ";";
        } else {
            $content .= "\$query = self::find();\n" . $this->space;
            $content .= $this->buildSelect();
            $content .= $this->buildWhere();
            $content .= $this->buildJoin();
            $content .= $this->buildGroupBy();
            $content .= $this->buildOrder();
            $content .= $this->buildLimit();
            $content .= $this->buildOffset();
            $content .= "\$result = ".($this->parent ? 'UtilityArray::ArrayPC(' : '')."self::";
            $argv = '';
            $argv .= $this->flag_key_id ? ',' . $this->flag_key_id : '';
            $argv .= $this->w_h ? ',[' . $this->w_h . ']' : '';
            if ($this->all_one) {
                $content .= "getArrayByObject(" . $this->buildArrayHelperMap("\$query->all()$argv") . ")".($this->parent ? ",'".$this->fixe."')" :'').";";
            } else {
                $content .= "getObject(\$query->one()$argv);";
            }
        }
        if ($this->cache) {
            $content .= "\n            \$cache->set(\$keyCache, \$result);";
            $content .= "\n        }";
        }
        $content .= "\n        return \$result;";
        $content .= "\n    }";
        return $content;
    }

    public function buildArrayHelperMap($content) {
        if ($this->array_helper_map) {
            $array = explode(',', $this->array_helper_map);
            if (count($array) == 2) {
                $content = "\yii\helpers\ArrayHelper::map({$content}, '{$array[0]}', '{$array[1]}')";
            }
        }
        return $content;
    }

    public function buildGroupBy() {
        if ($this->group_by) {
            return "\$query->groupBy('{$this->group_by}');\n" . $this->space;
        }
        return "";
    }

    public function buildOrder() {
        if ($this->order) {
            return "\$query->orderBy('{$this->order}');\n" . $this->space;
        }
        return "";
    }

    public function buildOffset() {
        if ($this->offset) {
            return "\$query->offset({$this->offset});\n" . $this->space;
        }
        return "";
    }

    public function buildLimit() {
        if ($this->limit) {
            return "\$query->limit({$this->limit});\n" . $this->space;
        }
        return "";
    }

    public function buildArgv() {
        if ($this->argv) {
            $argv = json_decode($this->argv);
            $array_argv = [];
            foreach ($argv as $item) {
                if ($item->operator) {
                    $array_argv[] = $item->attribute . ' ' . $item->operator . ' ' . $item->value;
                } else {
                    $array_argv[] = $item->attribute;
                }
            }
            return implode(' ,', $array_argv);
        }
        return '';
    }

    public function buildSelect() {
        $select = $this->select;
        if (!$select) {
            $select = GlobalActiveRecord::getSelectQuery($this->class);
        } else {
            if ($select{0} != '[') {
                $select = "'" . $select . "'";
            }
        }
        if ($select) {
            return "\$query->select($select);\n" . $this->space;
        }
        return '';
    }

    public function buildWhere() {
        if ($this->where) {
            $result = [];
            $where = json_decode($this->where);
            foreach ($where as $item) {
                $operator = $item->operator ? $item->operator : '=';
                $attribute = $item->attribute;
                $value = $item->value;
                if ($attribute) {
                    if (preg_match('/like/', strtolower($attribute))) {
                        $value = "'%" . $attribute . "%'";
                    } else if (!UtilityHtmlFormat::isInteger($value) && strpos($value, '$') === false && !preg_match('/(-\>)|(\:\:)/', $value)) {
                        $value = "'" . $value . "'";
                    }
                    $result[] = "\$query->andFilterWhere(['$operator',\"$attribute\",$value]);";
                }
            }
            return implode("\n" . $this->space, $result) . "\n" . $this->space;
        }
        return '';
    }

    public function buildJoin() {
        if ($this->join_width_or) {
            if ($this->join) {
                $json = json_decode($this->json);
                $result = [];
                foreach ($json as $item) {
                    $type = $item->type;
                    if (!$type)
                        $type = 'INNER JOIN';
                    $table = $item->table;
                    $on = $item->on;
                    $result[] = "\$query->join('$type','$table','$on');";
                }
                return implode("\n" . $this->space, $result) . "\n" . $this->space;
            }
        } else {
            if ($this->join_with) {
                $json = json_decode($this->join_with);
                $result = [];
                foreach ($json as $item) {
                    $result[] = "\$query->joinWith('{$item->joinwith}');";
                }
                return implode("\n" . $this->space, $result) . "\n" . $this->space;
            }
        }
        return "";
    }

    public static function ListWebComponent($modelWebTemplate = false) {
        $keyCache = self::getKeyFileCache('ListWebComponent');
        $cache = new \common\core\cache\GlobalFileCache();
        $result = $cache->get($keyCache);
        if (!$result) {
            $result = [];
            $query = self::find();
            $query->andFilterWhere(['=','status',StatusEnum::STATUS_ACTIVED]);
            $result = self::getArrayByObject($query->all());
            $cache->set($keyCache, $result);
        }
        $array = $modelWebTemplate && $modelWebTemplate->component_id ? explode(',',$modelWebTemplate->component_id) : [];
        foreach ($result as $key => $item) {
            $item['php_str1'] = "\$".$item['function_name'].' = \\' . $item['class'] . '::' . $item['function_name'] . '('.self::buildArgvStr($item).');';
            $item['php_str2'] = "'".$item['function_name'].'\' => $'.$item['function_name'].',';
            $item['js_str'] = "\$http.get(LINKJSON + 'templatereplace').success(function(rs) {\$rootScope = angular.extend(\$rootScope, rs);});";
            if(in_array($item['id'], $array)) {
                $item['active'] = true;
            }
            
            $result[$key] = $item;
        }
        return $result;
    }

    public static function buildArgvStr($item) {
        if ($item['argv']) {
            $argv = json_decode($item['argv'], true);
            $array_argv = [];
            foreach ($argv as $item) {
                if (isset($item['attribute']) && $item['attribute']) {
                    $array_argv[] = $item['attribute'];
                }
            }
            return implode(' ,', $array_argv);
        }
        return '';
    }
    
}
