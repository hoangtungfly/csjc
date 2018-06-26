<?php 

use common\models\admin\SettingsStatisticalSearch;

use common\core\enums\StatusEnum;
use common\models\admin\MenuAdminSearch;
use common\utilities\UtilityArray;
use yii\helpers\Url;
$arrayMenu = MenuAdminSearch::getAllMenu();
$menu = UtilityArray::arrayPC($arrayMenu);
$cannonical = Url::canonical();
$model = $this->context->menu_admin;
$arrayActive = [126,125,1];
?>

<?php
    if(isset($menu[0])) {
        foreach ($menu[0] as $key => $item) {
        ?>
            <li class="hover <?= $model && ($item->id == $model->id || $item->id == $model->pid) ? 'active' : ''?>"
                style="display:<?php  if(user()->identity->app_type == common\core\enums\AuthEnum::APP_TYPE_ADMIN && in_array($item->id, $arrayActive)) echo "none";else echo "block"?>">
                <a href="<?= $item->linkMenu() ?>" <?=$item->onclick ? 'data-onclick="1"' : ''?>>
                    <i class="menu-icon fa <?= ($item->icon != '') ? $item->icon : 'fa-desktop' ?>"></i>
                    
                    <span class="menu-text"> <?=Yii::t("admin",trim($item->name))  ?> </span>
                </a>
                <?php if (isset($menu[$key])) { ?>
                    <b class="arrow"></b>
                    <ul class="submenu can-scroll">
                        <?php foreach ($menu[$key] as $key2 => $item2) { ?>
                            <li class="hover <?= $model && ($item2->id == $model->id) ? 'active' : ''?>">
                                <a href="<?= $item2->linkMenu() ?>" <?=$item2->onclick ? 'data-onclick="1"' : ''?>>
                                    <i class="menu-icon fa <?= ($item2->icon != '') ? $item2->icon : 'fa-desktop' ?>"></i>
                                    <span class="menu-children"><?=Yii::t("admin",trim($item2->name))  ?></span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <?php
                }
                ?>
            </li>
            <?php
        }
        
    }
    ?>