<?php

use common\core\enums\StatusEnum;
use common\models\admin\MenuAdminSearch;
use common\utilities\UtilityArray;
use yii\helpers\Url;
$arrayMenu = MenuAdminSearch::getAllMenu();
$menu = UtilityArray::arrayPC($arrayMenu);
$cannonical = Url::canonical();
$model = isset($this->context->menu_admin) ? $this->context->menu_admin : false;
?>

<ul class="nav nav-list" style="top: 0px;">
    <li class="hover  <?= ($cannonical == HOST_BACKEND) ? 'active' : '' ?>">
        <a class="menu_header" href="<?=$this->context->homeUrl?>">
            <i class="menu-icon fa fa-tachometer"></i>
            <span class="menu-text"> Home </span>
        </a>
        <b class="arrow"></b>
    </li>
    <?php
    if(isset($menu[0])) {
        foreach ($menu[0] as $key => $item) { 
            if($key == 1 && user()->id != 1) {
                continue; 
            }
        ?>
            <li class="hover <?= $model && ($item->id == $model->id || $item->id == $model->pid) ? 'active' : ''?>">
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
</ul>
