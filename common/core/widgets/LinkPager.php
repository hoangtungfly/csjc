<?php

/**
 * @author: Nguyen Anh Dung
 * Extend CLinkPage of Yii
 * Set some default values for CLinkPager
 * Add more buttons such as: dropdownlist paper..
 * 
 * @date: 23/1/2015
 * @version: 1.0
 */

namespace common\core\widgets;

use yii\helpers\Html;
use common\utilities\UtilityUrl;

class LinkPager extends \yii\widgets\LinkPager {

    /**
     * Initializes the pager by setting some default property values.
     */
    public $showPageList = true;
    public $header = '';
    public $footer = '';

    /**
     * some option for pagination
     */
    public $paginationOptions = [
        'pageSizeParam' => true
    ];

    /**
     * max rows displayed
     * @var interger 
     */
    public $rows = array(
        1 => 1,
        10 => 10,
        20 => 20,
        50 => 50,
        100 => 100,
        200 => 200
    );

    /**
     * Template dropdown
     * @var string
     */
    public $templateDropdown = null;

    /**
     * Initializes the pager.
     */
    public function init() {
        return parent::init();
    }

    /**
     * Initializes the pager by setting some default property values.
     */
    public function run() {
        foreach ($this->paginationOptions as $key => $item) {
            $this->pagination->$key = $item;
        }
        if ($this->showPageList) {
            echo (!is_array($this->header)) ? $this->header : implode('\n', $this->header);
            echo '<div class="navbar-right soft-bottom">';
            echo implode('\n', $this->renderShowButton());
            echo '</div>';
            echo (!is_array($this->footer)) ? $this->header : implode('\n', $this->footer);
        } else {
            
        }
        
        parent::run();
    }

    /**
     * create dropdown list pagesize
     * @return string CHtml::dropDownList
     */
    public function renderShowButton() {
        $arrayRows = $this->rows;
        if (!is_array($this->rows)) {
            return null;
        }
        if (isset($_GET['pagesize'])) {
            $this->pagination->defaultPageSize = $_GET['pagesize'];
        }
        $htmlR = Html::dropDownList('pagesize', $this->pagination->defaultPageSize, $arrayRows, ['class' => 'chosen-select']);
        if ($this->templateDropdown === null && $this->pagination->totalCount > $this->pagination->pageSize) {
            $this->templateDropdown = '<div class="filters" id="Dfilter"><span>Show </span>' . $htmlR . '</div>';
        }
        return array($this->templateDropdown);
    }
    
    /**
     * @author Phong Pham Hong
     * 
     * calculate nextpage for load scroll
     * @param CPagination $pagination Description
     * @return int
     */
    public static function calculateNextPage($pagination) {
        $nextpage = 1;
        $currentpage = $pagination->getPage() + 1;
        $totalpage = $pagination->getPageCount();
        if ($currentpage < $totalpage) {
            $nextpage = $currentpage + 1;
        } else if ($currentpage >= $totalpage) {
            $nextpage = 0;
        }   
        return $nextpage;
    }

}
