<?php
echo $this->render('breadcrumb');
$arrayCreate = array();
?>
<div class="page-header">
<h1><?=Yii::t("admin","Create")?> <?= Yii::t("admin",trim($this->context->menu_admin->name)) ?></h1>
</div>

<?php
    echo $this->render('form',  array(
        'model'         => $model,
        'table_id'      => $this->context->table_id,
        'modelTable'    => $this->context->setting_table,
        'idform'        => 'D_form_create',
        'action'        => '',
        'primaryKey'    => $model->getKey(),
        'multi_add'     => 0,
    ));
?>