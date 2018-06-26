<?php

use backend\widgets\GridView;
use common\core\enums\StatusEnum;
use common\models\admin\SettingsGrid;
use common\models\admin\SettingsTableSearch;
use common\utilities\UtilityHtmlFormat;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/*BEGIN DISPLAY COLUMN*/
$modelTable = SettingsTableSearch::findOne(94);
$listGrid = SettingsGrid::find()->select('grid_id,label,status')->where(['table_id' => 94])->all();
$displayValue = [];
if($modelTable) {
    if($modelTable->columncheck) {
        $displayValue[] = '-3';
    }
    if($modelTable->columnid) {
        $displayValue[] = '-1';
    }
    if($modelTable->columnaction) {
        $displayValue[] = '-2';
    }
}
foreach($listGrid as $item) {
    if($item->status == StatusEnum::STATUS_ACTIVED) {
        $displayValue[] = $item->grid_id;
    }
}
$get = r()->get();
$displayColumn = ['-3' => 'Check','-1' => 'Id',];
$displayColumn += ArrayHelper::map($listGrid, 'grid_id', 'label');
$displayColumn += ['-2' => 'Action',];

/*END DISPLAY COLUMN*/
?>

<!--BEGIN BREADCRUMB-->
<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="<?= $this->createUrl('/') ?>"><?=Yii::t('admin','Home')?></a>
        </li>
        <li><a class="breadcrumbsa" href="<?=$this->createUrl('/category/category/index')?>"><?=Yii::t("admin","Category")?></a></li>        
    </ul>
<?php if (user()->id == 1) { ?>
    <div class="fr">
        <a id="buildgrid_link" href="<?= $this->createUrl('/settings/grid/index', array('SettingsGridSearch[table_id]' => 94)) ?>" class="btn btn-info bnone">Build Grid</a>
        <a id="buildform_link" href="<?= $this->createUrl('/settings/buildform/index', array('table_id' => 94)) ?>" class="btn btn-primary bnone">Build Form</a>
    </div>
<?php } ?>
</div>
<!--END BREADCRUMB-->

<!--BEGIN TITLE-->
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span><?=Yii::t('admin','Category')?> (<?=$total?>)</span></h1>
    
        
    <div class="clear"></div>
</div>
<!--END TITLE-->

<?php 
echo GridView::widget([
    'id' => 'user-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'checkbox' => 0,
    'summaryText'   => Html::dropDownList('displayColumn', $displayValue, $displayColumn, [
                'multiple' => true,
                'data-displayname' => Yii::t("admin","Display column"),
                'data-href' => $this->createUrl('/settings/access/checkgrid', [
                    'table_id' => 94,
                ]),
                'id'    => 'displayColumn',
    ]),
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
            'filter' => '<div class="fl search_filter_-1">'.Html::activeTextInput($model, 'id', ['placeholder' => Yii::t('admin','ID'), 'class' => 'form-control','style' => 'width:100px;']).'</div>',
            'label' => Yii::t('admin','ID'),
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
            'filter' => '<div class="fl search_filter_418">' . Yii::t('admin','Name') . ': ' . Html::activeTextInput($model, 'name', ['placeholder' => Yii::t('admin','Name'),'style' => 'width:200px;height:34px;']) . '</div>',
            'label' => Yii::t('admin','Name'),
            'format' => 'raw',
            'attribute' => 'name',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->name . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_418',
],            'contentOptions' => [
	'class' => 'column_418',
],            
        ],
        [
            'filter' => '<div class="fl search_filter_419">' . Yii::t('admin','Pid') . ': ' . Html::activeTextInput($model, 'pid', ['placeholder' => Yii::t('admin','Pid'),'style' => 'width:200px;height:34px;']) . '</div>',
            'label' => Yii::t('admin','Pid'),
            'format' => 'raw',
            'attribute' => 'pid',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . UtilityHtmlFormat::numberformat($data->pid) . '</span>';
            },                        'headerOptions' => [
	'class' => 'column_419',
],            'contentOptions' => [
	'class' => 'column_419',
],            
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin','Meta title'),
            'format' => 'raw',
            'attribute' => 'meta_title',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->meta_title . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_420',
],            'contentOptions' => [
	'class' => 'column_420',
],            
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin','Meta keyword'),
            'format' => 'raw',
            'attribute' => 'meta_keyword',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->meta_keyword . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_421',
],            'contentOptions' => [
	'class' => 'column_421',
],            
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin','Meta description'),
            'format' => 'raw',
            'attribute' => 'meta_description',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->meta_description . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_422',
],            'contentOptions' => [
	'class' => 'column_422',
],            
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin','Domain'),
            'format' => 'raw',
            'attribute' => 'domain',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->domain . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_423',
],            'contentOptions' => [
	'class' => 'column_423',
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
                        'data-table' => 'category',
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
        [
            'filter' => '',
            'label' => Yii::t('admin','Limitproduct'),
            'format' => 'raw',
            'attribute' => 'limitproduct',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->limitproduct . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_425',
],            'contentOptions' => [
	'class' => 'column_425',
],            
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin','Lang'),
            'format' => 'raw',
            'attribute' => 'lang',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->lang . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_426',
],            'contentOptions' => [
	'class' => 'column_426',
],            
        ],
        
    ],
]);

?> 
