<?php

/**
 * define status Ex: actived; deleted...
 * 
 * @author Phong Pham Hong
 */

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;

class EmailsettingEnum extends GlobalEnumBase {

    const ERROR_REPORT_SENDMAIL = 'error_report_sendmail';
    const ORDER_SENDMAIL_TEMPLATE = 'order_sendmail_template';
    const CONTACT_SENDMAIL_TEMPLATE = 'contact_sendmail_template';
    const COURSE_SENDMAIL_TEMPLATE = 'course_sendmail_template';
    const COMPANY_SENDMAIL_TEMPLATE = 'company_sendmail_templete';
    
    const SUBSCRIBLE = 'subscrible';
    
    const FORGOT_PASSWORD = 'forgot_password';
    const REGISTER = 'register';
    const RECURRING_BILLING_FALSE = 'recurring_billing_false';
    const ORDER_CONFIRMATION = 'order_confirmation';
    
    const INVITE_USER = 'invite_user';
    const TRIAL_ENDING_SOON = 'trial_ending_soon';
    
    const TRIAL_CANCEL_AFTER_FREE = 'trial_cancel_after_free';
    const USER_LEAVING_METRIXA_ADMANAGER = 'user_leaving_metrixa_admanager';
}
