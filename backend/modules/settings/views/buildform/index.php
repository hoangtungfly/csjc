<?php

use yii\web\View;
use yii\widgets\ActiveForm;
?>
<div class="breadcrumbs" id="breadcrumbs">
    <script type="text/javascript">
        try {
            ace.settings.check('breadcrumbs', 'fixed')
        } catch (e) {
        }
    </script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="<?= $this->createUrl('/home/details/index') ?>">Home</a>
        </li>
        <li>
            <i class="icon-home home-icon"></i>
            <a href="<?= $this->createUrl('/settings/buildform/index') ?>">Menuadmin</a>
        </li>
    </ul>
</div>
<div class="page-header col-sm-12">
    <div id="D_table" data-href="/settings/buildform/loadlisttable">
        <?php
        echo $this->render('listtable', array(
            'listTable' => $listTable,
        ));
        ?>

    </div>
    <div class="clear"></div>
    <?php
    $form = ActiveForm::begin([
                'id' => 'buildform_update',
                'enableClientValidation' => false,
                'enableAjaxValidation' => true,
                'validateOnChange' => false,
                'validateOnSubmit' => true,
                'validateOnBlur' => false,
                'action' => $this->createUrl('/settings/buildform/updateform'),
    ]);
    ?>
    <?php
    echo $this->render('form', array(
        'table_id' => $table_id,
        'tb' => $tb,
        'mapping' => $mapping,
        'listForm' => $listForm,
        'modelForm' => $modelForm,
        'form_id' => $form_id,
        'listField' => $listField,
        'form' => $form,
        'multi_add' => $multi_add,
    ));
    ?>
    <?php ActiveForm::end(); ?>
</div>