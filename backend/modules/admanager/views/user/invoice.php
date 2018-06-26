<?php

use common\models\admin\SettingsMessageSearch;
use common\utilities\UtilityDateTime;
use common\utilities\UtilityHtmlFormat;
?>
<div class="row form-to">
    <div class="col-sm-6 left">
        <div class="form">
            <p class="title-from-to"><?= SettingsMessageSearch::t('invoice', 'invoice_from_title', 'From') ?></p>
            <div class="cont-from-to">
                <p class="name text-red"><strong><?= SettingsMessageSearch::t('invoice', 'invoice_metrixa_title', 'Metrixa') ?> </strong></p>
                <p class="contact-popup">
                    <?= SettingsMessageSearch::t('invoice', 'invoice_metrixa_address') ?>

                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6 right">
        <div class="to">
            <p class="title-from-to"><?= SettingsMessageSearch::t('invoice', 'invoice_to_title', 'To') ?></p>
            <div class="cont-from-to">
                <p class="name text-red"><strong><?= $model->detail_firstname ?></strong></p>
                <p class="contact-popup">
                    <?= SettingsMessageSearch::t('invoice', 'invoice_clientid_title', 'Client ID:') ?> <?= $model->getClientId() ?>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="tax-invoice">
    <p class="title"><?= SettingsMessageSearch::t('invoice', 'invoice_vat_title', 'VAT invoice') ?> - <span><?= SettingsMessageSearch::t('invoice', 'invoice_vat_description', 'Full paid for services rendered') ?> </span></p>
    <div class="cont top">
        <div class="row">
            <div class="col-xs-3 col-sm-3">
                <div class="item">
                    <p><span class="title-item"><?= SettingsMessageSearch::t('invoice', 'invoice_amount_title', 'Amount (AUD$)') ?> </span><br><span class="detail"><?= number_format($model->order_amount) ?></span></p>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3">
                <div class="item">
                    <p><span class="title-item"><?= SettingsMessageSearch::t('invoice', 'invoice_date_title', 'Invoice date') ?></span><br><span class="detail"><?= UtilityDateTime::formatDate($model->created_time, 'd-M-Y H:i A') ?>   </span></p>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3">
                <div class="item">
                    <p><span class="title-item"><?= SettingsMessageSearch::t('invoice', 'invoice_payment_method', 'Payment Method') ?> </span><br><span class="detail">Credit Card</span></p>
                </div>
            </div>
            <div class="col-xs-3 col-sm-3">
                <div class="item">
                    <p><span class="title-item"><?= SettingsMessageSearch::t('invoice', 'invoice_bank_reference', 'Bank Reference') ?> </span><br><span class="detail"><?= $model->transaction_id ?></span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-billings">
    <table class="table">
        <tbody>
            <tr>
                <th class="td-1 bg-gray"><?= SettingsMessageSearch::t('invoice', 'invoice_item_title', 'Item') ?> </th>
                <th class="td-2 bg-gray"><?= SettingsMessageSearch::t('invoice', 'invoice_price_title', 'Price') ?></th>
                <th class="td-3 bg-gray">&nbsp;</th>
            </tr>
            <tr>
                <td class="td-title"><?= $plan->name ?></td>
                <td class="td-price"><?= SettingsMessageSearch::t('invoice', 'invoice_excluding_gst_title', 'Excluding GST') ?></td>
                <td class="td-detail-price text-right"><?= UtilityHtmlFormat::numberFormatDisplayed($model->order_amount) ?></td>
            </tr>
            <tr>
                <td class="td-title">&nbsp; </td>
                <td class="td-price"><?= SettingsMessageSearch::t('invoice', 'invoice_gst_title', 'GST') ?></td>
                <td class="td-detail-price text-right"><?= UtilityHtmlFormat::numberFormatDisplayed($model->gst_amount) ?></td>
            </tr>
            <tr>
                <td class="td-title">&nbsp;</td>
                <td class="td-price"><?= SettingsMessageSearch::t('invoice', 'invoice_processing_fee_title', 'Card/processing fee') ?></td>
                <td class="td-detail-price text-right"><?= UtilityHtmlFormat::numberFormatDisplayed($model->order_fee) ?></td>
            </tr>
            <tr>
                <td class="td-title">&nbsp;</td>
                <td class="td-price total"><strong class="text-red"><?= SettingsMessageSearch::t('invoice', 'invoice_order_total_title', 'Total') ?></strong></td>
                <td class="td-detail-price total text-right"><strong class=" text-red"><?= UtilityHtmlFormat::numberFormatDisplayed($model->order_total) ?></strong></td>
            </tr>
        </tbody>
    </table>
</div>