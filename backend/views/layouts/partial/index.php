<?php
$arrayCreate = array();
$arrayCreate['urlb'] = base64_encode($this->context->curl);
if ($this->context->table_id) {
    $arrayCreate['table_id'] = $this->context->table_id;
}
if ($this->context->menu_admin->id) {
    $arrayCreate['menu_id'] = $this->context->menu_admin->id;
}
?>
<?php echo $this->context->breadcrumb() ?>
<input type="hidden" id="dungid" value="<?= $this->context->menu_admin->id ?>" />
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span><?= Yii::t("admin",trim($this->context->menu_admin->name)) ?></span></h1>
    <?php
    if ($modelTable->attrsearch != '') {
        echo '<div class="fl">';
        echo '<a href="javascript:void(0);" data-id="all" class="btn index-header setting_attsearch" data-href="' . $this->createUrl($this->context->menu_admin->linkMenu()) . '">'.Yii::t('admin','Tất cả').'</a>';
        $arraySearch = explode(',', $modelTable->attrsearch);
        foreach ($arraySearch as $key => $value) {
            $array = explode('||', $value);
            $value = $array[0];
            $valueodr = $value . 'odr';

            $title = isset($array[1]) ? $array[1] : $array[0];

            $getname = $this->class . '[' . $value . ']';

            $vl = isset($array[2]) ? $array[2] : 1;

            ?>
            <a href="javascript:void(0);" data-vl='<?= $vl ?>' data-id="<?= $getname ?>" class="btn index-header DD_attsearch " data-href="<?= $this->createUrl('/admin/' . strtolower($className) . '/index', array($getname => $vl)) ?>"><?= $title ?></a>
            <?php
        }
        echo '</div>';
    }
    if ($modelTable->attrarange != '') {
        $arrayOrder = explode(',', $modelTable->attrarange);
        ?>
        <div class="fr">
            <label class="fl" style="margin:5px 10px 0px 0px;"><?=Yii::t("admin","Sắp xếp")?></label>
            <?php foreach ($arrayOrder as $key => $value) { ?>
                <?php
                $array = explode('||', $value);
                $attr['attr'] = $array[0];
                $value = $array[1];
                $attr['attrodr'] = $array[0] . 'odr';
                if (isset($array[2]))
                    $attr['attrodr'] = $array[2];
                $cl = get_class($model);
                $attr['class'] = $cl;
                $attr['flag'] = isset($array[3]) ? $array[3] : 1;
                if (isset($_GET['DDgrid']['table_id'])) {
                    $attr['gridid'] = 'table_id||' . $_GET['DDgrid']['table_id'];
                }
                if ($cl::model()->count($attr['attr'] . ' = ' . Enum::STATUS_ACTIVED) > 0) {
                    ?>
                    <a href="javascript:void(0);" class="btn btn-bold index-header arrangeodr" data-href="<?= $this->createUrl('access/arrange', $attr) ?>"><?= $value ?></a>
                <?php }
            }
            ?>
        </div>
    <?php } ?>
</div>
<?php if (isset($htmlComment)) {
    echo $htmlComment;
} ?>
<div class="col-sm-12 pl0 pr0" style="margin-bottom: 12px;">

    <div class="fr">
<?php if ($this->modelMenu->add == 1) { ?>
            <a href="javascript:void(0);" id="D_Create" class="btn index-header btn-success" data-href="<?= $this->createUrl('/admin/' . strtolower($className) . '/create', $arrayCreate) ?>">
                <i class="fa fa-plus"></i> <?=Yii::t("admin","Thêm")?> <?= Yii::t("admin",$this->modelMenu->name) ?>
            </a>
        <?php } ?>
<?php if ($this->modelMenu->delete == 1) { ?>
            <a href="javascript:void(0);" class="btn index-header btn-danger" id="DeleteAll" data-href="<?= $this->createUrl('/admin/' . strtolower($className) . '/alldelete') ?>">
                <i class="fa fa-trash-o"></i> <?=Yii::t("admin","Xóa")?>
            </a>
<?php } ?>
<?php if (user()->id == 1 || $this->modelMenu->copy == 1) { ?>
            <a href="javascript:void(0);" class="btn index-header btn-primary" id="CopyAll" data-href="<?= $this->createUrl('/admin/' . strtolower($className) . '/allcopy') ?>">
                <i class="fa fa-copy"></i> Copy
            </a>
<?php } ?>
<?php if ($this->modelMenu->cronlink == 1) { ?>
            <a href="javascript:void(0);" class="btn index-header btn-success" id="Cronweb" data-href="<?= $this->createUrl('/admin/' . strtolower($className) . '/cronweb',array('table_id' => $modelTable->id)) ?>">
                <i class="fa fa-crop"></i> Cronweb
            </a>
<?php } ?>
<?php if ($this->modelMenu->import == 1) { ?>
        <a href="javascript:void(0);" id="D_Import" class="btn index-header btn-warning " data-href="<?= $this->createUrl('/admin/' . strtolower($className) . '/importexcel', $arrayCreate) ?>">
            <i class="fa fa-exchange"></i> Import <?= strtolower(Yii::t("admin",$this->modelMenu->name)) ?>
        </a>
        <a class="btn index-header btn-warning " href="<?= $this->createUrl('/admin/' . strtolower($className) . '/exportexcel', $_GET) ?>">
            <i class="fa fa-exchange"></i> Export <?= strtolower(Yii::t("admin",$this->modelMenu->name)) ?>
        </a>
        <a class="btn index-header btn-bold " id="uploadImageImport" href="<?=$this->createUrl("/admin/access/thuvienanh")?>">
            <i class="fa fa-exchange"></i> Upload ảnh import
        </a>
        <?php } ?>
    </div>

    <?php
    if ($modelTable->attrchoice != '') {
        echo '<div class="fl"><span>'.Yii::t("admin","Cập nhật").':</span> ';
        $arrayChoice = DArray::convertStringToArrayByConmmaAndOr($modelTable->attrchoice);
        foreach ($arrayChoice as $key => $item) {
            echo CHtml::link($item[1], 'javascript:void(0);', array(
                'class' => 'btn index-header btn-info D_index_choice',
                'data-href' => $this->createUrl('/admin/access/updatestatus', array('name' => $item[0], 'class' => get_class($model))),
            )) . ' ';
        }
        echo '</div>';
    }
    ?>
</div>
<div class="row">
    <div class="col-xs-12">
<?php $this->renderPartial('../layouts/index_proccess', array('model' => $model, 'listIndex' => $listIndex, 'modelTable' => $modelTable, 'table_id' => $table_id)); ?>  
    </div>
</div>