<?php

echo $this->render('@app/views/layouts/settings/breadcrumb');
?>
<div class="page-header">
    <h1><?= Yii::t("admin", "Update") ?> <?= Yii::t("admin", trim($this->context->menu_admin->name)) ?></h1>
</div>

<?= $this->render('_form',['model' => $model,'url' => $this->createUrl('update',['id' => $model->id])])  ?>
