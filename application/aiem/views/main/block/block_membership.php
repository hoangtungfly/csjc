<?php

use common\models\admin\SettingsMessageSearch;
$statusLabelBlue = [
    0       => '',
    1       => '<img src="' . LINK_PUBLIC . 'images/hoivienxanh.png' . '" />',
    2       => SettingsMessageSearch::t('membership','gioi_han','Giới hạn'),
];
$statusLabelSilver = [
    0       => '',
    1       => '<img src="' . LINK_PUBLIC . 'images/hoivienbac.png' . '" />',
    2       => SettingsMessageSearch::t('membership','gioi_han','Giới hạn'),
];
$statusLabelGold = [
    0       => '',
    1       => '<img src="' . LINK_PUBLIC . 'images/hoivienvang.png' . '" />',
    2       => SettingsMessageSearch::t('membership','gioi_han','Giới hạn'),
];
?>
<div class="block block-membership">
    <div class="container">
        <h1 class="block-h1"><?= $item['title'] ?></h1>
        <table class="block-membership-table" border="#ddd">
            <thead>
                <tr>
                    <th><?= SettingsMessageSearch::t('membership','tinh_nang','Tính năng') ?></th>
                    <th class="text-align-center"><?= SettingsMessageSearch::t('membership','hoi_vien_xanh','Hội viên xanh') ?></th>
                    <th class="text-align-center"><?= SettingsMessageSearch::t('membership','hoi_vien_bac','Hội viên bạc') ?></th>
                    <th class="text-align-center"><?= SettingsMessageSearch::t('membership','hoi_vien_vang','Hội viên vàng') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($item['membership']) && $item['membership'] && count($item['membership'])) { ?>
                <?php foreach($item['membership'] as $key => $child) { $child = (array)$child; ?>
                <tr <?= $key % 2 == 1 ? 'class="two"' : '' ?>>
                    <td><?= $child['feature'] ?></td>
                    <td class="text-align-center"><?= isset($statusLabelBlue[$child['blue']]) ? $statusLabelBlue[$child['blue']] : '' ?></td>
                    <td class="text-align-center"><?= isset($statusLabelSilver[$child['silver']]) ? $statusLabelSilver[$child['silver']] : '' ?></td>
                    <td class="text-align-center"><?= isset($statusLabelGold[$child['gold']]) ? $statusLabelGold[$child['gold']] : '' ?></td>
                </tr>
                <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>