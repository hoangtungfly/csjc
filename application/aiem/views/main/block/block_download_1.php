<?php

use common\models\admin\SettingsMessageSearch;

?>
<div class="block block-download">
    <div class="block-download-container">
        <h1><?= $item['title'] ?></h1>
        <div class="div-qr-code">
            <div class="div-qr-code-left" style="padding-top: 20px;"><?= SettingsMessageSearch::t('download','title_under','Welcome to download and use AIEM OTT') ?></div>
            <div class="div-qr-code-right">
                <img src="<?= LINK_PUBLIC .'images/qr.png' ?>" />
            </div>
        </div>
        <div class="div-qr-code">
            <div class="div-qr-code-left">
                <a href="<?= isset($item['link_app_store']) ? $item['link_app_store'] : '' ?>"><img src="<?= LINK_PUBLIC .'images/app-store.png' ?>" /></a>
                <a href="<?= isset($item['link_google_play']) ? $item['link_google_play'] : '' ?>"><img class="div-qr-code-img-right" src="<?= LINK_PUBLIC .'images/google-play.png' ?>" /></a>
            </div>
            <div class="div-qr-code-right">
                <span><?= SettingsMessageSearch::t('download','title_under_2','Or scan QR code to directory download') ?></span>
            </div>
        </div>
        <div class="div-download-content">
            <h2><?= SettingsMessageSearch::t('download','description_middle','AIEM supports: iOS8 or above / Android 4 or above') ?></h2>
            <p>
                <?= $item['description'] ?>
            </p>
        </div>
        <div class="div-download-2-content">
            <h3><?= SettingsMessageSearch::t('download','content_title','PC (Beta version for preview)') ?></h3>
            <div class="div-download-2-content">
                <div class="div-download-2-content-left">
                    <a href="<?= isset($item['link_window']) ? $item['link_window'] : '' ?>"><img src="<?= LINK_PUBLIC .'images/windows.png' ?>" /></a>
                </div>
                <div class="div-download-2-content-right">
                    <a href="<?= isset($item['link_mac']) ? $item['link_mac'] : '' ?>"><img src="<?= LINK_PUBLIC .'images/mac.png' ?>" /></a>
                </div>
            </div>
        </div>
        <div class="div-download-footer-content">
            <?= $item['content'] ?>
        </div>
    </div>
</div>