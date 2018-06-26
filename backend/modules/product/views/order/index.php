<?php

use backend\widgets\GridView;
use yii\helpers\Html;
?>

<!--BEGIN BREADCRUMB-->
<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="<?= $this->createUrl('/') ?>"><?= Yii::t('admin', 'Home') ?></a>
        </li>
        <li><a class="breadcrumbsa" href="<?= $this->createUrl('/product/order/index') ?>"><?= Yii::t("admin", "Order") ?></a></li>        
    </ul>
</div>
<!--END BREADCRUMB-->

<!--BEGIN TITLE-->
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span> Orders (<?= $total ?>)</span></h1>
    <div class="clear"></div>
</div>
<div style="margin-bottom: 12px;" class="col-sm-12 pl0 pr0">
    <div class="fr">
    </div>
    <!--END TITLE-->

    <?php
    echo GridView::widget([
        'id' => 'order-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'checkbox' => 1,
//    'summaryText' => Html::dropDownList('displayColumn', $displayValue, $displayColumn, [
//        'multiple' => true,
//        'data-displayname' => Yii::t("admin", "Display column"),
//        'data-href' => $this->createUrl('/settings/access/checkgrid', [
//            'table_id' => 89,
//        ]),
//        'id' => 'displayColumn',
//    ]),
        'columnAction' => 1,
        'btnDeleteNav' => [
            'onoff' => 1,
        ],
        'btnDelete' => [
            'onoff' => 1,
        ],
        'btnCopyNav' => [
            'onoff' => 0,
        ],
        'btnCopy' => [
            'onoff' => 0,
        ],
        'btnView' => [
            'onoff' => 1,
            'data-onclick' => 0,
        ],
        'btnUpdate' => [
            'onoff' => 1,
            'data-onclick' => 0,
        ],
        'btnAddNav' => [
            'onoff' => 1,
            'data-onclick' => 0,
        ],
        'columns' => [
            [
                'filter' => '',
                'label' => Yii::t('admin', 'Order ID'),
                'sortLinkOptions' => ['class' => 'sort-link'],
                'attribute' => 'id',
                'enableSorting' => true,
                'value' => function ($data) {
            return $data->id;
        },
                'headerOptions' => [
                    'style' => 'width:75px;',
                    'class' => 'column_-1',
                ],
                'contentOptions' => [
                    'class' => 'column_-1',
                ],
            ],
            [
                'filter' => '',
                'label' => Yii::t('admin', 'Firstname'),
                'format' => 'raw',
                'attribute' => 'customer_firstname',
                'enableSorting' => false,
                'value' => function ($data) {
            return '<span class="D_value">' . $data->customer_firstname . '</span>';
        },
            ],
            [
                'filter' => '<div class="fl search_filter_403">' . Yii::t('admin', 'Customer lastname') . ': ' . Html::activeTextInput($model, 'customer_lastname', ['placeholder' => Yii::t('admin', 'Lastname'), 'style' => 'width:200px;height:34px;']) . '</div>',
                'label' => Yii::t('admin', 'Lastname'),
                'format' => 'raw',
                'attribute' => 'customer_lastname',
                'enableSorting' => false,
                'value' => function ($data) {
            return '<span class="D_value">' . $data->customer_lastname . '</span>';
        },
            ],
            [
                'filter' => '',
                'label' => Yii::t('admin', 'Email'),
                'format' => 'raw',
                'attribute' => 'customer_email',
                'enableSorting' => false,
                'value' => function ($data) {
            return '<span class="D_value">' . $data->customer_email . '</span>';
        },
            ],
            [
                'filter' => '',
                'label' => Yii::t('admin', 'Address'),
                'format' => 'raw',
                'attribute' => 'customer_address',
                'enableSorting' => false,
                'value' => function ($data) {
            return '<span class="D_value">' . $data->customer_address . '</span>';
        },
            ],
            [
                'filter' => '',
                'label' => Yii::t('admin', 'Phone'),
                'format' => 'raw',
                'attribute' => 'customer_phone',
                'enableSorting' => false,
                'value' => function ($data) {
            return '<span class="D_value">' . $data->customer_phone . '</span>';
        },
            ],
            [
                'filter' => '',
                'label' => Yii::t('admin', 'Country'),
                'format' => 'raw',
                'attribute' => 'country_id',
                'enableSorting' => false,
                'value' => function ($data) {
            return '<span class="D_value">' . $data->country_id . '</span>';
        },
            ],
            [
                'filter' => '',
                'label' => Yii::t('admin', 'State ID'),
                'format' => 'raw',
                'attribute' => 'state_id',
                'enableSorting' => false,
                'value' => function ($data) {
            return '<span class="D_value">' . $data->state_id . '</span>';
        },
            ],
            [
                'filter' => '',
                'label' => Yii::t('admin', 'Post code'),
                'format' => 'raw',
                'attribute' => 'post_code',
                'enableSorting' => false,
                'value' => function ($data) {
            return '<span class="D_value">' . $data->post_code . '</span>';
        },
            ],
            [
                'filter' => '',
                'label' => Yii::t('admin', 'Shipping Method'),
                'format' => 'raw',
                'attribute' => 'shipping_method',
                'enableSorting' => false,
                'value' => function ($data) {
            return '<span class="D_value">' . $data->shipping_method . '</span>';
        },
            ],
            [
                'filter' => '',
                'label' => Yii::t('admin', 'Status'),
                'format' => 'raw',
                'attribute' => 'status',
                'enableSorting' => false,
                'value' => function ($data) {
            return '<span class="D_value">' . $data->status . '</span>';
        },
            ],
        ],
    ]);
    ?> 
