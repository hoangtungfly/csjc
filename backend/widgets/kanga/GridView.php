<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\widgets\kanga;

use Yii;
use Closure;
use yii\i18n\Formatter;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\BaseListView;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;

class GridView extends \yii\grid\GridView {

    public $function = '';
    public $button_name = '';
    public $button_class = 'btn btn-primary';
    public $filter_action = '';
    public $action_name = '';
    public $action_class = 'btn btn-primary';

    public function renderFilters() {
        if ($this->filterModel !== null) {
            $cells = [];
            foreach ($this->columns as $column) {
                /* @var $column Column */
                $cells[] = $column->renderFilterCell();
            }
            $attributes = $this->filterModel->attributes;
            $check_show_bt = false;
            foreach ($attributes as $attribute) {
                if ($attribute != '' && $attribute != null) {
                    $check_show_bt = true;
                }
            }
            $count = count($cells);
            if ($this->dataProvider->getCount() == 0) {
                if ($check_show_bt) {
                    if ($cells[$count - 1] == '<td>&nbsp;</td>' || $cells[$count - 1] == '<td> </td>') {
                        if ($this->filter_action != '' && $this->action_name != '')
                            $cells[$count - 1] = '<td><button type="button" class="' . $this->action_class . '" onclick = "' . $this->filter_action . '">' . $this->action_name . '</button></td>';
                    }
                }
            }
            return Html::tag('tr', implode('', $cells), $this->filterRowOptions);
        } else {
            return '';
        }
    }

    public function renderPager() {
        $pagination = $this->dataProvider->getPagination();
        if ($pagination === false || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        /* @var $class LinkPager */
        $pager = $this->pager;
        $class = ArrayHelper::remove($pager, 'class', LinkPager::className());
        $pager['pagination'] = $pagination;
        $pager['view'] = $this->getView();
        if ($this->button_name != '' && $this->function != '') {
            $button = '<div><button type="button" class="' . $this->button_class . '" style="width: 12%;" onclick = "' . $this->function . '">' . $this->button_name . '</button></div>';
        } else {
            $button = '';
        }
        return $button . $class::widget($pager);
    }

    /* END */
}
