<?php

use common\core\enums\StatusEnum;

if ($modelTable->attrarange != '') {
    $arrayOrder = json_decode($modelTable->attrarange);
    ?>
    <div class="fr">
        <label class="fl" style="margin:5px 10px 0px 0px;"><?= Yii::t("admin", "Order by") ?></label>
        <?php
        foreach ($arrayOrder as $item) {
            if (isset($item->attribute) && $item->attribute != "") {
                $attr = [];
                $attr['attr'] = $item->attribute;
                $attr['attrodr'] = $item->orderby;
                $attr['table_id'] = $modelTable->table_id;
                if($item->flag == '0') {
                    $attr['flag'] = 0;
                } else if($item->flag == 1 || $model->hasAttribute('pid')){
                    $attr['flag'] = 1;
                }
                $attr['label'] = $item->name;
                $attr['menu_admin_id'] = $this->context->menu_admin_id;
                if (isset($get['SettingsGridSearch']['table_id'])) {
                    $attr['gridid'] = 'table_id||' . $get['SettingsGridSearch']['table_id'];
                }
                ?>
                <a class="btn btn-bold index-header arrangeodr" href="<?= $this->createUrl('/settings/access/arrange', $attr) ?>"><?= isset($item->label) ? $item->label : $model->attributeLabels()[$item->attribute] ?></a>
            <?php
            }
        }
        ?>
    </div>
<?php } ?>