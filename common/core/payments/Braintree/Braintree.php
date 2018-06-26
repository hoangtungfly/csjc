<?php
/**
 * Braintree PHP Library
 *
 * Braintree base class and initialization
 * Provides methods to child classes. This class cannot be instantiated.
 *
 *  PHP version 5
 *
 * @copyright  2014 Braintree, a division of PayPal, Inc.
 */


set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__)));


abstract class Braintree
{
    /**
     * @ignore
     * don't permit an explicit call of the constructor!
     * (like $t = new Braintree_Transaction())
     */
    protected function __construct()
    {
    }
    /**
     * @ignore
     *  don't permit cloning the instances (like $x = clone $v)
     */
    protected function __clone()
    {
    }

    /**
     * returns private/nonexistent instance properties
     * @ignore
     * @access public
     * @param string $name property name
     * @return mixed contents of instance properties
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }
        else {
            trigger_error('Undefined property on ' . get_class($this) . ': ' . $name, E_USER_NOTICE);
            return null;
        }
    }

    /**
     * used by isset() and empty()
     * @access public
     * @param string $name property name
     * @return boolean
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->_attributes);
    }

    public function _set($key, $value)
    {
        $this->_attributes[$key] = $value;
    }
}

require_once('braintree/Base.php');
require_once('braintree/Modification.php');
require_once('braintree/Instance.php');

require_once('braintree/OAuthCredentials.php');
require_once('braintree/Address.php');
require_once('braintree/AddressGateway.php');
require_once('braintree/AddOn.php');
require_once('braintree/AddOnGateway.php');
require_once('braintree/AndroidPayCard.php');
require_once('braintree/ApplePayCard.php');
require_once('braintree/ClientToken.php');
require_once('braintree/ClientTokenGateway.php');
require_once('braintree/CoinbaseAccount.php');
require_once('braintree/Collection.php');
require_once('braintree/Configuration.php');
require_once('braintree/CredentialsParser.php');
require_once('braintree/CreditCard.php');
require_once('braintree/CreditCardGateway.php');
require_once('braintree/Customer.php');
require_once('braintree/CustomerGateway.php');
require_once('braintree/CustomerSearch.php');
require_once('braintree/DisbursementDetails.php');
require_once('braintree/Dispute.php');
require_once('braintree/Dispute/TransactionDetails.php');
require_once('braintree/Descriptor.php');
require_once('braintree/Digest.php');
require_once('braintree/Discount.php');
require_once('braintree/DiscountGateway.php');
require_once('braintree/IsNode.php');
require_once('braintree/EuropeBankAccount.php');
require_once('braintree/EqualityNode.php');
require_once('braintree/Exception.php');
require_once('braintree/Gateway.php');
require_once('braintree/Http.php');
require_once('braintree/KeyValueNode.php');
require_once('braintree/Merchant.php');
require_once('braintree/MerchantGateway.php');
require_once('braintree/MerchantAccount.php');
require_once('braintree/MerchantAccountGateway.php');
require_once('braintree/MerchantAccount/BusinessDetails.php');
require_once('braintree/MerchantAccount/FundingDetails.php');
require_once('braintree/MerchantAccount/IndividualDetails.php');
require_once('braintree/MerchantAccount/AddressDetails.php');
require_once('braintree/MultipleValueNode.php');
require_once('braintree/MultipleValueOrTextNode.php');
require_once('braintree/OAuthGateway.php');
require_once('braintree/PartialMatchNode.php');
require_once('braintree/Plan.php');
require_once('braintree/PlanGateway.php');
require_once('braintree/RangeNode.php');
require_once('braintree/ResourceCollection.php');
require_once('braintree/RiskData.php');
require_once('braintree/ThreeDSecureInfo.php');
require_once('braintree/SettlementBatchSummary.php');
require_once('braintree/SettlementBatchSummaryGateway.php');
require_once('braintree/SignatureService.php');
require_once('braintree/Subscription.php');
require_once('braintree/SubscriptionGateway.php');
require_once('braintree/SubscriptionSearch.php');
require_once('braintree/Subscription/StatusDetails.php');
require_once('braintree/TextNode.php');
require_once('braintree/Transaction.php');
require_once('braintree/TransactionGateway.php');
require_once('braintree/Disbursement.php');
require_once('braintree/TransactionSearch.php');
require_once('braintree/TransparentRedirect.php');
require_once('braintree/TransparentRedirectGateway.php');
require_once('braintree/Util.php');
require_once('braintree/Version.php');
require_once('braintree/Xml.php');
require_once('braintree/Error/Codes.php');
require_once('braintree/Error/ErrorCollection.php');
require_once('braintree/Error/Validation.php');
require_once('braintree/Error/ValidationErrorCollection.php');
require_once('braintree/Exception/Authentication.php');
require_once('braintree/Exception/Authorization.php');
require_once('braintree/Exception/Configuration.php');
require_once('braintree/Exception/DownForMaintenance.php');
require_once('braintree/Exception/ForgedQueryString.php');
require_once('braintree/Exception/InvalidChallenge.php');
require_once('braintree/Exception/InvalidSignature.php');
require_once('braintree/Exception/NotFound.php');
require_once('braintree/Exception/ServerError.php');
require_once('braintree/Exception/SSLCertificate.php');
require_once('braintree/Exception/SSLCaFileNotFound.php');
require_once('braintree/Exception/Unexpected.php');
require_once('braintree/Exception/UpgradeRequired.php');
require_once('braintree/Exception/ValidationsFailed.php');
require_once('braintree/Result/CreditCardVerification.php');
require_once('braintree/Result/Error.php');
require_once('braintree/Result/Successful.php');
require_once('braintree/Test/CreditCardNumbers.php');
require_once('braintree/Test/MerchantAccount.php');
require_once('braintree/Test/TransactionAmounts.php');
require_once('braintree/Test/VenmoSdk.php');
require_once('braintree/Test/Nonces.php');
require_once('braintree/Transaction/AddressDetails.php');
require_once('braintree/Transaction/AndroidPayCardDetails.php');
require_once('braintree/Transaction/ApplePayCardDetails.php');
require_once('braintree/Transaction/CoinbaseDetails.php');
require_once('braintree/Transaction/EuropeBankAccountDetails.php');
require_once('braintree/Transaction/CreditCardDetails.php');
require_once('braintree/Transaction/PayPalDetails.php');
require_once('braintree/Transaction/CustomerDetails.php');
require_once('braintree/Transaction/StatusDetails.php');
require_once('braintree/Transaction/SubscriptionDetails.php');
require_once('braintree/WebhookNotification.php');
require_once('braintree/WebhookTesting.php');
require_once('braintree/Xml/Generator.php');
require_once('braintree/Xml/Parser.php');
require_once('braintree/CreditCardVerification.php');
require_once('braintree/CreditCardVerificationGateway.php');
require_once('braintree/CreditCardVerificationSearch.php');
require_once('braintree/PartnerMerchant.php');
require_once('braintree/PayPalAccount.php');
require_once('braintree/PayPalAccountGateway.php');
require_once('braintree/PaymentMethod.php');
require_once('braintree/PaymentMethodGateway.php');
require_once('braintree/PaymentMethodNonce.php');
require_once('braintree/PaymentMethodNonceGateway.php');
require_once('braintree/PaymentInstrumentType.php');
require_once('braintree/UnknownPaymentMethod.php');
require_once('braintree/Exception/TestOperationPerformedInProduction.php');
require_once('braintree/Test/Transaction.php');

if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    throw new Braintree_Exception('PHP version >= 5.4.0 required');
}


function requireDependencies() {
    $requiredExtensions = array('xmlwriter', 'openssl', 'dom', 'hash', 'curl');
    foreach ($requiredExtensions AS $ext) {
        if (!extension_loaded($ext)) {
            throw new Braintree_Exception('The Braintree library requires the ' . $ext . ' extension.');
        }
    }
}

requireDependencies();
