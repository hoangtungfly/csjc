<?php

namespace frontend\controllers;

use common\core\controllers\GlobalController;
use common\core\payments\LoPayment;
use common\models\payments\PaymentOrders;
use common\models\payments\UsrCards;
use common\models\user\UserModel;
use Yii;
use yii\helpers\Html;

/**
 * @author HuuDoan
 * @date 19/07/2015
 * Class for route payment
 */
class PaymentController extends GlobalController {

    public $userId = '';

    /**
     * 
     * @return type
     */
    function actionCheckout() {
        return $this->render('checkout');
    }

    /**
     * Payment and create order, log
     */
    function actionComplete() {
        set_time_limit(10000);
        if (Yii::$app->request->isPost) {
            $urlbb = $this->getParam('urlbb');
            if ($urlbb != "")
                $urlbb = base64_decode($urlbb);

            if (!Yii::$app->user->isGuest) {
                $userinfo = UserModel::findOne($this->userId);
                if (!$userinfo) {
                    echo json_encode(array('error' => 1, 'message' => Yii::t('payment', 'cant_not_create_user'), 'typeError' => 'user'));
                    exit();
                }
            }

            $success = false;
            $totalAmount = 0; //Add price service
            if ($totalAmount >= 0) {
                $model = new LoPayment();
                if (!Yii::$app->user->isGuest) {
                    $model->firstname = $userinfo->firstname;
                    $model->lastname = $userinfo->lastname;
                }

                $model->currency = CURRENCY_CODE;
                $model->amount = $totalAmount;
                $model->transactionID = 0;
                $this->validateCardInformation($model);
                if (Yii::$app->user->isGuest) {
                    $userinfo = $this->createAcount($model);
                    if (!$userinfo) {
                        echo json_encode(array('error' => 1, 'message' => Yii::t('payment', 'cant_not_create_user'), 'typeError' => 'user'));
                        exit();
                    } else {
                        $this->userId = $userinfo->user_id;
                    }
                }

                $result = $this->startPaymentProcess(['model_payment' => $model, 'currency' => CURRENCY_CODE]);
                $success = $result['success'];
            }

            if ($success) {
                //Create order
                $paymentorder = PaymentOrders::createOrder($model, $userinfo);
                if ($paymentorder) {
                    //Create Subscription
                    $model->createSubscription($this->appName);
                    $dataCard['user_id'] = $this->userId;
                    $dataCard['card_name'] = $model->cardholdername;
                    $dataCard['employer_id'] = isset(Yii::$app->user->identity->employerId) ? Yii::$app->user->identity->employerId : 0;
                    $dataCard['customer_id'] = $model->customerId;
                    $dataCard['country_of_issuance'] = $model->countryOfIssuance;
                    $dataCard['payment_method_token'] = $model->paymentMethodToken;
                    $dataCard['subscription_id'] = $model->subscriptionId;
                    $dataCard['card_type'] = $model->cardtype;
                    $dataCard['is_australia'] = $model->is_australia;
                    $dataCard['order_id'] = $paymentorder->order_id;
                    UsrCards::insertCustomer($dataCard);

                    //Send mail
                    $paymentorder->sendEmailforUser($paymentorder->order_id, $this->userId);
                    $param = ['orderid' => $paymentorder->order_id];
                    if ($this->getParam('urlbb')) {
                        $param['urlb'] = $this->getParam('urlbb');
                    }

                    $url = $this->createUrl('success', $param);
                    echo json_encode(array('error' => 0, 'url' => $url));
                    exit();
                }
            } else {
                if ($this->isAjax()) {
                    $error = $model->getErrors();
                    $data['error'] = 1;
                    if (isset($error['payment_model'][0])) {
                        $data['message'] = Yii::t('payment', 'credit_card_is_not_accepted');
                    }

                    echo json_encode($data);
                    exit();
                }
            }
        }
    }

    /**
     * 
     * @return type
     */
    function actionSuccess() {
        $orderId = $this->getParam('orderid');
        $orderInfo = PaymentOrders::findOne($orderId);
        if ($orderInfo) {
            return $this->render('success', ['orderInfo' => $orderInfo]);
        } else {
            return $this->redirect('/');
        }
    }

    /**
     * validate card number
     * @param LoPayment
     * @return LoPayment
     */
    protected function validateCardInformation(LoPayment $model) {
        $attrPayFlow = $this->getPOST('LoPayment');
        $newatr = array_merge($model->attributes, getElementHasValueArray($attrPayFlow));
        $model->currency = CURRENCY_CODE;
        $model->setAttributes($newatr, FALSE);
        if ($this->appName == APP_NAME_EMPLOYER) {
            $model->is_australia = 1;
        }

        if (Yii::$app->user->isGuest) {
            $model->scenario = 'newUser';
        }

        $model->cardnumber = $attrPayFlow['cardnumber1'] . $attrPayFlow['cardnumber2'] . $attrPayFlow['cardnumber3'] . $attrPayFlow['cardnumber4'];
        $model->cardnumber1 = $attrPayFlow['cardnumber1'];
        $model->cardnumber2 = $attrPayFlow['cardnumber2'];
        $model->cardnumber3 = $attrPayFlow['cardnumber3'];
        $model->cardnumber4 = $attrPayFlow['cardnumber4'];
        $model->validate();
        if (count($model->getErrors()) > 0) {
            foreach ($model->getErrors() as $attribute => $mess) {
                $listerrors[Html::getInputId($model, $attribute)] = $mess;
                if ($attribute == 'cardnumber') {
                    $listerrors[Html::getInputId($model, 'cardnumber1')] = $mess;
                    $listerrors[Html::getInputId($model, 'cardnumber2')] = $mess;
                    $listerrors[Html::getInputId($model, 'cardnumber3')] = $mess;
                    $listerrors[Html::getInputId($model, 'cardnumber4')] = $mess;
                } else if ($attribute == 'expirationdate') {
                    $listerrors[Html::getInputId($model, 'expmonth')] = $mess;
                    $listerrors[Html::getInputId($model, 'expyear')] = $mess;
                }
            }

            $error = json_encode($listerrors);
            echo json_encode(array('error' => 1, 'errors' => $error, 'typeError' => 'validate'));
            Yii::$app->end();
        }

        return $model;
    }

    /**
     * start payment process
     * 
     * @return array
     */
    protected function startPaymentProcess($params) {
        $model = $params['model_payment'];
        $currency = $params['currency'];
        if (isset($params['amount'])) {
            $model->amount = $params['amount'];
        }

        $model->clearErrors();
        $result = [
            'amount' => $model->amount,
            'success' => false,
            'transaction_id' => '',
            'listerrors' => array(),
            'error_from' => ''
        ];

        if ($model->amount > 0) {
            $model->transactionID = 0;
            $model->currency = $currency;
            if ($model->paymentWith()) {
                if ($model->createCustomer()) {
                    $result['success'] = true;
                }
            } else {
                foreach ($model->getErrors() as $attribute => $mess) {
                    $listerrors[Html::getInputId($model, $attribute)] = $mess;
                    if ($attribute == 'expirationdate') {
                        $listerrors[Html::getInputId($model, 'expmonth')] = $mess;
                        $listerrors[Html::getInputId($model, 'expyear')] = $mess;
                    }

                    $result['listerrors'] = $listerrors;
                }

                $result['success'] = false;
            }
        } else {
            $result['success'] = true;
        }
        return $result;
    }

    private function createAcount($model) {
        $userModel = new CandidateAccount();
        $userModel->firstname = $model->firstname;
        $userModel->lastname = $model->lastname;
        $userModel->email = $model->email;
        $userModel->phone_code = $model->phone_code;
        $userModel->phone = $model->phone;
        $userModel->isEmailSending = true;
        return $userModel->register();
    }

}
