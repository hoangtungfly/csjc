<?php

use common\utilities\UtilityArray;
use yii\helpers\Html;

if ($modelTable->attrchoice != '') {
    $arrayChoice = json_decode($modelTable->attrchoice);
    $html = '';
    foreach ($arrayChoice as $key => $item) {
        if (isset($item->attribute) && $item->attribute != "" && $item->title != "" && $item->value != "") {
            $html .= Html::a(
                            $item->title, $this->createUrl('/settings/access/updatestatus', array(
                                'name' => $item->attribute,
                                'class' => base64_encode($classTable),
                                'value' => $item->value,
                                'menu_admin_id' => $this->context->menu_admin_id,
                            )), [
                        'class' => 'btn index-header btn-info setting_index_choice',
                    ]) . ' ';
        }
    }
    if ($html != "") {
        echo '<div class="fl"><span>' . Yii::t("admin", "Update") . ':</span> ' . $html . '</div>';
    }
}
?>