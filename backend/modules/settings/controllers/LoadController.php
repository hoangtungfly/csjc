<?php

namespace backend\modules\settings\controllers;

use common\core\controllers\GlobalController;
use common\models\admin\SettingsMappingSearch;
use common\utilities\UtilityArray;
use common\utilities\UtilityHtmlFormat;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class LoadController extends GlobalController {
    public function actionMultimenu() {
        $class = $this->getParam('classcommon');
        return $this->multimenu($class);
    }
    
    public function actionMenu() {
        $class = $this->getParam('classcommon');
        return $this->menu($class);
    }
    
    public function menu($class) {
        $id = (int)$this->getParam('id');
        if($id) {
            $listMenu = $class::find()->select('id,name,pid')->where('pid = '.$id)->all();
            if($listMenu) {
                $list = ArrayHelper::map($listMenu,'id','name');
                echo $this->renderPartial('multimenu', [
                    'listMenu'  => $list,
                    'id'        => '',
                ]);
            }
        }
    }
    
    public function multimenu($class) {
        $idget = $this->getParam('id');
        $id = (int)$idget;
        $list = $this->getAllmenu($class,$id);
        if($list) {
            if($idget === '')$idget = -1;
            foreach($list as $key => $item) {
                $listMenu = $class::find()->select('id,name,pid')->where('pid = '.$item)->all();
                if($listMenu || $item == 0) {
                    $array = array();
                    if($item == 0) {
                        $array[0] = '-- Parent --';
                    }
                    $listMenu = ArrayHelper::map($listMenu,'id','name');
                    $array = $array + $listMenu;
                    $id_main = isset($list[$key + 1]) ? $list[$key + 1] : $idget;
                    echo $this->renderPartial('multimenu', [
                        'listMenu'  => $array,
                        'id'        => $id_main,
                    ]);
                }
            }
        }
    }
    
    public function getAllMenu($class,$id) {
        $list = [];
        if($id) {
            $model = $class::find()->select('id,pid,name')->where('id = '.$id)->one();
            while($model) {
                $list[] = $model->id;
                $model = $class::find()->select('id,pid,name')->where('id = '.$model->pid)->one();
            }
        }
        $list[] = 0;
        $list = array_reverse($list);
        return $list;
    }
    
    public function actionRole() {
        $id = $this->getParam('id');
        $modelMapping = SettingsMappingSearch::findOne($id);
        $html = '';
        if($modelMapping) {
            $value = $this->getParam('value');
            $query = new Query;
            $app = $query
                    ->select($modelMapping->select_id . ',' . $modelMapping->select_name . ',pid')
                    ->from($modelMapping->table_name)
                    ->where($modelMapping->where)
                    ->all();
            $menu = UtilityArray::ArrayPC($app);
            $html = $this->renderPartial('role', ['menu' => $menu, 'array' => $value != '' ? explode(',', $value) : []]);
        }
        $this->jsonResponse(200, $html);
    }
    
    
    public function actionMappingmultiallmenu() {
        $id = $this->getParam('id');
        $pid = $this->getParam('pid');
        if ($pid != 0) {
            $modelMapping = SettingsMappingSearch::findOne($id);
            $data = SettingsMappingSearch::mapping($modelMapping, array('pid'=>$pid));
            $class = strtolower(UtilityHtmlFormat::className($modelMapping->table_name));
            $className = $modelMapping->class;
            $model = $className::findOne($pid);
            $this->countMapping($data,$className);
            $html = '';
            $array = array();
            if ($data && $model) {
                $html .= '<div class="fl " id="div-multiallmenu-'.$pid.'">'.'<h6>'.$model->name.'</h6>'.  Html::dropDownList(
                        "", 
                        "", 
                        $data, 
                        array(
                            'class' => 'multiallmenu',
                            'multiple' => true,
                            'data-mappingid' => $id,
                            'style' => 'width:180px;height:150px;',
                            'id'    => 'multiallmenu_'.$class.'_'.$pid,
                            'data-class' => $class,
                        )
                ).'</div>';
            }
            $this->jsonResponse(200,$html);
        } else {
            $this->jsonResponse(200,'');
        }
    }
    
    public function actionMultiallmenu() {
        $id = $this->getParam('mappingid');
        $arrayWhere = $_POST;
        unset($arrayWhere['mappingid']);
        unset($arrayWhere['value']);
        $modelMapping = SettingsMappingSearch::findOne($id);
        unset($arrayWhere['menudid']);
        $class = strtolower(UtilityHtmlFormat::className($modelMapping->table_name));
        $value = $this->getParam('value');
        $value = ($value == "") ? 0 : $value;
        $name = 'pid';
        $arrayId = explode(',',$value);
        $array = array();
        foreach($arrayId as $k => $v) {
            $array[$v] = $v;
        }
        $html = '';
        $dem = 0;
        $pid1 = '0';
        $a = array();
        $className = $modelMapping->class;
        foreach ($array as $key => $pid) {
            $data = array();
            if ($dem == 0)$data[0] = '-- Select --';
            $model = $className::findOne($pid1);
            $namecha = $model ? $model->name : 'Parent';
            $data += SettingsMappingSearch::mapping($modelMapping, array_merge(array('pid' => $pid1),$arrayWhere));
            if (count($data) > 0) {
                $strId = array();
                foreach($data as $k => $v) {
                    if(isset($array[$k])) {
                        $strId[] = $k;
                        if($k != $pid)
                            $a[$k] = $k;
                    }
                }
                $this->countMapping($data,$className);
                $dataval = implode(",",$strId);
                $html .= '<div class="fl" id="div-multiallmenu-'.$pid1.'">'.'<h6>&nbsp;'.$namecha.'&nbsp;</h6>'.Html::dropDownList(
                        "", 
                        $strId, 
                        $data, 
                        array(
                            'class' => 'multiallmenu',
                            'multiple' => true,
                            'data-mappingid' => $modelMapping->mapping_id,
                            'style' => 'width:205px;height:150px;',
                            'data-pid' => $pid1,
                            'id'    => 'multiallmenu_'.$class.'_'.$pid1,
                            'data-val'  => $dataval > 0 ? $dataval : '',
                            'data-class' => $class,
                        )
                ).'</div>';
            }
            $pid1 = $pid;
            $dem++;
        }
        $this->jsonResponse(200,$html);
    }
    
    public function countMapping(&$data,$class) {
        if($data && count($data) > 0) {
            foreach($data as $key => $value) {
                if($class::find()->where('pid = '.$key)->count() > 0 && $value != "-- Select --") {
                    $data[$key] = $value.'⇒';
                }
            }
            return $data;
        }
    }
}