<?php

use common\models\category\CategoriesSearch;

$config = $this->context->array_config();
$menufooter = CategoriesSearch::FooterMenu();
$alias = $this->getParam('alias');
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
                    <li><a href="#">ADD: NO 32, LOT 06, ZONE 4.1CC, LANG HA -  THANH XUAN STREET, NHAN CHINH WARD, THANH XUAN DISTRICT,  HA NOI</a></li>
                    <li><a href="tel:+84-24-6652-3588">TEL : +84-24-6652-3588</a></li>
                    <li><a href="mailto:IBICON.INFO@GMAIL.COM">EMAIL: IBICON.INFO@GMAIL.COM</a></li>
                </ul>
            </div>
            <div class="col-xs-4">
                <h4>Connect With Us</h4>
                <a href="#"><i class="fab fa-facebook" aria-hidden="true"></i></a>
                <a href="#"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
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