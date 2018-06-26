<?php

use yii\helpers\Html;

?>
<div class="detail-s-box" id="detail-section-1" style="margin-top:10px;">
    <div class="wrap-tab-section">
        <div id="load_section">
            <?php
            echo $this->render('section', array(
                'listForm' => $listForm,
            ));
            ?>
        </div>
        <div class="newsection-tab">
            <a id="addnewitem" href="<?php echo $this->createUrl('/settings/buildform/createform', array('id' => $table_id,'multi_add' => $multi_add,)); ?>" data-toggle="tab">
                <i class="fa fa-plus"></i>New section
            </a>
        </div>
    </div>
    <div class="clear"></div>
    <div class="tab-content">
        <div id="name-section-1" class="content-section">
            <div class="fan-line pro-view wif-title" id="D_formbuilder">
                <?php
                echo $this->render('_item', array(
                    'table_id' => $table_id,
                    'tb' => $tb,
                    'mapping' => $mapping,
                    'modelForm' => $modelForm,
                    'form_id' => $form_id,
                    'listField' => $listField,
                ));
                ?>
            </div>
            <div class="btn-section-frm">
                <div id="savebuildform" style="margin-right:0px;z-index:100;">
<?= Html::submitButton('Save', array('class' => 'btn btn-success', 'id' => 'updateform', 'data-href' => $this->createurl('/settings/buildform/updateform/'))); ?>
                </div>
            </div>
        </div>
    </div>
</div>