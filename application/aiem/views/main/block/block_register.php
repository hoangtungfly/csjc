<?php

use common\core\enums\LanguageEnum;
use common\core\form\GlobalActiveForm;
use common\models\admin\SettingsMessageSearch;
use common\models\company\CompanyCategorySearch;
use common\models\company\CompanyPbxSearch;
use common\models\company\CompanySearch;
use common\models\company\CompanySizeSearch;
use yii\helpers\Html;
?>
<div class="block block-contact">


    <div class="container">
        <h1><?= $item['title'] ?></h1>
        <?php
        $model = new CompanySearch();
        $model->lang = app()->language;
        $form = GlobalActiveForm::begin([
                    'id' => 'company-form',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false,
                    'validateOnChange' => false,
                    'validateOnSubmit' => false,
                    'validateOnBlur' => false,
                    'action' => $this->createUrl('main/register'),
                    'fieldConfig' => [
                        'template' => "{label}{input}{error}",
                        'options' => [
                            'class' => 'input-box'
                        ]
                    ],
                    'options' => [
                        'class' => 'contact-form register-form plr0'
                    ]
        ]);
        echo Html::hiddenInput('lang', app()->language);
        ?>
        <div class="col-sm-7 pl0">
            <div class="widget-header">
                <i class="fa fa-list-alt"></i>
                <h2><?= SettingsMessageSearch::t('company', 'thong_tin_doanh_nghiep', 'Thông tin doanh nghiệp') ?></h2>
            </div>
            <div class="widget-content">
                <?= $form->field($model, 'name')->textInput(['placeholder' => SettingsMessageSearch::t('company', 'name_placeholder', 'Nhập tên của bạn')])->label(SettingsMessageSearch::t('company', 'name', 'Tên của bạn')) ?>
                <div class="form-group">
                    <div class="col-xs-6" style="padding-left: 0px">
                        <?= $form->field($model, 'company_category_id')->dropDownList(['' => SettingsMessageSearch::t('company', 'company_category_placeholder', '-- Chọn loại công ty --')] + CompanyCategorySearch::getAllDropown(), ['class' => 'settings_chosen'])->label(SettingsMessageSearch::t('company', 'company_category', 'Loại công ty')) ?>
                    </div>
                    <div class="col-xs-6" style="padding-right: 0px">
                        <?= $form->field($model, 'company_size_id')->dropDownList(['' => SettingsMessageSearch::t('company', 'company_size_placeholder', '-- Chọn quy mô công ty --')] + CompanySizeSearch::getAllDropown(), ['class' => 'settings_chosen'])->label(SettingsMessageSearch::t('company', 'company_size', 'Quy mô công ty')) ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-6" style="padding-left: 0px">
                        <?= $form->field($model, 'company_pbx_id')->radioList(CompanyPbxSearch::getAllDropown(), ['class' => 'settings_checkboxsmall'])->label(SettingsMessageSearch::t('company', 'company_pbx', 'Loại hình PBX cài đặt trong công ty? (PBX)')) ?>
                    </div>
                    <div class="col-xs-6" style="padding-right: 0px">
                        <div class="form-horizontal">
                            <?= $form->field($model, 'lang')->dropDownList(LanguageEnum::languageLabel(), ['class' => 'settings_chosen'])->label(SettingsMessageSearch::t('company', 'lang', 'Ngôn ngữ')) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-5 pr0">
            <div class="widget-header">
                <i class="fa fa-list-alt"></i>
                <h2><?= SettingsMessageSearch::t('company', 'thong_tin_lien_he', 'Thông tin liên hệ') ?></h2>
            </div>
            <div class="widget-content">
                <?= $form->field($model, 'information_name')->textInput(['placeholder' => SettingsMessageSearch::t('company', 'information_name_placeholder', 'Nhập họ tên của bạn')])->label(SettingsMessageSearch::t('company', 'information_name', 'Họ tên')) ?>
                <?= $form->field($model, 'information_email')->textInput(['placeholder' => SettingsMessageSearch::t('company', 'email_placeholder', 'Nhập email của bạn')])->label(SettingsMessageSearch::t('company', 'email', 'Email')) ?>
                <?= $form->field($model, 'information_mobile')->textInput(['placeholder' => SettingsMessageSearch::t('company', 'mobile_placeholder', 'Nhập số điện thoại di động của bạn')])->label(SettingsMessageSearch::t('company', 'mobile', 'Mobile (sử dụng để đăng nhập tài khoản AIEM) ')) ?>
                <?= $form->field($model, 'information_phone')->textInput(['placeholder' => SettingsMessageSearch::t('company', 'phone_placeholder', 'Nhập số điện thoại cố định của bạn')])->label(SettingsMessageSearch::t('company', 'phone', 'Số điện thoại cố định')) ?>
            </div>
        </div>
        <div class="clear"></div>
        <div class="notice notice-margin">
            <ul>
                <li><?= SettingsMessageSearch::t('company', 'contact_note_one') ?></li>
                <li><?= SettingsMessageSearch::t('company', 'contact_note_two') ?></li>
            </ul>
        </div>
        <div class="clear"></div>
        <div class="div-agree">
            <?= $form->field($model, 'agree',[
                                'template' => "<div class=\"form-group\">{input}{label}{error}</div>",
                                'options' => [
                                    'class' => 'item-input'
                                ],
                                'labelOptions' => [
                                    'class' => 'description',
                                ]
                            ])->checkboxone(['class' => 'styled', 'label' => ''])->label(SettingsMessageSearch::t('company', 'contact_agree')) ?>  
        </div>
        <div class="div-submit">
            <button type="submit" id="btnSubmit" class="btn btn-primary btn-lg"><?= SettingsMessageSearch::t('company', 'contact_submit') ?></button>
        </div>
        <?php GlobalActiveForm::end(); ?>
    </div>
</div>