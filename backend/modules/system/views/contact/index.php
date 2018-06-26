<?php 
use backend\widgets\GridView;
use yii\helpers\Html;
use common\models\admin\SettingsGrid;
use yii\helpers\ArrayHelper;
use common\core\enums\StatusEnum;
use common\utilities\UtilityArray;
use common\utilities\UtilityDateTime;

/*BEGIN DISPLAY COLUMN*/
$modelTable = common\models\admin\SettingsTableSearch::findOne(103);
$listGrid = SettingsGrid::find()->select('grid_id,label,status')->where(['table_id' => 103])->all();
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
        <li><a class="breadcrumbsa" href="<?=$this->createUrl('/system/contact/index')?>"><?=Yii::t("admin","Contact")?></a></li>        
    </ul>
<?php if (user()->id == 1) { ?>
    <div class="fr">
        <a id="buildgrid_link" href="<?= $this->createUrl('/settings/grid/index', array('SettingsGridSearch[table_id]' => 103)) ?>" class="btn btn-info bnone">Build Grid</a>
        <a id="buildform_link" href="<?= $this->createUrl('/settings/buildform/index', array('table_id' => 103)) ?>" class="btn btn-primary bnone">Build Form</a>
    </div>
<?php } ?>
</div>
<!--END BREADCRUMB-->

<!--BEGIN TITLE-->
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span><?=Yii::t('admin','Contact')?> (<?=$total?>)</span></h1>
    
        
    <div class="clear"></div>
</div>
<!--END TITLE-->

<?php 
echo GridView::widget([
    'id' => 'user-grid',
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'checkbox' => 1,
    'summaryText'   => Html::dropDownList('displayColumn', $displayValue, $displayColumn, [
                'multiple' => true,
                'data-displayname' => Yii::t("admin","Display column"),
                'data-href' => $this->createUrl('/settings/access/checkgrid', [
                    'table_id' => 103,
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
            'filter' => '<div class="fl search_filter_-1">'.Html::activeTextInput($model, 'contact_id', ['placeholder' => Yii::t('admin','Contact ID'), 'class' => 'form-control','style' => 'width:100px;']).'</div>',
            'label' => Yii::t('admin','Contact ID'),
            'sortLinkOptions' => ['class' => 'sort-link'],
            'attribute' => 'contact_id',
            'enableSorting' => true,
            'value' => function ($data) {
                return $data->contact_id;
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
            'filter' => '',
            'label' => Yii::t('admin','Contact name'),
            'format' => 'raw',
            'attribute' => 'contact_name',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->contact_name . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_427',
],            'contentOptions' => [
	'class' => 'column_427',
],            
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin','Contact email'),
            'format' => 'raw',
            'attribute' => 'contact_email',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->contact_email . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_428',
],            'contentOptions' => [
	'class' => 'column_428',
],            
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin','Contact subject'),
            'format' => 'raw',
            'attribute' => 'contact_subject',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->contact_subject . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_429',
],            'contentOptions' => [
	'class' => 'column_429',
],            
        ],
        [
            'filter' => '',
            'label' => Yii::t('admin','Contact body'),
            'format' => 'raw',
            'attribute' => 'contact_body',
            'enableSorting' => false,
            'value' => function ($data)  {
                return '<span class="D_value">' . $data->contact_body . '</span>';
            },
                        'headerOptions' => [
	'class' => 'column_430',
],            'contentOptions' => [
	'class' => 'column_430',
],            
        ],
        
    ],
]);

?> 
