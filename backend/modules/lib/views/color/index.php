<?php

use backend\widgets\GridView;
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
        <li><a class="breadcrumbsa" href="<?=$this->createUrl('/category/category/index')?>"><?=Yii::t("lib","color_page_header")?></a></li>        
    </ul>
</div>
<!--END BREADCRUMB-->

<!--BEGIN TITLE-->
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span><?=Yii::t('lib','color_page_header')?> (<?=$total?>)</span></h1>
        
    <div class="clear"></div>
</div>
<!--END TITLE-->

<?php 
echo GridView::widget([
    'id' => 'user-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'checkbox' => 0,
    'columnAction'  => 1,
    'renderNavLeft' => "",
    'checkbox'  =>1,
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
            'filter' => '<div class="fl search_filter_-1">'.Html::activeTextInput($model, 'id', ['placeholder' => Yii::t('lib','color_id'), 'class' => 'form-control','style' => 'width:100px;']).'</div>',
            'label' => Yii::t('lib','color_id'),
            'sortLinkOptions' => ['class' => 'sort-link'],
            'attribute' => 'id',
            'enableSorting' => true,
            'value' => function ($data) {
                return $data->id;
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
            'filter' => '<div class="fl search_filter_418">' . Yii::t('lib','color_name') . ': ' . Html::activeTextInput($model, 'name', ['placeholder' => Yii::t('lib','color_name'),'style' => 'width:200px;height:34px;']) . '</div>',
            'label' => Yii::t('lib','color_name'),
            'format' => 'raw',
            'attribute' => 'name',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->name . '</span>';
            },
            'headerOptions' => [
                'class' => 'column_418',
            ],
            'contentOptions' => [
                'class' => 'column_418',
            ],
        ],
        [
            'filter' => '',
            'label' => Yii::t('lib','color'),
            'format' => 'raw',
            'attribute' => 'color',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->color . '</span>';
            },
            'headerOptions' => [
                'class' => 'column_420',
            ],
            'contentOptions' => [
                    'class' => 'column_420',
            ],            
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin','Status'),
            'format' => 'raw',
            'attribute' => 'status',
            'enableSorting' => false,
            'value' => function ($data) {
                return Html::checkbox('status', $data->status, [
                        'class' => 'Pcheckbox status_click ace',
                        'checked' => $data->status ? true : false,
                        'data-table' => 'lib_color',
                        'data-statusname' => 'status',
                        'data-primarykey' => 'id',
                        'value' => $data->id,
                ]);
            },
            'headerOptions' => [
                    'class' => 'column_424',
            ],            'contentOptions' => [
                    'class' => 'column_424',
            ],            
        ],
    ],
]);
?> 
