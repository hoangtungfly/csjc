<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\widgets;

use common\core\enums\StatusEnum;
use common\utilities\UtilityUrl;
use Yii;
use yii\grid\Column;
use yii\grid\GridViewAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
class GridView extends \yii\grid\GridView {

    public  $templateMain = '<div class="row"><div class="col-xs-12">{main}</div></div>';
    /* SUMMARY */

    public $summaryText = '';
    public $summary = '';
    public $url = '';
    public $menu_admin_id;
    /* BEGIN */
    /* END */

    /* TABLE */
    /* BEGIN */
    public $tableOptions = [
        'class' => 'table table-striped table-bordered table-hover '
    ];
    /* END */

    /* DIV PARENT */
    /* BEGIN */
    public $options = [
        'class' => 'dataTables_wrapper form-inline no-footer backendgridview',
    ];

    /* END */
    
    /**/

    const PRIMARY_FIELD_CLASS = 'small';
    # default status field
    const DEFAULT_STATUS_COLUMN = 'status';

//    # function php that will process data to render css for colums
    public $cssColumsFunc = '';
//    # default other properties
//    # param for colums
    public $columBtnParams = array();
//    # default params for colums
    public $showPageList = true;
    public $checkLoadJs = true;

    /**
     *
     * @var type  primary field
     */
    private $priField = null;

    /**
     *
     * @var string
     */
    public $titlePage = '';

    /**
     * CButtonColumn class
     * @var CButtonColumn
     */
    private $btnColumn = null;

    
    /**
     * enable or disable checkbox
     * @var boolen
     */
    public $checkbox = true;
    public $columnAction = true;

    /**
     * show some navigation buttons 
     * format key=>array(
     *  'label'=>'',
     *  'tag'=>'',
     *  'htmlOptions'=>''
     * )
     * @var array
     */
    public $navigationBtn = array(
    );

    /**
     * set class default for pagination
     * @var array
     */
    public $pager = array(
        'header' => '',
        'showPageList' => true,
        'class' => 'backend\widgets\LinkPager',
        'maxButtonCount' => MAX_BUTTON_PAGE,
        'firstPageLabel' => '&laquo;&laquo;',
        'prevPageLabel' => '&laquo;',
        'nextPageLabel' => '&raquo;',
        'lastPageLabel' => '&raquo;&raquo;',
        'pageSizeLimit' => [1,200],
    );

    /**
     * default disable ajax update 
     * @var boolen
     */
    /* Filter select show row */
    public $filterSelector;
    /**
     * default urls for action delete, add
     * @var string
     */
    /*BEGIN*/
    public $deleteUrl = null;
    public $addUrl = null;
    public $tab_left = '';
    /*END*/
    /**
     * status icon
     */
    public $statusIcon = array(
        StatusEnum::STATUS_ACTIVED => 'icon-ok',
        StatusEnum::STATUS_DEACTIVED => 'icon-minus',
        StatusEnum::STATUS_REMOVED => 'icon-remove',
    );
    
    public $filterPosition = '';

    /*
     * default control
     */
    public $control         = true;
    public $controlEdit     = '';
    public $controlDelete   = '';
    public $priFieldKey     = false;
    
    public $btnUpdate       = array();
    public $btnDelete       = array();
    public $btnView         = array();
    public $btnCopy         = array();
    public $btnAdd          = array();
    
    public $renderNav       = array();
    public $btnAddNav       = array();
    public $btnAddMultiNav  = array();
    public $btnDeleteNav    = array();
    public $btnCopyNav      = array();
    public $renderNavLeft   = '';
    
    /**
     * Initializes the grid view.
     * This method will initialize required property values and instantiate {@link columns} objects.
     * @Extended from parent
     */
    public function init() {

        /* FILTER YII LOAD */
        /* BEGIN */
        if (!is_array($this->filterSelector)) {
            $this->filterSelector = (trim($this->filterSelector) != '') ? array($this->filterSelector) : array();
        }
        
        $this->options['data-url'] = $this->url ? $this->url : Url::canonical();
        if($this->url) {
            $this->pager['url'] = $this->url;
        }
        
        if(!isset($this->options['class']))
            $this->options['class'] = 'dataTables_wrapper form-inline no-footer backendgridview';
        
        $this->btnUpdate = array_merge([
            'onoff'                 => true,
            'class'                 => 'btn btn-xs btn-info update controlButton controlUpdate',
            'data-original-title'   => 'Edit',
            'data-placement'        => 'top',
            'data-toggle'           => 'tooltip',
            'html'                  => '<i class="ace-icon fa fa-pencil bigger-120"></i>',
            'link_children'         => 'update',
        ],$this->btnUpdate);
        
        
        $this->btnDelete = array_merge([
            'onoff'                 => true,
            'class'                 => 'btn btn-xs btn-danger delete controlButton controlDelete',
            'data-original-title'   => Yii::t('admin','Delete'),
            'data-placement'        => 'top',
            'data-toggle'           => 'tooltip',
            'html'                  => '<i class="ace-icon fa fa-trash-o bigger-120"></i>',
            'link_children'         => 'delete',
        ],$this->btnDelete);
        
        $this->btnView = array_merge([
            'onoff'                 => false,
            'class'                 => 'btn btn-xs btn-success view controlButton controlView',
            'data-original-title'   => Yii::t('admin','View'),
            'data-placement'        => 'top',
            'data-toggle'           => 'tooltip',
            'html'                  => '<i class="ace-icon fa fa-search-plus bigger-120"></i>',
            'link_children'         => 'view',
        ],$this->btnView);
        
        $this->btnCopy = array_merge([
            'onoff'                 => false,
            'class'                 => 'btn btn-xs btn-info view controlButton controlCopy',
            'data-original-title'   => 'Copy',
            'data-placement'        => 'top',
            'data-toggle'           => 'tooltip',
            'html'                  => '<i class="ace-icon fa fa-copy bigger-120"></i>',
            'link_children'         => 'copy',
        ],$this->btnCopy);
        
        $this->btnAddNav = array_merge([
            'onoff'                 => true,
            'class'                 => 'btn index-header btn-success create_record',
            'icon'                  => '<i class="fa fa-plus"></i> '.  Yii::t('admin','Add').' ',
            'html'                  => '',
            'link_children'         => 'create',
        ],$this->btnAddNav);
        
        $this->btnAddMultiNav = array_merge([
            'onoff'                 => true,
            'class'                 => 'btn index-header btn-warning create_multi_record',
            'icon'                  => '<i class="fa fa-plus"></i> '.  Yii::t('admin','Multi Add').' ',
            'html'                  => '',
            'link_children'         => 'multiadd',
        ],$this->btnAddMultiNav);
        
        $this->btnDeleteNav = array_merge([
            'onoff'                 => true,
            'class'                 => 'btn index-header btn-danger deleteall',
            'icon'                  => '<i class="fa fa-trash-o"></i> '.Yii::t('admin','Delete').' ',
            'html'                  => '',
            'link_children'         => 'deleteall',
        ],$this->btnDeleteNav);
        
        $this->btnCopyNav = array_merge([
            'onoff'                 => false,
            'class'                 => 'btn index-header btn-info copyall',
            'icon'                  => '<i class="fa fa-copy"></i> Copy ',
            'html'                  => '',
            'link_children'         => 'copyall',
        ],$this->btnCopyNav);
        
        $this->renderNav = array_merge([
            'onoff'                 => true,
            'class'                 => 'col-sm-12 pl0 pr0',
            'style'                 => 'margin-bottom: 12px;',
        ],$this->renderNav);
        
        if($this->filterPosition == "")
            $this->filterPosition = self::FILTER_POS_TOP;
        /* SHOW ROW FILTER */
        $this->filterSelector[] = '#Dfilter select';
        $this->filterSelector = implode(',', $this->filterSelector);
        /* END */
        # get primary key of gridview
        if (!$this->priFieldKey && $this->dataProvider->getModels()) {
            $models = $this->dataProvider->getModels();
            if(isset($models[0]) && $models[0]) {
                $this->priFieldKey = $models[0]->getKey();
            } else {
                $this->priFieldKey = false;
            }
            
        }

        /* PAGER */
        /* BEGIN */
        $this->pager['showPageList'] = $this->showPageList;
        # set default pagesize for CPagination
        # check parameter GET that pagesize does exist or not, if dose not exist, set pagesize to default value
        if (isset($_GET['pagesize'])) {
            $this->dataProvider->pagination->defaultPageSize = $_GET['pagesize'];
        }
        $this->dataProvider->pagination->pageSizeLimit = [1, 200];
        /* END */
        /*BEGIN RESET AJAX*/
        /* BEGIN */
        $this->getView()->registerJs('resetAjax();');
        /* END */
        # add checkbox
        $classOption = $this->checkbox ? '' : ' dnone';
        $loadiframe = (int)r()->get('loadiframe');
        $a = [];
        if(!$loadiframe) {
            $a[0] = [
                    'class'             => '\backend\widgets\CheckboxColumn',
                    'name'              => 'ids[]',
                    'checkboxOptions'   => [
                        'class'         => 'Pcheckbox gridChexbox ',
                    ],
                    'headerOptions'     => [
                        'style' => 'width:40px;',  
                        'class' => 'column_-3 '.$classOption,
                    ],
                    'contentOptions'    => [
                        'class' => 'column_-3 '.$classOption,
                    ],
                ];
        }
        $this->columns = array_merge($a, $this->columns);
        # add checkbox
        if ($this->control) {
            $priFieldKey = $this->priFieldKey;
            $btn['btnView'] = $this->btnView;
            $btn['btnUpdate'] = $this->btnUpdate;
            $btn['btnDelete'] = $this->btnDelete;
            $btn['btnCopy'] = $this->btnCopy;
            $that = $this;
            $params = [];
            if(isset($_GET['SettingsGridSearch']['table_id'])) {
                $params['SettingsGridSearch[table_id]'] = $_GET['SettingsGridSearch']['table_id'];
            }
            $columnAction = $this->columnAction ? '' : ' dnone';
            $realUrl = base64_encode(UtilityUrl::realURL());
            $control = [
                    [
                        'filter'        => '',
                        'format'        => 'raw',
                        'headerOptions' => [
                            'style' => 'width:140px;',  
                            'class' => 'column_-2 ' .$columnAction,
                        ],
                        'contentOptions' => [
                            'class' => 'column_-2 ' .$columnAction,
                        ],
                        'value'         => function($data) use ($priFieldKey, $btn, $that, $params,$realUrl) {
                            if($priFieldKey && count($btn) > 0) {
                                $result = '';
                                foreach($btn as $key => $item) {
                                    if($item && isset($item['onoff']) && $item['onoff']) {
                                        unset($item['onoff']);
                                        if(isset($item['href'])) {
                                            $href = $item['href'];
                                            unset($item['href']);
                                        } else {
                                            $params['id'] = $data->$priFieldKey;
                                            $params['urlb'] = $realUrl;
                                            $params['menu_admin_id'] = $that->menu_admin_id;
                                            $href = UtilityUrl::createUrl($item['link_children'],$params);
                                        }
                                        $html = $item['html'];unset($item['html']);unset($item['link_children']);
                                        $result .= Html::a($html, $href, $item).' ';
                                    }
                                }
                                return $result;
                            } else {
                                return '';
                            }
                        },
                    ],
            ];
            $this->columns = array_merge($this->columns, $control);
        }
        return parent::init();
    }

    public function run() {
        $id = $this->options['id'];
        
        if ($this->showOnEmpty || $this->dataProvider->getCount() > 0) {
            $content = preg_replace_callback("/{\\w+}/", function ($matches) {
                $content = $this->renderSection($matches[0]);

                return $content === false ? $matches[0] : $content;
            }, $this->layout);
        } else {
            $content = $this->renderEmpty();
        }
        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        $mainHtml = Html::tag($tag, $content, $this->options);
        echo $this->renderNav().str_replace('{main}',$mainHtml,$this->templateMain);
    }
    
    /*
     * Button create delete copy
     */
    public function renderNav() {
        if($this->renderNav['onoff']) {
            
            if(isset($this->renderNav['html']) && $this->renderNav['html']) {
                $content = $this->renderNav['html'];
                unset($this->renderNav['html']);
            } else {
                $ct['btnAddNav'] = $this->btnAddNav;
                $ct['btnAddMultiNav'] = $this->btnAddMultiNav;
                $ct['btnDeleteNav'] = $this->btnDeleteNav;
                $ct['btnCopyNav'] = $this->btnCopyNav;
                $result = '';
                $get = r()->get();
                foreach($ct as $key => $item) {
                    if($item && isset($item['onoff']) && $item['onoff']) {
                        unset($item['onoff']);
                        if(isset($item['href'])) {
                            $href = $item['href'];
                            unset($item['href']);
                        } else {
                            $params = isset($get['SettingsGridSearch']['table_id']) ? $get : [];
                            $params['menu_admin_id'] = $this->menu_admin_id;
                            $href = UtilityUrl::createUrl($item['link_children'],$params);
                        }
                        $html = $item['icon'].$item['html'];unset($item['html']);unset($item['icon']);unset($item['link_children']);
                        $result .= Html::a($html, $href, $item).' ';
                    }
                }
                $content = '<div class="fr">'.$result.'</div>';
            }
            unset($this->renderNav['onoff']);
            $html = Html::tag('div',$content.$this->renderNavLeft, $this->renderNav);
            return $html;
        } else {
            return '';
        }
    }

    /* FILTER */
    /* BEGIN */

    const FILTER_POS_TOP = 'top';

    public function renderItems() {
        if ($this->filterPosition == self::FILTER_POS_TOP) {
            $caption = $this->renderCaption();
            $columnGroup = $this->renderColumnGroup();
            $tableHeader = $this->showHeader ? $this->renderTableHeader() : false;
            $tableBody = $this->renderTableBody();
            $tableFooter = $this->showFooter ? $this->renderTableFooter() : false;
            $content = array_filter([
                $caption,
                $columnGroup,
                $tableHeader,
                $tableFooter,
                $tableBody,
            ]);
            return $this->renderFilters() . Html::tag('table', implode("\n", $content), $this->tableOptions);
        } else {
            return parent::renderItems();
        }
    }

    public function renderFilters() {
        if ($this->filterModel !== null) {
            if ($this->filterPosition == self::FILTER_POS_TOP) {
                foreach ($this->columns as $column) {
                    /* @var $column Column */
                    $cells[] = $column->renderFilterCell();
                }
                $this->filterRowOptions['class'] = ' render_filter row ';
                $summaryText = $this->summaryText;
                if($summaryText != '') {
                    $summaryText = '<div class="fr" style="margin:10px 10px 0px 0px;">'.$summaryText.'</div>';
                }
                return $summaryText . Html::tag('div','<div class="fl">' . implode('', $cells) . '</div>' . $this->tab_left, $this->filterRowOptions);
            } else {
                return parent::renderFilters();
            }
        } else {
            return '';
        }
    }

    /* END */
    
    public function renderTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if ($this->filterPosition == self::FILTER_POS_HEADER) {
            $content .= $this->renderFilters();
        } elseif ($this->filterPosition == self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        return "<thead>\n" . $content . "\n</thead>";
    }
    
    /**
     * Returns the options for the grid view JS widget.
     * @return array the options
     */
    protected function getClientOptions()
    {
        $filterUrl = isset($this->filterUrl) ? $this->filterUrl : Yii::$app->request->url;
        $id = $this->filterRowOptions['id'];
        $filterSelector = "#$id input, #$id select";
        if (isset($this->filterSelector)) {
            $filterSelector .= ', ' . $this->filterSelector;
        }
        $filterSelector = '';

        return [
            'filterUrl' => Url::to($filterUrl),
            'filterSelector' => $filterSelector,
        ];
    }
}
