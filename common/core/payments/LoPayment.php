<?php

namespace common\core\payments;

use Braintree_Configuration;
use Braintree_Customer;
use Braintree_Subscription;
use Braintree_Transaction;
use common\core\enums\StatusEnum;
use common\core\model\GlobalModel;
use common\models\admin\SettingsMessageSearch;
use common\models\payments\LogMembership;
use common\models\payments\PaymentGateway;
use common\models\payments\PaymentOrders;
use common\models\payments\UsrCards;
use common\models\user\UserModel;
use common\utilities\UtilityDateTime;
use DateTime;
use Exception;
use Yii;

/**
 * Payment System for 457
 * 
 * @author HuuDoan
 * @date 18/08/2015
 * @version 1.0
 */
class LoPayment extends GlobalModel {

    const ERROR_FROM_ACCOUNT = 1;
    const ERROR_FROM_CARD = 2;

    public $payment_model;
    public $payment_type = DEFAULT_BRAINTREE_GATEWAY_KEY;
    public $test_mode = true;

    /**
     * Basic payment properties
     */
    public $vendor = ''; // vendor
    public $user = '';   //username
    public $partner = ''; // partnet
    public $password = ''; // password
    public $currencies_allowed = array(CURRENCY_DISPLAYED); // list currency is allowed
    public $public_key = '';
    public $private_key = '';
    protected $curl = null; // Curl object
    public $message = ''; // message 
    public $transactionID = null; // transaction id, when payment is successed
    public $orderId;
    public $firstname = ''; // first name 
    public $lastname = '';  // last name
    public $cardnumber = '';    // card number
    public $cardnumber1 = '';    // card number 
    public $cardnumber2 = '';    // card number 
    public $cardnumber3 = '';    // card number 
    public $cardnumber4 = '';    // card number 
    public $cardtype = '';   // card type: visa, master card, ...
    public $cardholdername = '';
    public $expmonth = '';  // expiration month
    public $expyear = '';   // expiration year
    public $expirationdate = '';  // expiration date
    public $cvv = '';   // cvv
    public $amount = 0; // amout of money
    public $currency = CURRENCY_DISPLAYED; // currency is selected
    public $errormessage = ''; // error message
    public $success = false;    // status of payment
    public $city = '';      // city
    public $country = '';       // country
    public $state = '';     // state
    public $postcode = '';  // postcode
    public $address;
    public $address1 = '';  //address 1
    public $address2 = '';  //address 2
    public $status_code;
    public $gst_amount = 0;
    public $processing_fee = 0;
    public $email = '';
    public $phone = '';
    public $phone_code = '';
    public $customerId = '';
    public $paymentMethodToken = '';
    public $countryOfIssuance = '';
    public $is_australia = 0;
    public $subscriptionId = '';
    public $abn = '';
    public $sponsorship = '';
    public $agreewithterm = '';

    /**
     * @var PaymentGateway
     */
    public $gateWayModel;

    /**
     * @var mixed result
     */
    protected $resultResponse;

    /**
     * config array codes that was fired from account config
     * 
     * @var array 
     */
    public $config_error_codes_from_account = array(
        DEFAULT_BRAINTREE_GATEWAY_KEY => array(401)
    );

    /**
     * process error
     * 
     * store error information
     */
    public $error_info = array(
        'error_from' => '',
        'message' => '',
        'status_code' => '',
        'payment_getway' => ''
    );

    /**
     * Initializes this model.
     * This method is invoked in the constructor right after {@link scenario} is set.
     * You may override this method to provide code that is needed to initialize the model (e.g. setting
     * initial property values.)
     * 
     * @return type
     * @throws Exception
     */
    public function init() {
        $this->setupPaymentModel();
        $this->gateWayModel = new PaymentGateway();
        $param_config = Yii::$app->params['payment_config'];
        $this->test_mode = $param_config['test_enviroment'];
        $this->currencies_allowed = $param_config['allowed_currencies'];
        return parent::init();
    }

    /**
     * @return void
     */
    public function setupPaymentModel($type = DEFAULT_BRAINTREE_GATEWAY_KEY) {
        $this->payment_type = $type;
        if ($this->payment_type === DEFAULT_BRAINTREE_GATEWAY_KEY) {
            require_once __DIR__ . '/Braintree/Braintree.php';
            if ($this->test_mode) {
                Braintree_Configuration::environment('sandbox');
            } else {
                Braintree_Configuration::environment('production');
            }

            $this->payment_model = Braintree_Configuration::$global;
        } else {
            throw new Exception('Payment Model is not setted');
        }
    }

    public function getResult() {
        return $this->result;
    }

    /**
     * Rules
     */
    public function rules() {
        return [
            [['cardholdername', 'cardnumber', 'expirationdate', 'cvv', 'cardnumber1', 'cardnumber2', 'cardnumber3', 'cardnumber4', 'agreewithterm'], 'required'],
            [['email', 'firstname', 'lastname', 'phone', 'phone_code'], 'required', 'on' => 'newUser'],
            [['phone'], 'match', 'pattern' => '/^[\(+]?([0-9]{1,3})\)?[-. ]?([0-9]{1,3})\)?[-. ]?([0-9]{3,4})[-. ]?([0-9]{0,4})[-. ]?([0-9]{0,4})$/', 'on' => 'newUser'],
            [['abn'], 'required', 'on' => 'newABN'],
            [['email', 'firstname', 'lastname', 'phone', 'phone_code', 'abn'], 'required', 'on' => 'newABNU'],
            [['phone'], 'match', 'pattern' => '/^[\(+]?([0-9]{1,3})\)?[-. ]?([0-9]{1,3})\)?[-. ]?([0-9]{3,4})[-. ]?([0-9]{0,4})[-. ]?([0-9]{0,4})$/', 'on' => 'newABNU'],
            [['email'], 'uniqueemail', 'on' => 'newUser'],
            [['email'], 'uniqueemail', 'on' => 'newABNU'],
            [['abn', 'sponsorship'], 'required', 'on' => 'newSponsor'],
            [['email', 'firstname', 'lastname', 'phone', 'phone_code', 'abn', 'sponsorship'], 'required', 'on' => 'newSponsorU'],
            [['phone'], 'match', 'pattern' => '/^[\(+]?([0-9]{1,3})\)?[-. ]?([0-9]{1,3})\)?[-. ]?([0-9]{3,4})[-. ]?([0-9]{0,4})[-. ]?([0-9]{0,4})$/', 'on' => 'newSponsorU'],
            [['email'], 'uniqueemail', 'on' => 'newSponsorU'],
            [['email'], 'email'],
            [['expirationdate'], 'validate_card_expire'],
            [['cardnumber', 'expmonth'], 'integer'],
            [['currency'], 'validate_currency'],
            [['cardnumber'], 'validate_cardnumber'],
        ];
    }

    /**
     * 
     * @param type $attribute
     */
    function validate_currency($attribute) {
        if (!in_array($this->currency, $this->currencies_allowed)) {
            $this->addError('currency', 'currency: ' . $this->currency . ' is not allowed.');
            return false;
        }
        return true;
    }

    /**
     * validate card number
     * 
     * @param type $attribute
     * @return boolean
     */
    function validate_cardnumber($attribute) {
        $cardnumber = $this->cardnumber;
        $cardnumber = preg_replace('[^0-9]', '', $cardnumber);
        if ($cardnumber < 9)
            return false;
        $cardnumber = strrev($cardnumber);
        $total = 0;
        for ($i = 0; $i < strlen($cardnumber); $i++) {
            $current_number = substr($cardnumber, $i, 1);
            if ($i % 2 === 1) {
                $current_number *= 2;
            }

            if ($current_number > 9) {
                $first_number = $current_number % 10;
                $second_number = ($current_number - $first_number) / 10;
                $current_number = $first_number + $second_number;
            }

            $total += $current_number;
        }

        if ($total % 10 != 0) {
            $this->addError($attribute, 'Please re-enter your credit card number');
            return false;
        }

        return true;
    }

    /**
     * validate card number
     * 
     * @param type $attribute
     * @return boolean
     */
    function validate_card_expire($attribute) {
        if (!$this->expmonth || !$this->expyear)
            return false;

        $mm = $this->expmonth;
        $yy = $this->expyear;
        if ($mm < 1 || $mm > 12) {
            $this->addError($attribute, 'Invalid card expiration date');
            return false;
        }

        $year = date('Y');
        $yy = substr($year, 0, 2) . $yy; // eg 2007
        if (is_numeric($yy) && $yy >= $year && $yy <= ($year + 10)) {
            
        } else {
            $this->addError($attribute, 'Invalid card expiration date');
        }
        if ($yy === $year && $mm < date('n')) {
            $this->addError($attribute, 'Invalid card expiration date');
        }

        return true;
    }

    /**
     * 
     */
    public function clearErrors($attribute = NULL) {
        $this->error_info = array(
            'error_from' => '',
            'message' => '',
            'status_code' => '',
            'payment_getway' => ''
        );

        return parent::clearErrors($attribute);
    }

    /**
     * 
     */
    public function beforeValidate() {
        $this->processExpiredDate();
        return parent::beforeValidate();
    }

    /**
     * process expired date
     * 
     * @return string
     */
    protected function processExpiredDate() {
        if ((int) ($this->expyear) > 99) {
            $this->expyear = substr($this->expyear, strlen($this->expyear) - 2);
        } elseif ((int) $this->expyear < 10) {
            $this->expyear = '0' . (int) $this->expyear;
        }

        if ((int) ($this->expmonth) > 99) {
            $this->expmonth = substr($this->expmonth, strlen($this->expmonth) - 2);
        } elseif ((int) ($this->expmonth) < 10) {
            $this->expmonth = '0' . (int) $this->expmonth;
        }

        $this->expirationdate = $this->expmonth . '/' . $this->expyear;

        return $this->expirationdate;
    }

    /**
     * trim all attributes
     * 
     * @return void
     */
    protected function trimAllAttributes() {
        $attrs = $this->attributes;
        foreach ($attrs as $key => $item) {
            if (is_string($item) && !is_bool($item) && !is_null($item)) {
                $this->$key = trim($item);
            }
        }
    }

    /**
     * =============================================================================================================
     * Methods Process Payment
     * =============================================================================================================
     */

    /**
     * Set attribute from usr card
     */
    public function setAttributeFromUsrCard($card) {
        $this->cardnumber = isset($card['card_number']) ? $card['card_number'] : '';
        $this->cvv = isset($card['card_cvv']) ? $card['card_cvv'] : '';
        $this->expirationdate = preg_replace("/[^a-zA-Z0-9._-]+/", "", (isset($card['card_expiry']) ? $card['card_expiry'] : ''));
        $this->expmonth = (int) substr($this->expirationdate, 0, 2);
        $this->expyear = (int) substr($this->expirationdate, 2);
    }

    /**
     * mapping data from attributes of this class with other libaray
     * 
     * @return 
     */
    public function mappingData() {
        $this->processExpiredDate();
        if ($this->payment_type === DEFAULT_BRAINTREE_GATEWAY_KEY) {
            $this->payment_model->merchantId($this->user);
            $this->payment_model->publicKey($this->public_key);
            $this->payment_model->privateKey($this->private_key);
        }
    }

    /**
     * Process payment
     * 
     * Sent params to payment gateway
     * @return boolen Description
     */
    public function process($validate = false) {
        if ($validate && !$this->validate())
            return false;

        if ($this->payment_type === DEFAULT_BRAINTREE_GATEWAY_KEY) {
            $this->createCustomer();
            $this->processingFee();
            try {
                $this->resultResponse = Braintree_Transaction::sale(array(
                            'amount' => $this->getPrice(),
                            'orderId' => $this->orderId,
                            'creditCard' => array(
                                'number' => $this->cardnumber,
                                'expirationMonth' => $this->expmonth,
                                'expirationYear' => $this->expyear,
                                'cvv' => $this->cvv
                            ),
                            'options' => array(
                                'submitForSettlement' => true
                            )
                ));
            } catch (Exception $e) {
                $this->status_code = $e->getCode();
                $this->addError('payment_model', $e->getMessage());
                return false;
            }
        }

        $this->processResult();
        if ($this->getErrors()) {
            return false;
        }

        return true;
    }

    /**
     * Process payment by token
     * @param type $userId
     */
    public function processByCustomer($userId = 0) {
        if ($this->payment_type === DEFAULT_BRAINTREE_GATEWAY_KEY) {
            if ($this->customerId == '' && $userId > 0) {
                if (!$this->getCustomerByUser($userId)) {
                    $this->addError('payment_model', 'Not token');
                    return false;
                }
            }

            $this->processingFee();
            try {
                $this->resultResponse = Braintree_Transaction::sale(array(
                            'customerId' => $this->customerId,
                            'amount' => $this->getPrice(),
                            'options' => array(
                                'submitForSettlement' => true
                            )
                ));
            } catch (Exception $e) {
                $this->status_code = $e->getCode();
                $this->addError('payment_model', $e->getMessage());
                return false;
            }
        }

        $this->processResult();
        if ($this->getErrors()) {
            return false;
        }

        return true;
    }

    /**
     * Process payment
     * 
     * Sent params to payment gateway
     * @return boolen Description
     */
    public function createCustomer() {
        if ($this->payment_type === DEFAULT_BRAINTREE_GATEWAY_KEY) {
//            try {
            $email = $this->email ? $this->email : (isset(user()->identity->email) ? user()->identity->email : '');
            $phone = $this->phone ? $this->phone : (isset(user()->identity->phone) ? user()->identity->phone : '');
            $params = array(
                'firstName' => $this->firstname,
                'lastName' => $this->lastname,
                'email' => $email,
                'phone' => $phone,
                'creditCard' => array(
                    'cardholderName' => $this->cardholdername,
                    'number' => $this->cardnumber,
                    'expirationMonth' => $this->expmonth,
                    'expirationYear' => $this->expyear,
                    'cvv' => $this->cvv,
//                                'billingAddress' => array(
//                                    'firstName' => $this->firstname,
//                                    'lastName' => $this->lastname,
//                                )
                )
            );
//                echo '<pre>';
//                var_dump($params);
//                echo '</pre>';
//                die();
            $result = Braintree_Customer::create($params);
            if ($result->success) {
                $this->customerId = $result->customer->creditCards[0]->customerId;
                $this->cardtype = $result->customer->creditCards[0]->cardType;
                $this->countryOfIssuance = $result->customer->creditCards[0]->countryOfIssuance;
                $this->paymentMethodToken = $result->customer->paymentMethods[0]->token;
                return ['success' => true];
            } else {
                $message = $result->message;
                if ($message == 'Credit card number is not an accepted test number.') {
                    $message = SettingsMessageSearch::t('payment', 'card_number_test_error', 'Credit card number is not accepted. Please check your card information or try another card.');
                }
                return ['success' => false, 'message' => $message];
            }

//            } catch (Exception $e) {
//                var_dump($e);
//                return ['success' => false,'message' => SettingsMessageSearch::t('payment','payment_create_customer_error')];
//            }
        }
        return ['success' => false, 'message' => SettingsMessageSearch::t('payment', 'payment_type_not_support')];
    }

    /**
     * Get customer info by user_id or employer_id
     * @param type $userId
     * @param type $type
     * @return boolean
     */
    public function getCustomerByUser($userId, $type = true) {
        $condition['status'] = StatusEnum::STATUS_ACTIVED;
        $condition['employer_id'] = 0;
        if ($type) {
            $condition['user_id'] = $userId;
        } else {
            $condition['employer_id'] = $userId;
        }

        $ustomer = UsrCards::find()->where($condition)->one();
        if ($ustomer) {
            $this->customerId = $ustomer->customer_id;
            $this->cardtype = $ustomer->card_type;
            $this->countryOfIssuance = $ustomer->country_of_issuance;
            $this->paymentMethodToken = $ustomer->payment_method_token;
            $this->subscriptionId = $ustomer->subscription_id;
            return true;
        } else {
            return false;
        }
    }

    public function processingFee() {
        if ($this->is_australia == 1) {
            $this->gst_amount = $this->amount * 0.1;
        }

        if ($this->cardtype == 'American Express') {
            $this->processing_fee = ($this->amount + $this->gst_amount) * 0.0325 + 0.3;
        } else {
            if ($this->countryOfIssuance == 'AU' || $this->countryOfIssuance == 'AUS') {
                $this->processing_fee = ($this->amount + $this->gst_amount) * 0.0175 + 0.3;
            } else {
                $this->processing_fee = ($this->amount + $this->gst_amount) * 0.0325 + 0.3;
            }
        }
    }

    public function getPrice() {
        return round($this->amount + $this->gst_amount + $this->processing_fee, 2);
    }

    public function createSubscription($planId, $tomorrow = false) {
        try {
            if (!$tomorrow) {
                $tomorrow = new DateTime(date("Y-m-d", strtotime("+31 day")));
                $tomorrow->setTime(0, 0, 0);
            }
            $dataSub['paymentMethodToken'] = $this->paymentMethodToken;
            $dataSub['firstBillingDate'] = $tomorrow;
            $dataSub['price'] = $this->getPrice();
            $dataSub['planId'] = $planId;

            $result = Braintree_Subscription::create($dataSub);
            if ($result->success) {
                $this->subscriptionId = $result->subscription->id;
                $dataLog['user_id'] = user()->id;
                $dataLog['employer_id'] = 0;
                $dataLog['subscription_id'] = $this->subscriptionId;
                $dataLog['action_type'] = $result->subscription->status;
                $dataLog['price'] = $result->subscription->price;
                LogMembership::insertLog($dataLog);
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => $result->message];
            }
        } catch (Exception $e) {
//            var_dump($e);
            return ['success' => false, 'message' => SettingsMessageSearch::t('payment', 'subscription_create_error')];
        }
    }

    public function deleteSubscription() {
        if ($this->subscriptionId != '') {
            try {
                $result = Braintree_Subscription::find($this->subscriptionId);
                if ($result->status == Braintree_Subscription::CANCELED) {
                    return true;
                }

                $result = Braintree_Subscription::cancel($this->subscriptionId);
                return $result->success;
            } catch (Exception $e) {
                return false;
            }
        }

        return true;
    }

    public function activeSubscription() {
        if ($this->subscriptionId != '') {
            try {
                $result = Braintree_Subscription::find($this->subscriptionId);
                if ($result->success) {
                    $data['paymentMethodToken'] = $result->paymentMethodToken;
                    $data['planId'] = $result->planId;
                    $data['firstBillingDate'] = $result->firstBillingDate;
                    $data['price'] = $result->price;
                    $result = Braintree_Subscription::create($data);
                    if ($result->success) {
                        $cardInfo = UsrCards::findOne(['subscription_id' => $this->subscriptionId]);
                        $this->subscriptionId = $result->subscription->id;
                        $dataLog['user_id'] = Yii::$app->user->id;
                        $dataLog['employer_id'] = $cardInfo->employer_id;
                        $dataLog['subscription_id'] = $this->subscriptionId;
                        $dataLog['action_type'] = $result->subscription->status;
                        $dataLog['price'] = $result->subscription->price;
                        LogMembership::insertLog($dataLog);
                        $cardInfo->subscription_id = $this->subscriptionId;
                        $cardInfo->user_id = Yii::$app->user->id;
                        $cardInfo->save(false);

                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        }

        return true;
    }

    /**
     * Process Result
     */
    public function processResult() {
        if ($this->payment_type === DEFAULT_BRAINTREE_GATEWAY_KEY && $this->resultResponse) {
            if ($this->resultResponse->success) {
                $this->transactionID = $this->resultResponse->transaction->id;
            } else if ($this->resultResponse->transaction) {
                $this->status_code = $this->resultResponse->transaction->processorResponseCode;
                $this->addError('payment_model', $this->resultResponse->transaction->processorResponseText);
            } else {
                $this->addError('payment_model', $this->resultResponse->message);
            }
        }

        if ($this->getErrors()) {
            $this->error_info['status_code'] = $this->status_code;
            $this->error_info['message'] = $this->toStringErrors();
            $this->error_info['payment_getway'] = $this->payment_type;
            if (isset($this->config_error_codes_from_account[$this->payment_type]) && in_array($this->status_code, $this->config_error_codes_from_account[$this->payment_type])) {
                $this->error_info['error_from'] = self::ERROR_FROM_ACCOUNT;
            } else {
                $this->error_info['error_from'] = self::ERROR_FROM_CARD;
            }
        }
    }

    /**
     * conver array errors to string
     * @return string
     */
    public function toStringErrors($has = ', ', $errors = array()) {
        $result = array();
        $errors = $errors ? $errors : $this->getErrors();
        if ($errors) {
            foreach ($errors as $item) {
                if (!is_array($item)) {
                    $result[] = $item;
                } else if ($e = $this->toStringErrors($has, $item)) {
                    $result[] = $e;
                }
            }
        }

        if ($result) {
            return implode($has, $result);
        }

        return null;
    }

    /**
     * generate a random orderId
     * @return string
     */
    public static function randomOrderId() {
        return uniqid() . str_pad(dechex(mt_rand(0, 0xFFFFF)), 1, '0', STR_PAD_LEFT);
    }

    /**
     * Payment with a partner
     * @return boolen
     */
    public function paymentWith() {
        $dataGateWay = PaymentGateway::getGateWayPrimary();
        if ($dataGateWay) {
            $this->payment_type = $dataGateWay->payment_key;
            $dataGateWay->setGateWayModel($dataGateWay->payment_key);
            if ($dataGateWay->gateWayModel === null) {
                return ['success' => false, 'message' => SettingsMessageSearch::t('payment', 'gatewaymodel_error', 'Gateway for paymentgateway not found!')];
            }
            $dataGateWay->gateWayModel->decodeData($dataGateWay->payment_config);
            $this->setupPaymentModel($dataGateWay->payment_key);
            $this->setAttributes($dataGateWay->gateWayModel->attributes, false);
            $this->mappingData();
            $this->gateWayModel = $dataGateWay;
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => SettingsMessageSearch::t('payment', 'gateway_not_found', 'Payment gate way not found!')];
        }
    }

    /**
     * @HuuDoan
     * payment of gateway
     * @param type $pm
     */
    public function paymentTypeGateWay($pm) {
        $this->clearErrors();
        if ($pm) {
            /* @var $pm PaymentGateway */
            $this->payment_type = $pm->payment_key;
            $pm->setGateWayModel($pm->payment_key);
            # validate gateway model and currencies
            if ($pm->gateWayModel == null) {
                $this->addError('user', 'username and password are not setted.');
                $this->error_info['error_from'] = self::ERROR_FROM_ACCOUNT;
                return false;
            } else {
                $pm->getCurrencies();
                $this->currencies_allowed = $pm->payment_currency;
                if (!$this->validate_currency('currency')) {
                    $this->error_info['error_from'] = self::ERROR_FROM_ACCOUNT;
                    return false;
                }
            }

            //$pm->gateWayModel->decodeData($pm->payment_config)->decrypData();
            $pm->gateWayModel->decodeData($pm->payment_config);
            $this->setupPaymentModel($pm->payment_key);
            $config = $pm->gateWayModel->attributes;
            $this->setAttributes($config, false);
            $this->mappingData();
            $this->gateWayModel = $pm;

            return true;
        } else {
            $this->addError('gateWayModel', 'This partner have not set up payment gateway.');
            $this->error_info['error_from'] = self::ERROR_FROM_ACCOUNT;
            return false;
        }
    }

    public static function getMonth() {
        return array(
            1 => 'Jan'
            , 2 => 'Feb'
            , 3 => 'Mar'
            , 4 => 'Apr'
            , 5 => 'May'
            , 6 => 'Jun'
            , 7 => 'Jul'
            , 8 => 'Aug'
            , 9 => 'Sep'
            , 10 => 'Oct'
            , 11 => 'Nov'
            , 12 => 'Dec'
        );
    }

    public static function getYear() {
        $curYear = (int) date('Y');
        $dataYear = array();
        for ($i = 0; $i <= 10; $i++) {
            $key = substr($curYear, -2);
            $dataYear[$key] = $curYear;
            $curYear++;
        }

        return $dataYear;
    }

    public function Uniqueemail($attribute) {
        if (UserModel::find()->where(['email' => $this->email])->count() > 0) {
            $this->addError('email', 'Email has exists.');
        }
    }

    public function sale() {

        if ($this->payment_type === DEFAULT_BRAINTREE_GATEWAY_KEY) {
            try {
                $resultResponse = Braintree_Transaction::sale([
                            'amount' => $this->getPrice(),
                            'orderId' => $this->orderId,
                            'creditCard' => [
                                'number' => $this->cardnumber,
                                'expirationMonth' => $this->expmonth,
                                'expirationYear' => $this->expyear,
                                'cvv' => $this->cvv
                            ],
                            'options' => [
                                'submitForSettlement' => true
                            ]
                ]);
                if ($resultResponse->success) {
                    $this->transactionID = $resultResponse->transaction->id;
                    PaymentOrders::updateTransactionId($this->orderId, $this->transactionID);
                    return ['success' =>  true];
                } else if ($resultResponse->transaction) {
                    return ['success' => false, 'message' => $resultResponse->transaction->processorResponseText];
                } else {
                    return ['success' => false, 'message' => $resultResponse->message];
                }

            } catch (Exception $e) {
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }
    }

}
