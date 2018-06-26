<?php

use backend\widgets\GridView;
use yii\helpers\Html;
use common\models\admin\SettingsGrid;
use yii\helpers\ArrayHelper;
use common\core\enums\StatusEnum;
use common\utilities\UtilityArray;
use common\utilities\UtilityDateTime;

/* BEGIN DISPLAY COLUMN */
//$listGrid = SettingsGrid::find()->select('grid_id', 'label')->where(['table_id' => 89])->all();
//$displayValue = [];
//foreach ($listGrid as $item) {
//    if ($item->status == StatusEnum::STATUS_ACTIVED) {
//        $displayValue[] = $item->grid_id;
//    }
//}
//$get = r()->get();
//$displayColumn = array_merge(['-3' => 'Check', '-1' => 'Id',], ArrayHelper::map($listGrid, 'grid_id', 'label'), ['-2' => 'Action',]);
//$displayValue = implode(',', $displayValue);

/* END DISPLAY COLUMN */
?>

<!--BEGIN BREADCRUMB-->
<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="<?= $this->createUrl('/') ?>"><?= Yii::t('admin', 'Home') ?></a>
        </li>
        <li><a class="breadcrumbsa" href="<?= $this->createUrl('/user/details/index') ?>"><?= Yii::t("admin", "User") ?></a></li>        
    </ul>
</div>
<!--END BREADCRUMB-->

<!--BEGIN TITLE-->
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span><?= Yii::t('admin', 'User') ?> (<?= $total ?>)</span></h1>
    <div class="clear"></div>
</div>
<!--END TITLE-->

<?php
echo GridView::widget([
    'id' => 'user-grid',
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
    'btnDelete' => [
        'onoff' => 1,
    ],
    'btnUpdate' => [
        'onoff' => 1,
        'data-onclick' => 0,
    ],
    'columns' => [
        [
            'filter' => '',
            'label' => Yii::t('admin', 'User ID'),
            'sortLinkOptions' => ['class' => 'sort-link'],
            'attribute' => 'user_id',
            'enableSorting' => true,
            'value' => function ($data) {
            return $data->user_id;
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
            'attribute' => 'firstname',
            'enableSorting' => false,
            'value' => function ($data) {
        return '<span class="D_value">' . $data->firstname . '</span>';
    },
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin', 'Lastname'),
            'format' => 'raw',
            'attribute' => 'lastname',
            'enableSorting' => false,
            'value' => function ($data) {
        return '<span class="D_value">' . $data->lastname . '</span>';
    },
        ],
        [
            'filter' => '<div class="fl search_filter_403">' . Yii::t('admin', 'Email') . ': ' . Html::activeTextInput($model, 'email', ['placeholder' => Yii::t('admin', 'Email'), 'style' => 'width:200px;height:34px;']) . '</div>',
            'label' => Yii::t('admin', 'Email'),
            'format' => 'raw',
            'attribute' => 'email',
            'enableSorting' => false,
            'value' => function ($data) {
        return '<span class="D_value">' . $data->email . '</span>';
    },
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin', 'Birthday'),
            'format' => 'raw',
            'attribute' => 'birthday',
            'enableSorting' => false,
            'value' => function ($data) {
        return '<span class="D_value">' . UtilityDateTime::formatDate($data->birthday) . '</span>';
    },
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin', 'Phone'),
            'format' => 'raw',
            'attribute' => 'phone',
            'enableSorting' => false,
            'value' => function ($data) {
        return '<span class="D_value">' . $data->phone . '</span>';
    },
        ],        
    ],
]);
?> 

