<?php

use common\models\category\CategoriesSearch;
$model = new CategoriesSearch();
?>
<div class="block block-download">
    <div class="block-download-container">
        <h1><?= $item['title'] ?></h1>
        <div class="div-qr-code">
            <div class="div-qr-code-left" style="padding-top: 20px;"><?= $item['text1'] ?></div>
            <div class="div-qr-code-right">
                <img src="<?= LINK_PUBLIC .'images/qr.png' ?>" />
            </div>
        </div>
        <div class="div-qr-code">
            <div class="div-qr-code-left">
                <a href="<?= isset($item['link_app_store']) ? $model->getfile($item['link_app_store']) : '' ?>"><img src="<?= LINK_PUBLIC .'images/app-store.png' ?>" /></a>
                <a href="<?= isset($item['link_google_play']) ? $model->getfile($item['link_google_play']) : '' ?>"><img class="div-qr-code-img-right" src="<?= LINK_PUBLIC .'images/google-play.png' ?>" /></a>
            </div>
            <div class="div-qr-code-right">
                <span><?= $item['text2'] ?></span>
            </div>
        </div>
        <div class="div-download-content">
            <h2><?= $item['text3'] ?></h2>
            <p>
                <?= $item['description'] ?>
            </p>
        </div>
    </div>
</div>