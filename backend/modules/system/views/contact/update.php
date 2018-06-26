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

<div class="page-header">
    <h1><?=Yii::t("admin","Update")?> <?= Yii::t("admin","Contact") ?> id = <?= $model->contact_id ?></h1>
</div>

<?php
    echo $this->render('_form',  array(
        'model'         => $model,
        'idform'        => 'form_update',
    ));
?>