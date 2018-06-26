<?php
namespace frontend\controllers;

use common\core\enums\CartEnum;
use common\core\enums\payments\PaymentsEnum;
use common\core\enums\product\ProductEnum;
use common\core\payments\LoPayment;
use common\models\customer\Customers;
use common\models\libs\Cities;
use common\models\libs\States;
use common\models\order\OrderDetails;
use common\models\order\Orders;
use common\models\product\ProductsSearch;
use common\models\settings\SystemSetting;
use common\utilities\UtilityHtmlFormat;
use common\utilities\UtilityUrl;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\mongodb\Exception;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class CheckoutController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        
        $behaviors['verbs'] = [
           'class' => VerbFilter::className(),
            'actions' => [
            ],
        ];
        return $behaviors;
    }
    
    public function actionValidate() {
        set_time_limit(10000);
        $return = [];
        $model = new LoPayment();
//        if(Yii::$app->session->has(CartEnum::PAYMENT_KEY)) {
//            $model->attributes = Yii::$app->session->get(CartEnum::PAYMENT_KEY);
//        }
        if (Yii::$app->request->isPost) {
            $cart = Yii::$app->session->has(CartEnum::CART_KEY) ? Yii::$app->session->get(CartEnum::CART_KEY) : [];
            $subtotal = $cart ? ProductsSearch::getTotalPrice($cart) : 0;
            if ($cart && $subtotal > 0) {
                $model->currency = CURRENCY_CODE;
                $model->amount = $subtotal;
                $model->transactionID = 0;
                $model->currency = CURRENCY_CODE;
                $model->setAttributes(Yii::$app->getRequest()->getBodyParams(), FALSE);
                $model->arround_time_later = $model->arround_time_later ? $model->arround_time_later : null;
                $model->arround_time_today = $model->arround_time_today ? $model->arround_time_today : null;
                $model->currency = CURRENCY_CODE;
                if($model->payment_method == PaymentsEnum::PAYMENT_METHOD_BY_CARD) {
                    $model->scenario = 'payment_method_card';
                }
                
                if ($model->validate()) {
                    Yii::$app->session->set(CartEnum::PAYMENT_KEY, $model->attributes);
                    return true;
                } else {
                    if($model->hasErrors('expirationdate')){
                        $mess = $model->getErrors('expirationdate');
                        $model->addError('expmonth', $mess);
                        $model->addError('expyear', $mess);
                    }
                    return $model;
                }
            }
        }
        return $return;
    }
    
    /**
     * 
     * @return type
     */
    function actionConfirm() {
        $result = [];
        $cart = Yii::$app->session->has(CartEnum::CART_KEY) ? Yii::$app->session->get(CartEnum::CART_KEY) : [];
        $cart = ProductsSearch::getDataCart($cart);
        $payments_data = Yii::$app->session->get(CartEnum::PAYMENT_KEY);
        if ($cart && $payments_data) {
            Yii::$app->session->set(CartEnum::CART_KEY, $cart);
            $subtotal = ProductsSearch::getTotalPrice($cart);
            $payments_data['amount'] = $subtotal;
            Yii::$app->session->set(CartEnum::PAYMENT_KEY, $payments_data);
            $around_time = PaymentsEnum::getArroundTimeOpen();
            $shipping_methods = PaymentsEnum::enumLabelsShippingMethod();
            $payment_methods = PaymentsEnum::enumLabelsPaymentMethod();
            $payments_data['shipping_method_label'] = isset($payments_data['shipping_method'] )&& isset($shipping_methods[$payments_data['shipping_method']]) 
                     ? $shipping_methods[$payments_data['shipping_method']] : null;
            $payments_data['payment_method_label'] = isset($payments_data['payment_method'] )&& isset($payment_methods[$payments_data['payment_method']]) 
                     ? $payment_methods[$payments_data['payment_method']] : null;
            
            $states = isset($payments_data['state']) ? States::findOne($payments_data['state']) : null;
            $cities = isset($payments_data['city']) ? Cities::findOne($payments_data['city']) : null;
            $payments_data['arround_time_today_label'] = isset($payments_data['arround_time_today']) ? $around_time[$payments_data['arround_time_today']] : null;
            $payments_data['arround_time_later_label'] = isset($payments_data['arround_time_later']) ? $around_time[$payments_data['arround_time_later']] : null;
            $payments_data['state_label'] = $states ? $states->state_name : null;
            $payments_data['city_label'] = $cities ? $cities->city_name : null;
            $payments_data['cardnumber'] = isset($payments_data['cardnumber']) ? UtilityHtmlFormat::maskCreditCard(UtilityHtmlFormat::formatCreditCard($payments_data['cardnumber'])) : null;
            $result = [
                'cart' => $cart,
                'payments' => $payments_data,
                'subtotal' => $subtotal,
            ];
        }
        elseif($cart && !$payments_data){
            $result['redirect'] = '/checkout';
        }
        else{
            $result['redirect'] = '/order';
        }
        return $result;
    }
    
    
    function actionCheckout() {
        $result = [
            'success' => false, 
            'error' => [],
        ];
        $model = new LoPayment();
        if(Yii::$app->session->has(CartEnum::PAYMENT_KEY) && Yii::$app->session->has(CartEnum::CART_KEY)) {
            $carts = Yii::$app->session->get(CartEnum::CART_KEY);
            $model->setAttributes(Yii::$app->session->get(CartEnum::PAYMENT_KEY), FALSE);
            $success = false;
            $result_payment = [];
            $subtotal = $carts ? ProductsSearch::getTotalPrice($carts) : 0;
            $model->amount = $subtotal;
            if($model->validate()) {
                if($model->payment_method == PaymentsEnum::PAYMENT_METHOD_BY_CARD){
                    $result_payment = $this->startPaymentProcess(['model_payment' => $model, 'currency' => CURRENCY_CODE]);
                    
                    $success = $result_payment['success'];
                    if(isset($result_payment['listerrors']))
                        $result['error'] = $model->getErrors();
                }
                else
                    $success = true;
            }
            if($success) {
                //do transaction
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    //create customer
                    $customer = new Customers();
                    $customer->attributes = $model->attributes;
                    $customer->state_id = $model->state;
                    $customer->city_id = $model->city;
                    if($customer->validate()){
                        $customer->save(false);
                        $order = new Orders();
                        //$order->attributes = $model->attributes;
                        $order->customer_id = $customer->id;
                        $order->order_total = floatval(ProductsSearch::getTotalPrice($carts));
                        $order->payment_method = $model->payment_method;
                        $order->process_status = PaymentsEnum::PROCESS_PENDING;
                        $order->shipping_method = $model->shipping_method;
                        if((int)$model->shipping_method == PaymentsEnum::SHIPING_METHOD_DELIVERY){
                            $order->shipping_date = time();
                        }
                        elseif ((int)$model->shipping_method == PaymentsEnum::SHIPING_METHOD_PICK_UP_TODAY) {
                            $order->shipping_time = $model->arround_time_today;
                            $str = $model->arround_time_today == PaymentsEnum::AS_SOON_VALUES ? date('m/d/Y', time()) : date('m/d/Y', time()).' '.$model->arround_time_today;
                            $order->shipping_date = strtotime($str);
                        }
                        else{
                            $order->shipping_time = $model->arround_time_later;
                            $str = $model->arround_time_later == PaymentsEnum::AS_SOON_VALUES ? $model->shipping_date : $model->shipping_date.' '.$model->arround_time_later;
                            $order->shipping_date = strtotime($str);
                        }
                        $order->transaction_id = isset($result_payment['transaction_id']) ? $result_payment['transaction_id'] : null;
                        $time = md5(time());
                        $start = rand(0, strlen($time) - 8);
                        $order->order_code = substr($time, $start, 8);
                        if($order->validate()){
                            $order->save(false);
                            //insert order detail
                            $insert_details = [];
                            $details_email = [];
                            foreach ($carts as $key => $cart) {
                                if($cart['product_id']){
                                    $insert_details[] = [
                                        'order_id' => $order->id, 
                                        'product_id' => $cart['product_id'], 
                                        'price' => doubleval($cart['price']), 
                                        'quantity' => (int)$cart['quantity'],
                                        'half_option' => isset($cart['half_option']) && $cart['half_option'] ? ProductEnum::HALF_OPTION : ProductEnum::WHOLE_OPTION,
                                        'total' => isset($cart['total_price']) ? floatval($cart['total_price']) : 0,
                                    ];
                                    $details_email[] = [
                                        'name' => isset($cart['half_option']) && $cart['half_option'] ? $cart['name'].' (Half)' : $cart['name'],
                                        'quantity' => $cart['quantity'],
                                        'price' => CURRENCY_DISPLAYED.' '.number_format((string)$cart['price'],2),
                                        'total_price' => CURRENCY_DISPLAYED.' '.number_format((string)$cart['total_price'],2),
                                    ];
                                }
                            }
                            if($insert_details)
                                Yii::$app->db->createCommand()
                                        ->batchInsert(OrderDetails::tableName(), 
                                        ['order_id', 'product_id', 'price', 'quantity', 'half_option', 'total'], $insert_details)->execute();
                            
                            //delete session
                            Yii::$app->session->remove(CartEnum::CART_KEY);
                            Yii::$app->session->remove(CartEnum::PAYMENT_KEY);
                            $transaction->commit();
                            $result['success'] = true;
                            
                            //sendmail to customer
                            $setting = SystemSetting::find()->one();
                            if($setting && $setting->support_mail){
                                $around_time = PaymentsEnum::getArroundTimeOpen();
                                $shipping_methods = PaymentsEnum::enumLabelsShippingMethod();
                                $payment_methods = PaymentsEnum::enumLabelsPaymentMethod();
                                $city_name = $model->city ? Cities::findOne($model->city)->city_name : '';
                                $state_name = $model->state ? States::findOne($model->state)->state_name : '';
                                $mail_customer = [
                                    'firstname' => $model->firstname,
                                    'date' => date('d-M-Y', $order->created_time),
                                    'ajantha_link' => UtilityUrl::createAbsoluteUrl('/'),
                                    'invoice_number' => $order->order_code,
                                    'invoice_date' => date('d-M-Y', $order->created_time),
                                    'fullname' => $model->firstname.' '.$model->lastname,
                                    'email' => $model->email,
                                    'phone' => $model->phone,
                                    'address' => $model->address.', '.$model->district.', '.$city_name.', '.$state_name,
                                    'shipping_method' => $shipping_methods[$order->shipping_method],
                                    'shipping_time' => date('D, d-M-Y', $order->shipping_date),
                                    'shipping_around' => $order->shipping_method == PaymentsEnum::SHIPING_METHOD_DELIVERY ? '' : 
                                                        ($order->shipping_method == PaymentsEnum::SHIPING_METHOD_PICK_UP_TODAY ? $around_time[$model->arround_time_today] : $around_time[$model->arround_time_later]),
                                    'payment_method' => $payment_methods[$order->payment_method],
                                    'transaction' => $order->transaction_id,
                                    'details' => isset($details_email) ? $details_email : [],
                                    'total_price' => CURRENCY_DISPLAYED.' '.number_format((string)$order->order_total,2),
                                ];
                                Yii::$app->mail->compose('confirm_customer', $mail_customer)
                                        ->setFrom([$setting->support_mail => 'Ajantha'])
                                        ->setTo($model->email)
                                        ->setSubject('Thank you for your order')
                                        ->send();
                                //send mail to admin
                                $mail_admin = $mail_customer;
                                $mail_admin['admin_name'] = $setting->admin_name;
                                Yii::$app->mail->compose('alert_admin', $mail_admin)
                                        ->setFrom([$setting->support_mail => 'Ajantha'])
                                        ->setTo($setting->admin_email)
                                        ->setSubject('We have a new order from our customer '.$model->firstname)
                                        ->send();
                            }
                        }
                    }
                } catch (Exception $ex) {
                    $transaction->rollBack();
                }
            } else {
                $error = $model->getErrors();
                //$result['error'] = 1;
                if(isset($error['payment_model'][0])) {
                    $result['error'][] = Yii::t('payment', 'credit_card_is_not_accepted');
                }
                $result['success'] = false;
            }
        }
        return $result;
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
                if($model->process()) {
                    $result['success'] = true;
                    $result['transaction_id'] = $model->transactionID;
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
    public function actionGetdata() {
        $cart = Yii::$app->session->has(CartEnum::CART_KEY) ? Yii::$app->session->get(CartEnum::CART_KEY) : [];
        $cart = ProductsSearch::getDataCart($cart);
        Yii::$app->session->set(CartEnum::CART_KEY, $cart);
        $result = Yii::$app->session->has(CartEnum::CART_KEY) 
                ? [
                    'cart'=>  $cart,
                    'subtotal'=>  ProductsSearch::getTotalPrice($cart),
                    'states'=>  (new States())->searchFromCache(),
                    'around_times'=>  PaymentsEnum::getArroundTimeOpen(),
                    'checkout'=>  Yii::$app->session->get(CartEnum::PAYMENT_KEY),
                    'payment_methods'=>  PaymentsEnum::enumLabelsPaymentMethod(),
                    'months'=>  PaymentsEnum::getMonth(),
                    'years'=>  PaymentsEnum::getYear(),
                  ] 
                : [];
        if(Yii::$app->session->has(CartEnum::PAYMENT_KEY)) {
            $result['form'] = Yii::$app->session->get(CartEnum::PAYMENT_KEY);
            if(isset($result['form']['expmonth'])) {
                $result['form']['expmonth'] = (string) (int) $result['form']['expmonth'];
            }
            
        }
        return $result;
    }
    
    public function actionTest(){
        $str = '12/16/2015 18:00';
        $time = md5(time());
        $start = rand(0, strlen($time) - 8);
        $end = $start + 7;
        var_dump($time);
        var_dump(substr($time, $start, 8)); exit;
    }
}
