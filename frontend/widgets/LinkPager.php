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

namespace frontend\widgets;

use yii\helpers\Html;
use common\utilities\UtilityUrl;

class LinkPager extends \yii\widgets\LinkPager {

    /**
     * Initializes the pager by setting some default property values.
     */
    public $showPageList = true;
    public $header = '';
    public $footer = '';
    public $options = [
        'class' => 'pagination navbar-left',
    ];
    public $lastPageCssClass = '';
    public $nextPageCssClass = 'nav_page_txt';
    public $prevPageCssClass = 'nav_page_txt';

    public $pageSizeParam = 'pagesize';
    
    public $filterid = 'Dfilter';
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
        200 => 200,
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
        echo '<div class="panigation-div">';
        echo (!is_array($this->header)) ? $this->header : implode('\n', $this->header);
        if ($this->showPageList) {
            echo implode('\n', $this->renderShowButton());
        }
        echo (!is_array($this->footer)) ? $this->header : implode('\n', $this->footer);
        parent::run();
        echo '</div>';
    }

    /**
     * create dropdown list pagesize
     * @return string CHtml::dropDownList
     */
    public function renderShowButton() {
        $arrayRows = $this->rows;
//        if (!is_array($this->rows)) {
//            return null;
//        }
        if (isset($_GET[$this->pageSizeParam])) {
            $this->pagination->defaultPageSize = $_GET[$this->pageSizeParam];
        }
        $htmlR = Html::dropDownList($this->pageSizeParam, $this->pagination->defaultPageSize, $arrayRows, ['class' => 'chosen-select']);
        if ($this->templateDropdown === null) {
            $this->templateDropdown = '<div class="filters pull-left nav-page" id="'.$this->filterid.'">' . $htmlR . '</div>';
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
    
    /**
     * Renders the page buttons.
     * @return string the rendering result
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        $countShow = (int)(MAX_BUTTON_PAGE/2);
        
        $buttons = [];
        $currentPage = $this->pagination->getPage();
        // prev page
        if ($this->prevPageLabel !== false && $currentPage > $countShow && $beginPage != 0) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            if($beginPage > 1)
                $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
            $buttons[] = $this->renderPageButton(1, 0, $this->firstPageCssClass, $currentPage <= 0, false);
            if($beginPage - 1 != 0) {
                $buttons[] = $this->renderPageButton('...', $beginPage - 1, $this->prevPageCssClass, $currentPage <= 0, false);
            }
        }
        

        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }

        // last page
        if ($currentPage < $pageCount - $countShow) {
            if($endPage < $pageCount - 1) {
                if($endPage + 1 != $pageCount - 1)
                    $buttons[] = $this->renderPageButton('...', $endPage + 1, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
                $buttons[] = $this->renderPageButton($pageCount, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
                // next page
                if ($this->nextPageLabel !== false && $endPage < $pageCount - $countShow) {
                    if (($page = $currentPage + 1) >= $pageCount - 1) {
                        $page = $pageCount - 1;
                    }
                    $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
                }
            }
        }


        return '<div class="table-panigation pull-right">'.Html::tag('ul', implode("\n", $buttons), $this->options).'</div>';
    }

}
