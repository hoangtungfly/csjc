<?php

namespace common\models\payments;

use application\webadmanager\models\UserAdmanager;
use common\core\dbConnection\GlobalActiveRecord;
use common\core\enums\EmailsettingEnum;
use common\core\enums\payments\PaymentsEnum;
use common\core\enums\StatusEnum;
use common\core\payments\LoPayment;
use common\models\plan\PlanSearch;
use common\models\user\UsrApplication;
use common\utilities\UltilityEmail;
use common\utilities\UtilityDateTime;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * This is the model class for table "payment_orders".
 *
 * @property integer $order_id
 * @property integer $payment_id
 * @property string $user_id
 * @property double $order_total
 * @property double $order_cash
 * @property integer $order_credits
 * @property double $order_fee
 * @property string $detail_firstname
 * @property string $detail_lastname
 * @property string $detail_address1
 * @property string $detail_address2
 * @property integer $detail_city
 * @property integer $detail_postcode
 * @property string $detail_country
 * @property integer $created_time
 * @property integer $modified_time
 * @property string $ipaddress
 * @property string $order_currency
 * @property integer $deleted
 * @property integer $order_type
 * @property integer $status
 * @property integer $customer_id
 * @property integer $transaction_id
 * @property integer $object_id
 * @property integer $gst_amount
 *
 * @property PaymentOrderObjects[] $paymentOrderObjects
 * @property Payments $payment
 * @property UsrApplication[] $usrApplications
 */
class PaymentOrders extends GlobalActiveRecord {
    public $plan_id;
    public $order_id_count = 100000000;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'payment_orders';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_id', 'user_id', 'order_currency'], 'required'],
            [['payment_id', 'user_id', 'order_credits', 'detail_city', 'detail_postcode', 'created_time', 'modified_time', 'deleted', 'order_type', 'status', 'customer_id', 'object_id'], 'integer'],
            [['order_total', 'order_cash', 'order_fee', 'gst_amount', 'order_amount'], 'number'],
            [['detail_firstname', 'detail_lastname'], 'string', 'max' => 100],
            [['detail_address1', 'detail_address2', 'transaction_id'], 'string', 'max' => 255],
            [['detail_country'], 'string', 'max' => 10],
            [['ipaddress'], 'string', 'max' => 30],
            [['order_currency'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'order_id' => 'Order ID',
            'payment_id' => 'Payment ID',
            'user_id' => 'User ID',
            'order_total' => 'Order Total',
            'order_cash' => 'Order Cash',
            'order_credits' => 'Order Credits',
            'order_fee' => 'Order Fee',
            'order_amount' => 'Order Amount',
            'gst_amount' => 'GST',
            'detail_firstname' => 'Detail Firstname',
            'detail_lastname' => 'Detail Lastname',
            'detail_address1' => 'Detail Address1',
            'detail_address2' => 'Detail Address2',
            'detail_city' => 'Detail City',
            'detail_postcode' => 'Detail Postcode',
            'detail_country' => 'Detail Country',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'ipaddress' => 'Ipaddress',
            'order_currency' => 'Order Currency',
            'deleted' => 'Deleted',
            'order_type' => 'Order Type',
            'status' => 'Status',
            'customer_id' => 'Customer Id',
            'transaction_id' => 'Transaction Id',
            'object_id' => 'Object Id',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getPaymentOrderObjects() {
        return $this->hasMany(PaymentOrderObjects::className(), ['order_id' => 'order_id']);
    }
    
    public function getPaymentoo() {
        return $this->hasOne(PaymentOrderObjects::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPayment() {
        return $this->hasOne(Payments::className(), ['payment_id' => 'payment_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getUsrApplications() {
        return $this->hasMany(UsrApplication::className(), ['order_id' => 'order_id']);
    }

    /**
     * @inheritdoc
     * @param type $insert
     * @return type
     */
    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->ipaddress = app()->getRequest()->getUserIP();
        } else {
            $this->order_id_text = PaymentsEnum::PREFIX_ORDER_ID_TEXT . $this->order_id;
        }
        
        $this->order_amount = $this->order_total + $this->gst_amount + $this->order_fee;
        
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     * @param type $insert
     * @param type $changedAttributes
     * @return type
     */
    public function afterSave($insert, $changedAttributes) {
        app()->db->createCommand("update payment_orders set order_id_text = CONCAT('" . PaymentsEnum::PREFIX_ORDER_ID_TEXT . "',order_id) WHERE order_id = " . (int) $this->order_id)
                ->execute();
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @HuuDoan
     * Send order detail for user affter purchased
     * @param type $orderId
     * @param type $userId
     */
    public function sendEmailforUser($orderId, $userId) {
        $mail = new UltilityEmail();
        $usermodel = UserAdmanager::findOne($userId);
        $usermodel->CHtmlEncodeAttributes();
        $orderInfo = self::getOrderDetail($orderId);
        $content = '';
        if ($usermodel && $orderInfo) {
            $date = new UtilityDateTime();
            $orderInfo['total'] = number_format($orderInfo['order_total'] + $orderInfo['gst_amount'] + $orderInfo['order_fee'], 2);
            $orderInfo['order_total'] = number_format($orderInfo['order_total'], 2);
            $orderInfo['gst_amount'] = number_format($orderInfo['gst_amount'], 2);
            $orderInfo['order_fee'] = number_format($orderInfo['order_fee'], 2);
            $orderInfo['createdtime'] = $date->intToTime($orderInfo['created_time'], 'd-M-Y');
            $array = ['orderid' => $orderId] + $usermodel->attributes + $orderInfo;
            $array['link'] = HTTP_HOST . '/profile';
            $mail->getTemplateText(EmailsettingEnum::ORDER_CONFIRMATION, $array);
            $mail->send($usermodel->email);
//            $privateMail = new SysPrivateMail();
//            $privateMail->title = $mail->subject;
//            $privateMail->content = $mail->content;
//            $privateMail->show_html = 1;
//            $send = $usermodel->app_type == APP_TYPE_EMPLOYER ? [EmployerProfile::findOne(['created_by' => $usermodel->user_id])->employer_id] : [$usermodel->user_id];
//            $sendArray[$usermodel->app_type] = $send;
//            $privateMail->own_type = APP_TYPE_SYSTEM;
//            $privateMail->own_id = APP_TYPE_SYSTEM;
//            return $privateMail->sendMessages($sendArray);
        }
    }
    
    public function getOrderDetail($orderId) {
        if (!$orderId) {
            return false;
        }
        
        $query = new Query();
        $query->select('o.order_id, o.gst_amount, o.order_fee, o.order_total, o.detail_firstname, o.detail_lastname, ss.name as plan_title
                        , od.order_object_id, od.object_id, od.transaction_id, od.object_price, od.object_qty, od.object_tax, od.created_time')
                ->from('payment_orders o')
                ->join('INNER JOIN', 'payment_order_objects od', 'o.order_id = od.order_id')
                ->join('INNER JOIN', 'plan ss', 'od.object_id = ss.id')
                ->where('o.order_id = :order_id', array(':order_id' => $orderId));
        return $query->one();
    }

    /**
     * @HuuDoan
     * Get Content order
     * @param type $orderId
     * @return string|boolean
     */
    public function getContentOrder($orderId, $usermodel) {
        if (!$orderId) {
            return false;
        }

        $query = new Query();
        $query->select('od.order_id, od.order_object_id, od.object_id, od.transaction_id, od.object_price, od.object_qty, od.object_tax, od.created_time')
                ->from('payment_order_objects od')
                ->where('od.order_id = :order_id', array(':order_id' => $orderId))
                ->orderBy('od.order_object_id DESC');
        $product = $query->one();
        $html = '';
        if ($product) {
            $date = new UtilityDateTime();
            $html .= '<tr>';
            $html .= '<td>' . number_format($product['object_price'], 2) . '</td>';
            $html .= '<td>' . $date->intToTime($product['created_time'], 'd-M-Y') . '</td>';
            $html .= '<td>Credit card</td>';
            $html .= '<td>' . $product['transaction_id'] . '</td>';
            $html .= '</tr>';
        }

        return $html;
    }

    /**
     * 
     * @param type $model
     * @param type $serviceInfo
     * @param type $userinfo
     * @return PaymentOrders
     */
    public static function createOrder($model, $user_id, $object_id, $price, $customerid) {
        /*@var $model LoPayment */
        $paymentorder = new PaymentOrders();
        $paymentorder->payment_id = 1;
        $paymentorder->user_id = $user_id;
        $paymentorder->order_total = $model->amount;
        $paymentorder->order_currency = CURRENCY_DISPLAYED;
        $paymentorder->order_credits = 0;
        $paymentorder->order_fee = 0;
        $paymentorder->customer_id = $customerid;
        $paymentorder->detail_firstname = isset($model->firstname) ? $model->firstname : '';
        $paymentorder->detail_lastname = isset($model->lastname) ? $model->lastname : '';
        $paymentorder->detail_address1 = isset($model->address1) ? $model->address1 : '';
        $paymentorder->detail_address2 = isset($model->address2) ? $model->address2 : '';
        $paymentorder->detail_city = isset($model->city) ? $model->city : '';
        $paymentorder->detail_postcode = isset($model->postcode) ? $model->postcode : '';
        $paymentorder->detail_country = isset($model->country) ? $model->country : '';
        $paymentorder->gst_amount = $model->gst_amount;
        $paymentorder->order_fee = $model->processing_fee;
        $paymentorder->order_id_text = '';
        $paymentorder->object_id = $object_id;
        if ($paymentorder->save()) {
            $model->orderId = $paymentorder->order_id;
            $paymentorderobject = new PaymentOrderObjects();
            $paymentorderobject->object_qty = 1;
            $paymentorderobject->transaction_id = $model->transactionID;
            $paymentorderobject->saveProductByOrder($paymentorder->order_id, $object_id, $price, $paymentorder->order_currency, $model->payment_type);
            return ['success' => true,'model' => $paymentorder];
        } else {
            $errors = $paymentorder->getErrors();
            foreach($errors as $key => $error) {
                $errors[$key] = implode("<br>",$error);
            }
            return [
                'success'       => false,
                'message'       => implode("<br>",$errors),
            ];
        }

        
    }
    
    /**
     * 
     * @param type $model
     * @param type $serviceInfo
     * @param type $userinfo
     * @param type $booking
     * @return PaymentOrders
     */
    public static function createOrderById($orderId, &$serviceId, $transaction_id = '') {
        $orderOld = PaymentOrders::findOne($orderId);
        if($orderOld) {
            $paymentorder = new PaymentOrders();
            $paymentorder->attributes = $orderOld->attributes;
            $paymentorder->status = StatusEnum::STATUS_ACTIVED;
            $paymentorder->transaction_id = $transaction_id;
            $paymentorder->created_time = time();
            if ($paymentorder->save(false)) {
                $orderObjectOld = PaymentOrderObjects::findOne(['order_id' => $orderId]);
                $paymentorderobject = new PaymentOrderObjects();
                $paymentorderobject->attributes = $orderObjectOld->attributes;
                $serviceId = $orderObjectOld->object_id;
                $paymentorderobject->order_id = $paymentorder->order_id;
                $paymentorderobject->transaction_id = $transaction_id;
                $paymentorderobject->save(false);
            }

            return $paymentorder;
        } else {
            return false;
        }
    }
    
    public function getTotalMessage() {
        return CURRENCY_CODE . number_format($this->order_total,2);
    }
    
    public $object_ids;
    
    public function searchHome($get = [], $customer_id = false) {
        $query = $this->find();
        $query->select([
            'payment_orders.order_id',
            'payment_orders.transaction_id',
            'payment_orders.created_time',
            'payment_orders.order_total',
            'group_concat(payment_order_objects.object_id) as object_ids',
        ]);
        $this->load($get);
        if(isset($get['PaymentOrders']['plan_id'])) {
            $this->plan_id = $get['PaymentOrders']['plan_id'];
        }
        if(isset($get['PaymentOrders']['order_id'])) {
            $this->order_id = $get['PaymentOrders']['order_id'];
        }
        $order_id = null;
        if($this->order_id) {
            $order_id = (int)preg_replace('/ADM/','',trim($this->order_id));
            if($order_id > $this->order_id_count)
                $order_id -= $this->order_id_count;
//            $this->order_id = $order_id;
        }
        if(!$customer_id) {
            $customer_id  = user()->identity->customerid;
        }
        $query->andFilterWhere(['=','payment_orders.order_id',$order_id]);
        $query->andFilterWhere(['=','payment_orders.customer_id',$customer_id]);
        $query->andFilterWhere(['=','payment_order_objects.object_id',$this->plan_id]);
        $query->andFilterWhere(['=','payment_orders.status',  StatusEnum::STATUS_ACTIVED]);
        if($this->created_time) {
            $array = explode(' - ', $this->created_time);
            $start_time = strtotime($array[0]);
            $end_time = strtotime($array[1] . ' 23:59:59');
            $query->andFilterWhere(['>=', 'payment_orders.created_time', $start_time]);
            $query->andFilterWhere(['<=', 'payment_orders.created_time', $end_time]);
        }
        $query->join('LEFT JOIN', 'payment_order_objects', ' payment_orders.order_id = payment_order_objects.order_id ');
        $query->groupBy('payment_orders.order_id');
        $query->orderBy(['payment_orders.created_time' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }
    
    public function getPlanLabel() {
        $rs = [];
        if($this->object_ids) {
            $list = PlanSearch::getAllSelect();
            $a = explode(',',$this->object_ids);
            foreach($a as $k) {
                $rs[] = isset($list[$k]) ? $list[$k] : '';
            }
        }
        return implode(', ',$rs);
    }
    
    public function getClientId() {
        return $this->user_id + $this->order_id_count;
    }
    
    public function getInvoiceNo() {
        return 'ADM' . ($this->order_id_count + $this->order_id);
    }
    
    public static function updateTransactionId($order_id, $transaction_id) {
        $order_id = (int)$order_id;
        if($order_id && ($model = self::findOne($order_id))) {
            $model->transaction_id = $transaction_id;
            $model->status = StatusEnum::STATUS_ACTIVED;
            $model->save(false);
            $list = PaymentOrderObjects::find()->where(['order_id' => $order_id])->all();
            if($list) {
                foreach($list as $key => $item) {
                    /*@var $item PaymentOrderObjects */
                    $item->transaction_id = $transaction_id;
                    $item->save(false);
                }
            }
        }
    }

}
