<?php

/**
 * define status Ex: actived, deleted...
 * 
 * @author HuuDoan
 */

namespace common\core\enums\payments;

use common\core\enums\base\GlobalEnumBase;
use Yii;

class PaymentGatewayEnum extends GlobalEnumBase {

    const PAYMENT_STATUS_ACTIVE = 1;
    const PAYMENT_STATUS_DEACTIVE = 0;
    const PAYMENT_TEST_ACTIVE = 1;
    const PAYMENT_TEST_DEACTIVE = 0;
    const IS_PRIMARY = 1;
    const IS_NOT_PRIMARY = 0;
    const KEY_ASE_ENCRYPT = 'KEY_FOR_ORG_ACCOUNT_GATEWAY';

    // config gateway
    public static function gatewayLabel() {
        return array(
            DEFAULT_PAYFLOWPRO_GATEWAY_KEY => Yii::t('payment', DEFAULT_PAYFLOWPRO_GATEWAY_KEY),
            DEFAULT_SECURPAY_GATEWAY_KEY => Yii::t('payment', DEFAULT_SECURPAY_GATEWAY_KEY),
            DEFAULT_BRAINTREE_GATEWAY_KEY => Yii::t('payment', DEFAULT_BRAINTREE_GATEWAY_KEY),
        );
    }

}
