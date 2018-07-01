<?php

use common\core\form\GlobalActiveForm;
use common\models\admin\SettingsMessageSearch;
use common\models\contact\ContactSearch;
use common\models\settings\SystemSettingSearch;
use yii\helpers\Html;

$email = SystemSettingSearch::getValue('email');
$config = $this->context->array_config();
?>
<div class="container company">
    <h1 class="text-center"><?= $item['title'] ?></h1>
    <h2 class="text-center"><?= $item['description'] ?></h2>
    <hr>
</div>

<div class="container contact">
    <div class="row_1">
        <div class="col-xs-6">
            <h4>TRỤ SỞ CHÍNH</h4>
            <div class="content">
                <p>Phòng 411 Toà nhà TOYOTA Mỹ Đình, Số 15 Phạm Hùng, Quận Nam Từ Liêm, Thành phố Hà Nội, Việt Nam.</p>
                <p>Tel: +84-24 3 795 7717</p>
                <?php if(isset($config['email']) && $config['email']):?>
                <p>Email: <?=$config['email']?></p>
                <?php endif;?>
                <p>Hotline: (+84) 983.384.888</p>
            </div>
            <iframe height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=hanoi&key=AIzaSyBD1yh4p30kaKpA2idu_e_rcR1GZkUYXCA" allowfullscreen></iframe>
        </div>
        
        <div class="col-xs-6">
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
//                        'options' => [
//                            'class' => 'form-control'
//                        ]
                    ],
                    'options' => [
                        'class' => 'contact-form'
                    ]
                ]);
                echo Html::hiddenInput('lang', app()->language);
                ?>
                <h4>LIÊN HỆ</h4>
                <?= $form->field($model, 'name')->textInput(['placeholder' => SettingsMessageSearch::t('contact', 'name', 'Tên của bạn')]) ?>
                <?= $form->field($model, 'email')->textInput(['placeholder' => SettingsMessageSearch::t('contact', 'email', 'Email')]) ?>
                <?= $form->field($model, 'phone')->textInput(['placeholder' => SettingsMessageSearch::t('contact', 'phone', 'Điện thoại của bạn')]) ?>
                <?= $form->field($model, 'title')->textInput(['placeholder' => SettingsMessageSearch::t('contact', 'title', 'Tiêu đề')]) ?>
                <?= $form->field($model, 'content')->textarea(['placeholder' => SettingsMessageSearch::t('contact', 'content', 'Nội dung')]) ?>
                <button type="submit" class=""><?= SettingsMessageSearch::t('contact', 'button_submit', 'Gửi liên hệ') ?></button>
             <?php GlobalActiveForm::end(); ?>
        </div>
    </div>
</div>