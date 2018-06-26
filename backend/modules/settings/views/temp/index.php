<?php 
use backend\widgets\GridView;
use yii\helpers\Html;
use common\models\admin\SettingsGrid;
use yii\helpers\ArrayHelper;
use common\core\enums\StatusEnum;
use common\utilities\UtilityArray;
use common\utilities\UtilityDateTime;

/*BEGIN DISPLAY COLUMN*/
$modelTable = common\models\admin\SettingsTableSearch::findOne(95);
$listGrid = SettingsGrid::find()->select('grid_id,label,status')->where(['table_id' => 95])->all();
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
        <li><a class="breadcrumbsa" href="<?=$this->createUrl('/settings/menuadmin/index')?>"><?=Yii::t("admin","Menu Admin")?></a></li><li><a class="breadcrumbsa" href="<?=$this->createUrl('/settings/temp/index')?>"><?=Yii::t("admin","Temp")?></a></li>        
    </ul>
<?php if (user()->id == 1) { ?>
    <div class="fr">
        <a id="buildgrid_link" href="<?= $this->createUrl('/settings/grid/index', array('SettingsGridSearch[table_id]' => 95)) ?>" class="btn btn-info bnone">Build Grid</a>
        <a id="buildform_link" href="<?= $this->createUrl('/settings/buildform/index', array('table_id' => 95)) ?>" class="btn btn-primary bnone">Build Form</a>
    </div>
<?php } ?>
</div>
<!--END BREADCRUMB-->

<!--BEGIN TITLE-->
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span><?=Yii::t('admin','Temp')?> (<?=$total?>)</span></h1>
    
        
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
                    'table_id' => 95,
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
        
    ],
]);

?> 
