<?php

use backend\widgets\GridView;
use common\utilities\UtilityDateTime;
use yii\helpers\Html;

$total = $dataProvider->totalCount;
?>
<!--BEGIN BREADCRUMB-->
<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="<?= $this->createUrl('/') ?>"><?=Yii::t('admin','Home')?></a>
        </li>
        <li><a class="breadcrumbsa" href="<?=$this->createUrl('/system/syspages/index')?>"><?=Yii::t("sys_page","page_header")?></a></li>        
    </ul>
</div>
<!--END BREADCRUMB-->

<!--BEGIN TITLE-->
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span><?=Yii::t('sys_page','page_header')?> (<?=$total?>)</span></h1>    
    <div class="clear"></div>
</div>
<!--END TITLE-->

<?php 
echo GridView::widget([
    'id' => 'syspage-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'checkbox' => 1,
    'columnAction'  => 1,
    'renderNavLeft' => "",
    'btnDeleteNav'  => [
        'onoff' => 1,
    ],
    'btnDelete'     => [
        'onoff' => 1,
    ],
    'btnCopyNav'  => [
        'onoff' => 0,
    ],
    'btnCopy'       => [
        'onoff' => 0,
    ],
    'btnView'       => [
        'onoff' => 0,
    ],
    'btnUpdate'     => [
        'onoff'         => 1,
        'data-onclick'  => 0,
    ],
    'btnAddNav'     => [
        'onoff'         => 1,
        'data-onclick'  => 0,
    ],
    'columns' => [
        [
            'filter' => '<div class="fl search_filter_-1">'.Html::activeTextInput($model, 'page_id', ['placeholder' => Yii::t('sys_page','page_id'), 'class' => 'form-control','style' => 'width:100px;']).'</div>',
            'label' => Yii::t('sys_page','page_id'),
            'sortLinkOptions' => ['class' => 'sort-link'],
            'attribute' => 'page_id',
            'enableSorting' => true,
            'value' => function ($data) {
                return $data->page_id;
            },
            'headerOptions' =>  [
                'style' => 'width:75px;',
                'class' => 'column_-1',
            ],
            'contentOptions' =>  [
                'class' => 'column_-1',
            ],
        ],
        [
            'filter' => '<div class="fl search_filter_172">'.Html::activeTextInput($model, 'title', ['placeholder' => Yii::t('sys_page','title'), 'class' => 'form-control','style' => 'width:200px;height:34px;']).'</div>',
            'label' => Yii::t('sys_page','title'),
            'format' => 'raw',
            'attribute' => 'title',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->title . '</span>';
            },
                                                
        ],
        [
            'filter' => '',
            'label' => Yii::t('sys_page','slug'),
            'format' => 'raw',
            'attribute' => 'slug',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->slug . '</span>';
            },
                                                
        ],
        [
            'filter' => '',
            'label' => Yii::t('sys_page', 'Status'),
            'format' => 'raw',
            'attribute' => 'status',
            'enableSorting' => false,
            'value' => function ($data) {
                return Html::checkbox('status', $data->status, [
                            'class' => 'Pcheckbox status_click ace',
                            'checked' => $data->status ? true : false,
                            'data-table' => 'category',
                            'data-statusname' => 'status',
                            'data-primarykey' => 'id',
                            'value' => $data->page_id,
                ]);
            },
            'headerOptions' => [
                    'class' => 'column_424',
             ], 
            'contentOptions' => [
                'class' => 'column_424',
            ],
        ],
        [
            'filter' => '<div class="fl search_filter_404">' .
                            "<div class=\"fl\" style=\"width:300px;\">
                                <div class=\"input-group\">
                                    <span class=\"input-group-addon\">
                                        <i class=\"fa fa-calendar bigger-110\"></i>
                                    </span>
                                    " . Html::activeTextInput($model, 'created_time', array('class' => 'form-control setting_daterangepicker', 'placeholder' => Yii::t('sys_page','created_time'), 'style' => 'width:250px;')) . "
                                </div>
                            </div>" 
                     . '</div>',
            'label' => Yii::t('sys_page','created_time'),
            'format' => 'raw',
            'attribute' => 'created_time',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . UtilityDateTime::formatDateTime($data->created_time) . '</span>';
            },                                                
        ],
        
    ],
]);

?> 
