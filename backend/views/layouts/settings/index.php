<?php

use backend\widgets\GridView;
use common\core\enums\StatusEnum;
use common\models\admin\SettingsFieldSearch;
use common\models\admin\SettingsGridSearch;
use common\models\admin\SettingsMappingSearch;
use common\utilities\UtilityArray;
use common\utilities\UtilityDateTime;
use common\utilities\UtilityHtmlFormat;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$loadiframe = (int) $this->getParam('loadiframe');
$listGrid = SettingsGridSearch::listGridByTable($this->context->table_id);
$query = $model->find();
$table_name = $model->tableName();

$primaryKey = $model->getKey();
$modelTable = $this->context->setting_table;
$classTableAll = $modelTable->class;
$array = explode('\\', $classTableAll);
$classTable = $array[count($array) - 1];
$attributeLabels = $model->attributeLabels();

$get = r()->get();
$getTable = isset($get[$classTable]) ? $get[$classTable] : false;

$whereJoin = [];

$column = [
    [
        'filter' => !$loadiframe ? '<div class="fl dev_search_filter search_filter_-1">&nbsp;' . Html::activeTextInput($model, $primaryKey, ['placeholder' => $attributeLabels[$primaryKey], 'class' => 'form-control', 'style' => 'width:100px;']) . '</div>' : '',
        'label' => $attributeLabels[$primaryKey],
        'sortLinkOptions' => ['class' => 'sort-link'],
        'attribute' => $primaryKey,
        'enableSorting' => true,
        'value' => function ($data) use($primaryKey) {
    return $data->$primaryKey;
},
        'headerOptions' => [
            'style' => 'width:75px;',
            'class' => 'column_-1',
        ],
        'contentOptions' => [
            'style' => 'width:75px;',
            'class' => 'column_-1',
        ],
    ]
];

$arrayName = ["`$table_name`.$primaryKey"];

/* Displaycolumn */
$displayColumn = array();
$displayColumn["-3"] = 'Check';
$displayColumn["-1"] = 'Id';
$displayValue = array();
if ($modelTable->columncheck == StatusEnum::STATUS_ACTIVED) {
    $displayValue[] = "-3";
}
if ($modelTable->columnid == StatusEnum::STATUS_ACTIVED) {
    $displayValue[] = "-1";
}
if ($modelTable->columnaction == StatusEnum::STATUS_ACTIVED) {
    $displayValue[] = "-2";
}
$that = $this;

$image_grey = !WEB_TYPE ? '/img/grey.gif' : '/' . WEB_TYPE . '/img/grey.gif';
if ($listGrid) {
    foreach ($listGrid as $key => $item) {
        $arrayColumn = array();
        $displayColumn[$item->grid_id] = $item->label;
        $attribute = $item->attribute;
        $arrayName[] = $item->attribute;
        $arrayColumn['attribute'] = preg_replace('/([`0-9a-zA-Z-_])+\./', '', $attribute);
        $arrayColumn['enableSorting'] = ($item->enablesorting == 1) ? true : false;
        if ($item->format != '') {
            $arrayColumn['format'] = 'raw';
        } else {
            $arrayColumn['format'] = 'raw';
        }
        $arrayColumn['label'] = Yii::t("admin", $item->label);
        $arrayColumn['headerOptions'] = UtilityArray::getArraySource($item->headeroptions, array('|', ','));
        $arrayColumn['contentOptions'] = UtilityArray::getArraySource($item->contentoptions, array('|', ','));
        $arrayColumn['sortLinkOptions'] = UtilityArray::getArraySource($item->sortlinkoptions, array('|', ','));


        if ($item->value == 'checkbox') {
            if (!isset($arrayColumn['headerOptions']['style']))
                $arrayColumn['headerOptions'] = array_merge($arrayColumn['headerOptions'], array('style' => 'text-align:center;width:50px;'));
            if (!isset($arrayColumn['contentOptions']['style']))
                $arrayColumn['contentOptions'] = array_merge($arrayColumn['contentOptions'], array('style' => 'text-align:center;width:50px;'));
        }
        if ($item->status == 0) {
            if (isset($arrayColumn['headerOptions']['class'])) {
                $arrayColumn['headerOptions']['class'] .= ' column_' . $item->grid_id . ' dnone';
            } else {
                $arrayColumn['headerOptions']['class'] = ' column_' . $item->grid_id . ' dnone';
            }

            if (isset($arrayColumn['contentOptions']['class'])) {
                $arrayColumn['contentOptions']['class'] .= ' column_' . $item->grid_id . ' dnone';
            } else {
                $arrayColumn['contentOptions']['class'] = ' column_' . $item->grid_id . ' dnone';
            }
        } else {
            if (isset($arrayColumn['headerOptions']['class'])) {
                $arrayColumn['headerOptions']['class'] .= ' column_' . $item->grid_id;
            } else {
                $arrayColumn['headerOptions']['class'] = ' column_' . $item->grid_id;
            }

            if (isset($arrayColumn['contentOptions']['class'])) {
                $arrayColumn['contentOptions']['class'] .= ' column_' . $item->grid_id;
            } else {
                $arrayColumn['contentOptions']['class'] = ' column_' . $item->grid_id;
            }

            $displayValue[] = $item->grid_id;
        }



        //mapping
        $mapping = array();
        $comma = array();
        $alias = $table_name;
        $name = strtolower($attribute);
        if(preg_match('/ as /',$name)) {
            $name = explode('as',$name);
            $name = trim($name[1]);
        }
        $modelValue = '';
        if (preg_match('/`([a-zA-Z0-9-_])+`/', $name, $comma)) {
            $alias = str_replace('`', '', array_shift($comma));
            $name = str_replace("`$alias`.", '', $name);
            switch ($alias) {
                case $table_name : $modelValue = $model->$name;
                    break;
                default : $modelValue = '';
                    break;
            }
        }
        $mappingMenu = array();
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
        // fiter
        if ($item->filter == '') {
            $arrayColumn['filter'] = '';
        } else {
            if (!$loadiframe) {
                $arrayColumn['filter'] = '<div class="fl dev_search_filter search_filter_' . $item->grid_id . ' ' . ($item->status ? '' : '') . '">' . $this->render('grid/' . $item->filter, array(
                            'model' => $model,
                            'mapping' => $mapping,
                            'mappingMenu' => $mappingMenu,
                            'name' => $name,
                            'title' => $item->label,
                            'modelValue' => $modelValue,
                        )) . '</div>';
            } else {
                $arrayColumn['filter'] = '';
            }
        }

        if ($getTable && isset($getTable[$name]) && $item->alias_attribute != "" && preg_match('/,/', $item->alias_attribute)) {
            $array = explode(',', $item->alias_attribute);
            $a1 = trim($array[0]);
            $a2 = trim($array[1]);
            $whereJoin[$name] = [$a1, $a2];
        }

        $arrayColumn['value'] = function ($data) use ($item, $mapping, $model, $name, $alias, $table_name, $primaryKey, $that, $classTableAll, $classTable, $loadiframe, $image_grey) {
            //value
            if ($alias != $table_name && !$data->$alias) {
                return "";
            } else {
                $value = $alias != $table_name ? $data->$alias->$name : $data->$name;
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
                    if(count($attributes)) {
                        foreach($attributes as $kv => $vv) {
                            $attributes[$table_name.'.'.$kv] = $vv;
                        }
                    }
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
                } else {
                    $value = $item->format ? Html::encode($value) : $value;
                }

                if ($item->check_status && isset($data->{$item->check_status})) {
                    if (!$data->{$item->check_status}) {
                        $value = '';
                    }
                }
                switch ($item->value) {
                    case '' : $value = '<span class="D_value">' . Html::encode($value) . '</span>';
                        break;
                    case 'noencode' : $value = '<span class="D_value">' . $value . '</span>';
                        break;
                    case 'iconcheck' : $value = '<span class="D_value">' . ($valueOld ? '<i class="fa fa-check"></i>' : '') . '</span>';
                        break;
                    case 'invoiceno' : $value = '<span class="D_value invoice_id">' . $data->getInvoiceNo() . '</span>';
                        break;
                    case 'a' : $value = '<a href="' . $link . '" class="D_gridlink D_value" >' . Html::encode($value) . '</a>';
                        break;
                    case 'json' :
                        if ($value != "") {
                            $array = (array) json_decode($value);
                            $html = '';
                            foreach ($array as $key => $v) {
                                $html .= "<p>$key => $v</p>";
                            }
                            $value = '<div class="D_value">' . $html . '</div>';
                        } else {
                            $value = '<div class="D_value"></div>';
                        }
                        break;
                    case 'arrayjson' :
                        if ($value != "") {
                            $array = (array) json_decode($value);
                            $html = '';
                            foreach ($array as $key => $valueItem) {
                                $valueItem = (array) $valueItem;
                                $html .= '<p>';
                                foreach ($valueItem as $k => $v) {
                                    $html .= " $k => $v ";
                                }
                                $html .= '</p>';
                            }
                            $value = '<div class="D_value">' . $html . '</div>';
                        } else {
                            $value = '<div class="D_value"></div>';
                        }
                        break;
                    case 'file':
                        $value = '<div class="D_value"><a href="'.$model->getfile($value).'">'.$model->getfile($value).'</a></div>';
                        break;
                    case 'img' :
                        $image_main = $value ? ' data-srca="'.$model->getimage(array(), $value).'"' : '';
                        if (!$loadiframe) {
                            $image_30 = $value ? ' data-src="'.$model->getimage(array(30, 30), $value).'"' : '';
                            $class_attr = $value ? 'setting_tooltip D_loadingImg' : '';
                            $value = '<div class="D_value"><img' . $image_main . ' class="admin_image ' . $class_attr . '" src="' . $image_grey . '"' . $image_30 . ' /></div>';
                        } else {
                            $image_30 = $value ? ' src="'.$model->getimage(array(30, 30), $value).'"' : '';
                            $class_attr = $value ? 'setting_tooltip ' : '';
                            $value = '<div class="D_value"><img' . $image_main . ' class="admin_image ' . $class_attr . '"' . $image_30 . '/></div>';
                        }
                        break;
                    case 'number' :
                        if ($value != "") {
                            $str = preg_replace("/([^0-9\.])+/", "", $value);
                            if ($str != "") {
                                $value = '<span class="D_value">' . UtilityHtmlFormat::numberFormat($str) . '</span>';
                            } else {
                                $str = '';
                            }
                        } else {
                            $value = '';
                        }
                        break;
                    case 'price' :
                        $str = preg_replace("/([^0-9\.])+/", "", $value);
                        $value = '<span class="D_value">' . UtilityHtmlFormat::numberFormatPrice($str) . '</span>';
                        break;
                    case 'float' :
                        $str = preg_replace("/([^0-9\.])+/", "", $value);
                        $value = '<span class="D_value">' . UtilityHtmlFormat::numberFloat($str) . '</span>';
                        break;
                    case 'floatPrice' :
                        $str = preg_replace("/([^0-9\.])+/", "", $value);
                        $value = '<span class="D_value">' . UtilityHtmlFormat::numberFloatPrice($str) . '</span>';
                        break;
                    case 'checkbox' :
                        if ($item->template != "") {
                            $htmlOptions = [
                                'class' => 'status_a_click',
                                'data-class' => get_class($data),
                                'data-statusname' => $name,
                                'data-primarykey' => $primaryKey,
                                'data-value' => $data->$primaryKey,
                                'data-val' => $data->$name,
                            ];

                            $arrayParent = explode(';', $item->template);
                            
                            $label = '';
                            foreach ($arrayParent as $key => $v) {
                                $array = explode(' ', trim(preg_replace('/(\s)+/', ' ', $v)));
                                if ($value == $array[0]) {
                                    $label = isset($array[1]) ? $array[1] : '';
                                }
                                $htmlOptions['data-label' . $array[0]] = isset($array[1]) ? $array[1] : '';
                            }
                            $value = Html::a($label, 'javscript:void(0);', $htmlOptions);
                        } else {
                            $htmlOptions = [
                                'class' => 'Pcheckbox status_click ace',
                                'data-class' => get_class($data),
                                'checked' => $value ? true : false,
                                'data-table' => $table_name,
                                'data-statusname' => $name,
                                'data-primarykey' => $primaryKey,
                                'value' => $data->$primaryKey,
                            ];
                            $value = '<span class="D_value">' . Html::checkbox($name, $value, $htmlOptions) . '</span>';
                        }
                        break;
                    case 'date' :
                        $value = '<span class="D_value">' . UtilityDateTime::formatDate($value) . '</span>';
                        break;
                    case 'datetime' :
                        $value = '<span class="D_value">' . UtilityDateTime::formatDateTime($value) . '</span>';
                        break;
                    case 'ip' :
                        $value = '<pre class="D_value google_value">' . UtilityHtmlFormat::convertStringToIp($value) . '</pre>';
                        break;
                }
                if ($item->update == 1) {
                    $linkUpdate = $that->createUrl(trim($item->link_updatefast) != "" ? trim($item->link_updatefast) : '/settings/access/updatefast', [
                        'id' => $data->$primaryKey,
                        'nameupdate' => $name,
                        'table_id' => $item->table_id]);
                    $value .= Html::a('<i class="ace-icon fa fa-pencil bigger-120"></i>', $linkUpdate, [
                                'class' => 'btn btn-xs btn-info tableupdate',
                                'data-nameupdate' => $name,
                                'data-tablename' => $classTable,
                    ]);
                }
                return $value;
            }
        };

        $column[] = $arrayColumn;
    }
}
/* @var $query ActiveQuery */
$array_select = [];
foreach($arrayName as $k => $v) {
    $array_select[$v] = $v;
}
$arrayName = array_values($array_select);
$query->select($arrayName)->where($modelTable->condition);
$modelTable->join = trim($modelTable->join);
if ($modelTable->join != "") {
    $join = explode(',', $modelTable->join);
    foreach ($join as $key => $value) {
        $a = explode(':', $value);
        if (isset($a[1])) {
            $query->joinWith($a[0], true, $a[1]);
        } else {
            $query->innerJoinWith($value);
        }
    }
}
if($modelTable->groupby) {
    $query->groupBy($modelTable->groupby);
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
if ($modelTable->orderby != '' && !$this->getParam('sort')) {
    $query->orderBy($modelTable->orderby);
}
$dataProvider = $model->searchAdmin($query);
if (isset($_GET['pagesize'])) {
    $dataProvider->pagination->defaultPageSize = $_GET['pagesize'];
} else {
    $dataProvider->pagination->defaultPageSize = 50;
}
$total = $dataProvider->totalCount;
echo $this->render('breadcrumb');

if ($modelTable->attrarange != '') {
    $htmlOrderby = $this->render('attribute/orderby', [
        'modelTable' => $modelTable,
        'classTable' => $classTableAll,
        'attributeLabels' => $attributeLabels,
        'model' => $model,
        'get'   => $get,
    ]);
} else {
    $htmlOrderby = '';
}


if ($modelTable->attrsearch != '') {
    $htmlSearch = $this->render('attribute/attributesearch', [
        'modelTable' => $modelTable,
        'classTable' => $classTableAll,
        'attributeLabels' => $attributeLabels,
        'model' => $model,
    ]);
} else {
    $htmlSearch = '';
}


if ($modelTable->attrchoice != '') {
    $htmlChoice = $this->render('attribute/choice', [
        'modelTable' => $modelTable,
        'classTable' => $classTableAll,
        'attributeLabels' => $attributeLabels,
        'model' => $model,
    ]);
} else {
    $htmlChoice = '';
}
?>
<?php if (!$loadiframe) { ?>
    <div class="page-header">
        <h1 class="fl" style="width:100%;"><span><?= Yii::t("admin", trim($this->context->menu_admin->name)) ?> (<?= $total ?>)</span></h1>
        <?= $htmlSearch . $htmlOrderby ?>
        <div class="clear"></div>
    </div>
<?php } ?>
<?php
$displayColumn["-2"] = 'Action';
$summaryText = '';
if (!$loadiframe) {
    $summaryText = Html::dropDownList('displayColumn', $displayValue, $displayColumn, array(
                'multiple' => true,
                'data-displayname' => Yii::t("admin", "Display column"),
                'data-href' => $this->createUrl('/settings/access/checkgrid', array(
                    'table_id' => $modelTable->table_id,
                )),
                'id' => 'displayColumn',
                    )
    );
}
echo GridView::widget([
    'id' => 'user-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'checkbox' => $modelTable->columncheck,
    'columns' => $column,
    'summaryText' => $summaryText,
    'columnAction' => $modelTable->columnaction,
    'renderNavLeft' => $htmlChoice,
    'menu_admin_id' => $this->context->menu_admin_id,
    'options' => [
        'class' => 'dataTables_wrapper form-inline no-footer backendgridview div_'.$table_name,
    ],
    'btnDeleteNav' => [
        'onoff' => $this->context->menu_admin->delete && !$loadiframe,
    ],
    'btnDelete' => [
        'onoff' => $this->context->menu_admin->delete,
    ],
    'btnCopyNav' => [
        'onoff' => $this->context->menu_admin->copy,
    ],
    'btnCopy' => [
        'onoff' => $this->context->menu_admin->copy,
    ],
    'btnView' => [
        'onoff' => $this->context->menu_admin->view,
    ],
    'btnUpdate' => [
        'onoff' => $this->context->menu_admin->edit,
        'data-onclick' => $this->context->menu_admin->onclickedit,
    ],
    'btnAddNav' => [
        'onoff' => $this->context->menu_admin->add && !$loadiframe,
        'data-onclick' => $this->context->menu_admin->onclickadd,
    ],
    'btnAddMultiNav' => [
        'onoff' => $this->context->menu_admin->multi_add && !$loadiframe,
    ],
]);
?>  
<script type="text/javascript">
    var loadiframe = '<?= $loadiframe ?>';
</script>