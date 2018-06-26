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
        <li><a class="breadcrumbsa" href="<?=$this->createUrl('/category/category/index')?>"><?=Yii::t("lib","Category")?></a></li>        
    </ul>
</div>
<!--END BREADCRUMB-->

<!--BEGIN TITLE-->
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span><?=Yii::t('lib','country_page_header')?> (<?=$total?>)</span></h1>
        
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
            'filter' => '<div class="fl search_filter_-1">'.Html::activeTextInput($model, 'country_code', ['placeholder' => Yii::t('lib','country_code'), 'class' => 'form-control','style' => 'width:100px;']).'</div>',
            'label' => Yii::t('lib','country_code'),
            'sortLinkOptions' => ['class' => 'sort-link'],
            'attribute' => 'country_code',
            'enableSorting' => true,
            'value' => function ($data) {
                return $data->country_code;
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
            'filter' => '<div class="fl search_filter_418">' . Yii::t('lib','country_name') . ': ' . Html::activeTextInput($model, 'country_name', ['placeholder' => Yii::t('lib','country_name'),'style' => 'width:200px;height:34px;']) . '</div>',
            'label' => Yii::t('lib','country_name'),
            'format' => 'raw',
            'attribute' => 'country_name',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->country_name . '</span>';
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
            'label' => Yii::t('lib','phone_code'),
            'format' => 'raw',
            'attribute' => 'phone_code',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->phone_code . '</span>';
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
            'label' => Yii::t('lib','key_search'),
            'format' => 'raw',
            'attribute' => 'meta_title',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->keysearch . '</span>';
            },
            'headerOptions' => [
                'class' => 'column_420',
            ],
            'contentOptions' => [
                    'class' => 'column_420',
            ],            
        ],
    ],
]);
?> 
