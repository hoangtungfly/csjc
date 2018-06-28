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
                    <li><a href="#">+1 (416) 900-1111 (Toronto Office)</a></li>
                    <li><a href="#">info@nanopay.net (General)</a></li>
                    <li><a href="#">pr@nanopay.net (Press &amp; Media)</a></li>
                    <li><a href="#">support@nanopay.net (Support)</a></li>
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