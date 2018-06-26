<?php

use common\core\enums\admin\AdminEnum;
use common\core\form\GlobalActiveForm;
use common\models\admin\SettingsFieldSearch;
use common\models\admin\SettingsFormSearch;
use common\utilities\UtilityArray;

$loadiframe = (int)$this->getParam('loadiframe');

$listForms = SettingsFormSearch::listFormByTable($this->context->table_id, $multi_add);
$listFieldsAll = SettingsFieldSearch::listFieldByTable($this->context->table_id, $multi_add);
$listFields = array();
if($listFieldsAll) {
    foreach($listFieldsAll as $key => $item) {
        $listFields[$item->form_id][] = $item;
    }
}

if(get_class($model) == 'yii\base\DynamicModel') {
    $did = 999999999;
} else {
    $did = $model->isNewRecord ? '0' : $model->$primaryKey;
}
?>

<?php if(!$multi_add) { ?>
<input type="hidden" id="table_id" value="<?= $table_id ?>" />
<input type="hidden" id="tmp" value="<?= $this->context->setting_table->table_name ?>" />
<input type="hidden" id="did" value="<?=$did?>" />
<?php } ?>
<?php
$form = GlobalActiveForm::begin([
    'id' => $idform,
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
    'validateOnChange' => false,
    'validateOnSubmit' => false,
    'validateOnBlur' => false,
    'action' => $action,
    'fieldConfig' => [
        'template' => '{input}<div class="clear"></div>{error}',
        'options' => [
            'class' => '',
        ]
    ],
    'options'   => [
        'class' => 'form-horizontal formsortable',
        'role' => 'role',
        'onsubmit' => 'return false;',
    ]
]);
?>


<?php if(!$multi_add) { ?>
<input type="hidden" name="imageid" id="imageid" value="" />
<input type="hidden" name="imageiddelete" id="imageiddelete" value="" />
<input type="hidden" name="imageiddeletename" id="imageiddeletename" value="" />

<input type="hidden" name="fileid" id="fileid" value="" />
<input type="hidden" name="fileiddelete" id="fileiddelete" value="" />
<input type="hidden" name="fileiddeletename" id="fileiddeletename" value="" />
<?php } ?>
<div class="row">
    <div class="col-xs-12 d_form_left">
        <div class="col-xs-12 panel-group accordion-style1 accordion-style2">
            <?php
            $dem = 1;
            if ($listForms && count($listForms) > 0) {
                foreach ($listForms as $key => $modelForm) {
                    switch ($modelForm->line) {
                        case AdminEnum::FORM_LINE_2IN1:
                            include(__DIR__ . '/form/line2in1.php');
                            break;
                        case AdminEnum::FORM_LINE_3IN1:
                            include(__DIR__ . '/form/line3in1.php');
                            break;
                        case AdminEnum::FORM_LINE_4IN1:
                            include(__DIR__ . '/form/line4in1.php');
                            break;
                        default:
                            include(__DIR__ . '/form/default.php');
                            break;
                    }
                    $dem++;
                }
            }
            $arrayIndex = array();
            if(isset($_GET['SettingsGridSearch']['table_id'])) {
                $arrayIndex['SettingsGridSearch[table_id]'] = $_GET['SettingsGridSearch']['table_id'];
            }
            $a = UtilityArray::getLinkAndParam($this->context->menu_admin->linkmenu());
            $link = $a['link'];
            $arrayIndex = array_merge($arrayIndex,$a['params']);
            $url = base64_encode($this->createUrl($link, $arrayIndex));
            if (isset($_REQUEST['urlb'])) {
                $url = $_REQUEST['urlb'];
            }
            ?>
            <?php if(!$multi_add) { ?>
            <div class="col-sm-12" style="margin-bottom: 10px;">
                <div class="col-sm-12 D_form_submit" style="text-align:center;">
                    <input type="hidden" value="<?= $url ?>" name="urlb" />
                    <input type="submit" class="btn btn-primary" id="D_update_submit" value="<?=Yii::t("admin","Save")?>" style="border:0px;" />
                    <?php if(!$loadiframe) { ?>
                    <a class="btn btn-success D_cancel" href="<?= base64_decode($url)  ?>" style="border:0px;"><?=Yii::t("admin","Back")?></a>
                    <?php } else { ?>
                    <button class="btn btn-success bnone" onclick="$(this).closeAllPopup({id:'IDLOpopup'}); return false;">Close</button>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>

        </div><!-- form -->
    </div>
    <?php if($multi_add) { ?>
    <button type="button" class="btn bnone delete_multi_div_recod"><i class="fa fa-minus"></i></button>
    <?php } ?>
</div>
<?php GlobalActiveForm::end(); ?>
<?php if (user()->id == 1 && $listForms && count($listForms) > 0 && !$loadiframe && !$multi_add) { ?>
    <div id="formArrange">Arrange fast form</div>
    <div id="formArrange-description">
        <p><span id="formArrange-close"></span></p>
            <?php
            $dem = 1;
            foreach ($listForms as $key => $modelForm) {
                ?>
            <div data-id="<?= $modelForm->form_id ?>">Form <?= $dem ?> - <?= $modelForm->form_name ?></div>
            <?php
            $dem++;
        }
        ?>
    </div>
<script>
    $('#formArrange-description').css('margin-top', '-' + ($('#formArrange-description').height() / 2) + 'px');
    $(document).ready(function(e) {
        setTimeout(function() {

            $('.D_panel').each(function(e) {
                $(this).css('top', ($(this).parent().parent().height() / 2 - 10) + 'px');
            });
        }, 1000);
        $('body').on('click', '#formArrange', function(e) {
            $("#formArrange-description").css('right', '0px');
            $(this).hide();
        });

        $('body').on('click', '#formArrange-close', function(e) {
            $("#formArrange-description").css('right', '-1000px');
            $('#formArrange').show();
        });

        $("#formArrange-description").sortable({
            stop: function(event, ui) {
                var a = [];
                $("#formArrange-description").find('div').each(function(i) {
                    if ($(this).data('id'))
                        a[i] = $(this).data('id');
                });
                loadingFull();
                $.ajax({
                    url: '<?= $this->createUrl('/settings/access/arrangeform') ?>',
                    type: 'POST',
                    dateType: 'json',
                    data: {arrayOrder: a},
                    success: function(rs) {
                        loadAjaxGridView(window.location.href);
                    }
                });
            },
        });
    });
</script>
<?php } ?>