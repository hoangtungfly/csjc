<?php

use common\models\category\CategoriesSearch;

$config = $this->context->array_config();
$menufooter = CategoriesSearch::FooterMenu();
$alias = $this->getParam('alias');
$phone = isset($config['hotline']) ? $config['hotline'] : '';
$email = isset($config['email']) ? $config['email'] : '';
?>

<div class="container-fluid section_5">
    <div class="row">
        <div class="container">
            <div class="col-xs-4">
                <h4>Quick Links</h4>
                <ul>
                <?php if($menufooter) { ?>
                        <?php foreach($menufooter as $key => $menu) { ?>
                    <li>
                        <a href="<?= $menu['alias'] ?>" 
                           class="<?= $menu['alias'] == $alias ? 'active' : '' ?>" title="<?= $menu['name'] ?>"><?= $menu['name'] ?></a>
                    </li>
                        <?php } ?>
                <?php } ?>
                </ul>
            </div>
            <div class="col-xs-4">
                <h4>Contact</h4>
                <ul>
                    <li><a href="javascript:void(0);">ADD: <?=isset($config['address']) ? $config['address'] : ''?></a></li>
                    <?php if($phone){?>
                    <li><a href="tel:<?=$phone?>">TEL : <?=($phone)?></a></li>
                    <?php } ?>
                    <?php if($phone){?>
                    <li><a href="mailto:<?=$email?>">EMAIL: <?= ($email)?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-xs-4">
                <h4>Connect With Us</h4>
                <a href="<?=isset($config['facebook']) ? $config['facebook']: ''?>"><i class="fab fa-facebook" aria-hidden="true"></i></a>
                <a href="<?=isset($config['twitter']) ? $config['twitter']: ''?>"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                <a href="<?=isset($config['linkin']) ? $config['linkin']: ''?>"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</div>
<footer class="container-fluid">
    <div class="row">
        <div class="container">
            <p class="text-center"><?=isset($config['copyright']) ? $config['copyright'] : ''?></p>
        </div>
    </div>
</footer>