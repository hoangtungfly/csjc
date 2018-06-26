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

namespace backend\widgets;

use common\utilities\UtilityUrl;
use yii\helpers\Html;
use yii\helpers\Url;

class LinkPager extends \yii\widgets\LinkPager {

    /**
     * Initializes the pager by setting some default property values.
     */
    public $showPageList = true;
    public $header = '';
    public $footer = '';
    public $url;
    public $pageSizeLimit = [1,200];
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
        $this->pagination->pageSizeLimit = $this->pageSizeLimit;
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
        }
        
        parent::run();
    }

    /**
     * create dropdown list pagesize
     * @return string CHtml::dropDownList
     */
    public function renderShowButton() {
        $arrayRows = $this->rows;
        $arrayData = [];
        $get = r()->get();
        $pageActive = isset($get['pagesize']) ? $get['pagesize'] : $this->pagination->defaultPageSize;
        $this->pagination->defaultPageSize = $pageActive;
        $route = Url::base();
        foreach($arrayRows as $key => $value) {
            $get['pagesize'] = $key;
            $url = UtilityUrl::createUrl($route,$get);
            $arrayData[$url] = $value;
            if($pageActive == $value)
                $pageActive = $url;
        }
        if (!is_array($this->rows)) {
            return null;
        }
        $htmlR = Html::dropDownList('pagesize', $pageActive, $arrayData, [
            'class' => 'setting_chosen_nosearch',
            'id'    => 'change_pagesize',
        ]);
//        if ($this->templateDropdown === null && $this->pagination->totalCount > $this->pagination->pageSize) {
            $this->templateDropdown = '<div style="margin:10px 10px 0px 0px;"><span>Show </span>' . $htmlR . '</div>';
//        }
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
    
    
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('li', Html::tag('span', $label), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;
        $url = $this->pagination->createUrl($page);
        if($this->url != "") {
            $get = r()->get();
            $get['page'] = $page + 1;
            $url = UtilityUrl::createUrl($this->url, $get);
        }
        return Html::tag('li', Html::a($label, $url, $linkOptions), $options);
    }

}
