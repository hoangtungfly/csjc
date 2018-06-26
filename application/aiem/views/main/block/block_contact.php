<?php

use common\core\form\GlobalActiveForm;
use common\models\admin\SettingsMessageSearch;
use common\models\contact\ContactSearch;
use common\models\product\BrandSearch;
use common\models\product\ManufacturerSearch;
use common\models\product\MobileSearch;
use yii\helpers\Html;
$list_brand = BrandSearch::getAllDropown();
if(app()->language == 'vi' && isset($list_brand[7])) {
    $list_brand[7] = 'Khác';
}
?>
<div class="block block-contact">
    <div class="container">
        <h1><?= $item['title'] ?></h1>
        <div class="contact-description"><?= $item['description'] ?></div>
        <div class="clear"></div>
        <?php
        $model = new ContactSearch();
        $form = GlobalActiveForm::begin([

                    'id' => 'contact-form',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false,
                    'validateOnChange' => false,
                    'validateOnSubmit' => false,
                    'validateOnBlur' => false,
                    'action' => $this->createUrl('/contact/proccess'),
                    'fieldConfig' => [
                        'template' => "{input}{error}",
                        'options' => [
                            'class' => 'input-box'
                        ]
                    ],
                    'options' => [
                        'class' => 'contact-form'
                    ]
        ]);
        echo Html::hiddenInput('lang', app()->language);
        ?>
        <div class="form-contact-left">
            <h2><?= SettingsMessageSearch::t('contact', 'chi_tiet_lien_lac', 'Chi tiết liên lạc của bạn') ?><span class="red">*</span></h2>
            <?= $form->field($model, 'name')->textInput(['placeholder' => SettingsMessageSearch::t('contact', 'name', 'Tên của bạn')]) ?>
            <?= $form->field($model, 'phone')->textInput(['placeholder' => SettingsMessageSearch::t('contact', 'phone', 'Điện thoại của bạn')]) ?>
            <?= $form->field($model, 'email')->textInput(['placeholder' => SettingsMessageSearch::t('contact', 'email', 'Email')]) ?>
            <h2><?= SettingsMessageSearch::t('contact', 'thiet_bi_di_dong', 'Thiết bị di động của bạn') ?></h2>
            <?= $form->field($model, 'mobile_id')->dropDownList(['' => SettingsMessageSearch::t('contact', 'cac_loai_thiet_bi_di_dong', 'Các loại thiết bị di động')] + MobileSearch::getAllDropown(), ['class' => 'settings_chosen', 'style' => 'width:100%;']) ?>
            <?= $form->field($model, 'brand_id')->dropDownList(['' => SettingsMessageSearch::t('contact', 'nhan_hieu', 'Nhãn hiệu')] + $list_brand, ['class' => 'settings_chosen', 'style' => 'width:100%;']) ?>
            <?= $form->field($model, 'os_version')->textInput(['placeholder' => SettingsMessageSearch::t('contact', 'os_version', 'OS version')]) ?>
            <?= $form->field($model, 'manufacturer_id')->dropDownList(['' => SettingsMessageSearch::t('contact', 'nha_cung_cap_dich_vu', 'Nhà cung cấp dịch vụ')] + ManufacturerSearch::getAllDropown(), ['class' => 'settings_chosen', 'style' => 'width:100%;']) ?>

        </div>
        <div class="form-contact-right">
            <h2><?= SettingsMessageSearch::t('contact', 'vui_long_mo_ta_cau_hoi', 'Vui lòng mô tả câu hỏi của bạn') ?><span class="red">*</span></h2>
            <?= $form->field($model, 'content')->textarea(['style' => 'height:410px']) ?>
            <?= $form->field($model, 'captcha')->capcha(['class' => 'input-text required-entry', 'placeholder' => SettingsMessageSearch::t('contact','nhap_ma_bao_mat','Nhập mã bảo mật')]) ?>
        </div>

        <div class="form-contact-submit">
            <button class="btn btn-primary bnone"><?= SettingsMessageSearch::t('contact', 'button_submit', 'Gửi') ?></button>
            <button class="btn btn-default bnone"><?= SettingsMessageSearch::t('contact', 'button_cancel', 'Làm lại') ?></button>
        </div>
        <?php GlobalActiveForm::end(); ?>
        <div class="clear"></div>
    </div>
</div>