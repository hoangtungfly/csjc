<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\core\widgets;

use common\core\dbConnection\GlobalActiveRecord;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\core\widgets\LinkPager;
use common\core\enums\StatusEnum;

class GridView extends \yii\grid\GridView {

    /* SUMMARY */

    public $summaryText = '';
    public $summary = '';
    /* BEGIN */
    /* END */

    /* TABLE */
    /* BEGIN */
    public $tableOptions = [
        'class' => 'table table-new-crm'
    ];
    /* END */

    /* DIV PARENT */
    /* BEGIN */
    public $options = [
        'class' => 'wrap_crm',
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
    public $checkbox = false;

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
        'class' => 'common\core\widgets\LinkPager',
        'maxButtonCount' => MAX_BUTTON_PAGE,
        'firstPageLabel' => '&laquo;&laquo;',
        'prevPageLabel' => '&laquo;',
        'nextPageLabel' => '&raquo;',
        'lastPageLabel' => '&raquo;&raquo;',
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

        $this->filterPosition = self::FILTER_POS_TOP;
        /* SHOW ROW FILTER */
        $this->filterSelector[] = '#Dfilter select';
        $this->filterSelector = implode(',', $this->filterSelector);
        /* END */


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
        if ($this->checkbox) {
            $a = [
                    [
                        'class' => '\yii\grid\CheckboxColumn',
                        'name'  => 'ids[]',
                        'checkboxOptions' => ['class' => 'Pcheckbox gridChexbox'],
                    ],
            ];

            $this->columns = array_merge($a, $this->columns);
        }
        return parent::init();
    }

    public function run() {
        return parent::run();
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
            parent::init();
        }
    }

    public function renderFilters() {
        if ($this->filterModel !== null) {
            if ($this->filterPosition == self::FILTER_POS_TOP) {
                foreach ($this->columns as $column) {
                    /* @var $column Column */
                    $cells[] = $column->renderFilterCell();
                }
                $this->filterRowOptions['class'] .= ' opp-menu-search navbar-right chonsen-select-opp';
                return Html::tag('div', $this->tab_left . '<div class="navbar-right chonsen-select-opp">' . implode('', $cells) . '</div>', $this->filterRowOptions);
            } else {
                parent::renderFilters();
            }
        } else {
            return '';
        }
    }

    /* END */
}
