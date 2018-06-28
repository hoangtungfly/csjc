<?php

use common\core\form\GlobalActiveForm;
use common\models\admin\SettingsMessageSearch;
use common\models\contact\ContactSearch;
use common\models\settings\SystemSettingSearch;
use yii\helpers\Html;

$email = SystemSettingSearch::getValue('email');
?>
<div class="block block-contact">
    <div class="container">
        <h1><?= $item['title'] ?></h1>
        <div class="contact-description"><?= $item['description'] ?></div>
        <div class="clear"></div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <h2 class="title-3"><span>Trụ Sở Chính</span></h2>
                <p>Phòng 411 Toà nhà TOYOTA Mỹ Đình, Số 15 Phạm Hùng, Quận Nam Từ Liêm, Thành phố Hà Nội, Việt Nam.</p>
                <p><span>Tel: +84-24 3 795 7717</span></p>
                <?php if($email):?>
                <p>Email: <?=$email?></p>
                <?php endif;?>
                <p>Hotline: (+84) 983.384.888</p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
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

                </div>
                <div class="form-contact-right">
                    <h2><?= SettingsMessageSearch::t('contact', 'vui_long_mo_ta_cau_hoi', 'Vui lòng mô tả câu hỏi của bạn') ?><span class="red">*</span></h2>
                    <?= $form->field($model, 'content')->textarea(['style' => 'height:410px']) ?>
                </div>

                <div class="form-contact-submit">
                    <button class="btn btn-primary bnone"><?= SettingsMessageSearch::t('contact', 'button_submit', 'Gửi') ?></button>
                    <button class="btn btn-default bnone"><?= SettingsMessageSearch::t('contact', 'button_cancel', 'Làm lại') ?></button>
                </div>
                <?php GlobalActiveForm::end(); ?>
                <div class="clear"></div>
            </div>
        </div>
        
    </div>
</div>