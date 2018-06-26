<?php
$arrayIndex = array();
if (isset($_GET['SettingsGridSearch[table_id]'])) {
    $arrayIndex['SettingsGridSearch[table_id]'] = $_GET['SettingsGridSearch[table_id]'];
}
$url = $this->createUrl(app()->controller->id . '/index', $arrayIndex);
echo $this->render('breadcrumb');

if(get_class($model) == 'yii\base\DynamicModel') {
    $primaryKey = 'id';
    $h1 = '';
} else {
    $primaryKey = $model->getKey();
    $h1 = ' id = ' . $model->$primaryKey;
}
?>
<div class="page-header">
    <h1><?=Yii::t("admin","Update")?> <?php echo Yii::t("admin",trim($this->context->menu_admin->name)) . $h1; ?></h1>
</div>
<?php
echo $this->render('form', array(
    'model' => $model,
    'table_id'      => $this->context->table_id,
    'modelTable'    => $this->context->setting_table,
    'idform' => 'form_update',
    'action' => '',
    'primaryKey'    => $primaryKey,
    'multi_add'     => 0,
));
?>