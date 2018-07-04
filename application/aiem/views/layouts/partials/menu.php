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
$config = $this->context->array_config();
?>

<div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav">
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
        <li>
            <a class="icon" href="<?=isset($config['facebook']) ? $config['facebook']: ''?>"><i class="fab fa-facebook" aria-hidden="true"></i></a>
            <a class="icon" href="<?=isset($config['twitter']) ? $config['twitter']: ''?>"><i class="fab fa-twitter" aria-hidden="true"></i></a>
            <a class="icon" href="<?=isset($config['linkin']) ? $config['linkin']: ''?>"><i class="fab fa-linkedin-in"></i></a>
        </li>
<!--        <div class="language">
            <?php foreach($menuLang as $key => $menu) { ?>
                <a href="<?= $menu['href'] ?>" class="<?= app()->language == $menu['lang'] ? 'active' : '' ?>" title="<?= $menu['name'] ?>"><?= $menu['name'] ?></a>
                <?php if($key < count($menuLang) - 1) { ?>
                <span>|</span>
                <?php } ?>
            <?php } ?>
        </div>-->
    </ul>
</div>
<!--/.navbar-collapse -->