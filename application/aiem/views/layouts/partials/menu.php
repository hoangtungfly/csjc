<?php

use common\models\admin\SettingsMessageSearch;
use common\models\category\CategoriesSearch;

$menumain = CategoriesSearch::MainMenu();
$alias = $this->context->alias ? $this->context->alias : $this->getParam('alias');
$menuLang = [
    [
        'href'  => '/en',
        'name'  => SettingsMessageSearch::t('lang','english','Tiếng Anh'),
        'lang'  => 'en',
    ],
    [
        'href'  => '/',
        'name'  => SettingsMessageSearch::t('lang','vietnamese','Tiếng Việt'),
        'lang'  => 'vi',
    ],
];
?>


<div class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <div class="language">
                <?php foreach($menuLang as $key => $menu) { ?>
                <a href="<?= $menu['href'] ?>" class="<?= app()->language == $menu['lang'] ? 'active' : '' ?>" title="<?= $menu['name'] ?>"><?= $menu['name'] ?></a>
                <?php if($key < count($menuLang) - 1) { ?>
                <span>|</span>
                <?php } ?>
                <?php } ?>
            </div>
            <ul class="nav navbar-nav menu-bar">
                <?php if ($menumain && isset($menumain[0])) { ?>
                    <?php foreach ($menumain[0] as $key => $menu) { ?>
                        <li class="<?= $menu['alias'] == $alias ? 'active' : '' ?>">
                            <a href="<?= $menu['alias'] ?>" class="<?= $menu['alias'] == $alias ? 'active' : '' ?>" title="<?= $menu['name'] ?>"><?= $menu['name'] ?></a>
                            <?php if (isset($menumain[$key])) { ?>
                                <ul>
                                    <?php foreach ($menumain[$key] as $key2 => $menu2) { ?>
                                        <li class="<?= $menu2['alias'] == $alias ? 'active' : '' ?>">
                                            <a class="<?= $menu2['alias'] == $alias ? 'active' : '' ?>" href="<?= $menu2['alias'] ?>" title="<?= $menu2['name'] ?>"><?= $menu2['name'] ?></a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>