<?php

use application\webadmanager\models\AccountSearch;
use application\webadmanager\models\CustomersSearch;
use application\webadmanager\models\UserAdmanager;
use common\models\admin\SettingsMessageSearch;
use common\models\lib\LibCountriesSearch;
use common\models\payments\PaymentOrders;
use common\models\plan\PlanSearch;
use common\utilities\UtilityDateTime;
use common\utilities\UtilityHtmlFormat;
/* @var $model CustomersSearch */
/* @var $plan PlanSearch */
$list_country = LibCountriesSearch::getAllDropDown();
?>
<div class="breadcrumbs" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="icon-home home-icon"></i>
            <a href="/admin">Home</a>
        </li>
        <li><a class="breadcrumbsa" href="<?= $this->createUrl('/admanager/customer/index', ['menu_admin_id' => 131]) ?>">Customer Management</a></li>
    </ul>
    <div class="fr">
        <a id="delete_cache" href="<?= $this->createUrl('/settings/access/deletecache') ?>" class="btn btn-warning bnone">Delete cache</a>
    </div>
</div>
<div class="page-header">
    <h1 class="fl" style="width:100%;"><span>View customer id = <?= $model->customerid ?></span></h1>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="user_main">
            <h2 class="user_title">1. Customer information</h2>
            <table class="user_table">
                <tr>
                    <td>Email</td>
                    <td><?= $model->email ?></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><?= $model->fullname ?></td>
                </tr>
                <tr>
                    <td>Plan</td>
                    <td><?= $plan->name ?></td>
                </tr>
                <tr>
                    <td>Start Trial Date</td>
                    <td><?= $model->starttrialdate ?></td>
                </tr>
                <tr>
                    <td>Expired Trial Date</td>
                    <td><?= $model->expireddate ?></td>
                </tr>
                <tr>
                    <td>Number User</td>
                    <td><?= UtilityHtmlFormat::numberFloat(count($list),0) ?></td>
                </tr>
                <tr>
                    <td>Number Facebook Account</td>
                    <?php $count_facebook = count($listFacebook); ?>
                    <td><?= $count_facebook ?  UtilityHtmlFormat::numberFloat($count_facebook,0) : 0 ?></td>
                </tr>
                <tr>
                    <td>Registered Date</td>
                    <td><?= $model->createddate ?></td>
                </tr>
            </table>
        </div>
        <div class="user_main">
            <h2 class="user_title">2. User Details</h2>
            <?php foreach($list as $key => $item) { /* @var $item UserAdmanager */ ?>
            <div class="user_item">
                <h2>2.<?=$key + 1?> User <?=$key + 1?> <?=$item->app_type ==  APP_TYPE_CUSTOMERS ? '(Admin)' : '' ?> <a href="javascript:void(0);" class="updateuser_information" data-href="<?=$this->createUrl('user/updateuser',['id' => $item->userid])?>">Update Information</a></h2>
                <table class="user_table">
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'email_title', 'Email') ?></td>
                        <td><?= $item->email ?></td>
                    </tr>
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'first_name_title', 'First Name') ?></td>
                        <td><?= $item->firstname ?></td>
                    </tr>
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'last_name_title', 'Last Name') ?></td>
                        <td><?= $item->name ?></td>
                    </tr>
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'businessname_title', 'Business Name') ?></td>
                        <td><?= $item->businessname ?></td>
                    </tr>
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'phone_title', 'Phone') ?></td>
                        <td><?= $item->contactphone ?></td>
                    </tr>
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'address_title', 'Address') ?></td>
                        <td><?= $item->address ?></td>
                    </tr>
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'country_title', 'Country') ?></td>
                        <td><?= isset($list_country[$item->countrycode]) ? $list_country[$item->countrycode] : '' ?></td>
                    </tr>
                </table>
            </div>
            <?php } ?>
        </div>
        <div class="user_main">
            <h2 class="user_title">3. Facebook Ad Account</h2>
            <?php foreach($listFacebook as $key => $itemfacebook) { /* @var $itemfacebook AccountSearch */ ?>
            <div class="user_item">
                <h2>3.<?=$key + 1?> Facebook Ad Account <?=$key + 1?>
                    <!--<a href="javascript:void(0);" class="updateuser_information" data-href="<?=$this->createUrl('user/updatefacebook',['id' => $itemfacebook->id])?>">Update Information</a>-->
                </h2>
                <table class="user_table">
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'facebook_account_id_title', 'Account ID') ?></td>
                        <td><?= $itemfacebook->accountid ?></td>
                    </tr>
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'facebook_email_title', 'Email') ?></td>
                        <td><?= $itemfacebook->email ?></td>
                    </tr>
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'facebook_name_title', 'Name') ?></td>
                        <td><?= $itemfacebook->fullname ?></td>
                    </tr>
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'facebook_modified_date_title', 'Modified Date') ?></td>
                        <td><?= $itemfacebook->modifieddate ?></td>
                    </tr>
                    <tr>
                        <td><?= SettingsMessageSearch::t('profile', 'facebook_modified_by_title', 'Modified By') ?></td>
                        <td><?= isset($list_user[$itemfacebook->modifiedby]) ? $list_user[$itemfacebook->modifiedby] : '' ?></td>
                    </tr>
                </table>
            </div>
            <?php } ?>
        </div>
        <div class="user_main">
            <h2 class="user_title">4. Payment detail <a id="invoice_export" href="<?=$this->createUrl('user/export',['customerid' => $model->customerid])?>">Export</a></h2>
            <?php foreach($listOrder as $key => $itemorder) { /* @var $itemorder PaymentOrders */ ?>
            <div class="user_item">
                <h2>4.<?=$key + 1?> Payment detail <?=$key + 1?>
                </h2>
                <table class="user_table">
                    <tr>
                        <td>Invoice No.</td>
                        <td ><a href="javascript:void(0);" class="view_invoice invoice_id fl" style="float:left;" data-href="<?=$this->createUrl('user/invoice',['id' => $itemorder->order_id])?>"><?= $itemorder->getInvoiceNo() ?></a></td>
                    </tr>
                    <tr>
                        <td>Bank Reference</td>
                        <td ><?= $itemorder->transaction_id ?></td>
                    </tr>
                    <tr>
                        <td>Payment Date</td>
                        <td><?= UtilityDateTime::formatDate($itemorder->created_time, 'd-M-Y H:i A'); ?></td>
                    </tr>
                    <tr>
                        <td>Plan</td>
                        <td><?= $itemorder->getPlanLabel() ?></td>
                    </tr>
                    <tr>
                        <td>GST Fee</td>
                        <td><?= UtilityHtmlFormat::numberFloatPrice($itemorder->gst_amount) ?></td>
                    </tr>
                    <tr>
                        <td>Processing Fee</td>
                        <td><?= UtilityHtmlFormat::numberFloatPrice($itemorder->order_fee) ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td><?= $itemorder->getTotalMessage() ?></td>
                    </tr>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>
</div>