<?php

namespace common\models\payments;

use common\models\payments\baseGateway;

/**
 * PayFlowProModel class.
 */
class braintreeGateway extends baseGateway {

    public $user;
    public $public_key;
    public $private_key;
    protected $gatewayType = DEFAULT_BRAINTREE_GATEWAY_KEY;
    protected $fieldEncrypt = array(
        'user',
        'public_key',
        'private_key',
    );

    public function rules() {
        return array(
            [['user', 'public_key', 'private_key'], 'required'],
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'user' => 'Merchant ID',
            'public_key' => 'Public key',
            'private_key' => 'Private key',
        );
    }

}
