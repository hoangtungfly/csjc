<?php

namespace common\models\payments;

use yii\db\Query;
use yii\data\ActiveDataProvider;
use common\models\payments\PaymentOrders;
use common\models\services\SysServices;
use common\models\user\UserModel;

/**
 * This is the model class for table "payment_order_objects".
 *
 * @property integer $order_object_id
 * @property integer $order_id
 * @property string $transaction_id
 * @property integer $object_id
 * @property integer $object_type
 * @property double $object_price
 * @property integer $object_qty
 * @property double $object_tax
 * @property integer $created_time
 * @property string $payment_gateway
 * @property string $payment_info
 *
 * @property PaymentOrders $order
 * @property PaymentRefund[] $paymentRefunds
 */
class PaymentOrderObjects extends \common\core\dbConnection\GlobalActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'payment_order_objects';
    }

    public $user_id;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['order_id', 'object_id', 'object_qty', 'created_time'], 'integer'],
            [['object_type', 'payment_info'], 'required'],
            [['object_price', 'object_tax'], 'number'],
            [['transaction_id'], 'string', 'max' => 20],
            [['payment_gateway'], 'string', 'max' => 50],
            [['payment_info'], 'string', 'max' => 300]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'order_object_id' => 'Order Object ID',
            'order_id' => 'Order ID',
            'transaction_id' => 'Transaction ID',
            'object_id' => 'Object ID',
            'object_type' => 'Object Type',
            'object_price' => 'Object Price',
            'object_qty' => 'Object Qty',
            'object_tax' => 'Object Tax',
            'created_time' => 'Created Time',
            'payment_gateway' => 'Payment Gateway',
            'payment_info' => 'Payment Info',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder() {
        return $this->hasOne(PaymentOrders::className(), ['order_id' => 'order_id'])->from(PaymentOrders::tableName() . ' order ');
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService() {
        return $this->hasOne(SysServices::className(), ['service_id' => 'object_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderapp() {
        return $this->hasOne(PaymentOrders::className(), ['order_id' => 'order_id'])->select('order_id,payment_orders.created_time,payment_orders.payment_id,payments.payment_id')->innerJoinWith('payment');
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = PaymentOrderObjects::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->andFilterWhere([
            'order_object_id' => $this->order_object_id,
            'order_id' => $this->order_id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'object_price' => $this->object_price,
            'object_qty' => $this->object_qty,
            'object_tax' => $this->object_tax,
            'created_time' => $this->created_time,
        ]);
        $query->andFilterWhere(['like', 'transaction_id', $this->transaction_id]);

        return $dataProvider;
    }

    /**
     * @author dungnguyenanh@orenj.com
     * @param array $params
     * @return \yii\data\ActiveDataProvider
     */
    public function getDataGridViewAdvertiser(array $params) {
        $query = new Query();
        if (isset($params['PaymentOrderObjects'])) {
            $this->setAttributes($params['PaymentOrderObjects'], FALSE);
            $this->user_id = isset($params['PaymentOrderObjects']['user_id']) ? trim($params['PaymentOrderObjects']['user_id']) : null;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->select('poo.*, service.service_title, u.display_name');
        $query->from(self::tableName() . ' poo');
        $query->join('INNER JOIN', SysServices::tableName() . ' service', 'poo.object_id = service.service_id');
        $query->join('INNER JOIN', PaymentOrders::tableName() . ' po', 'po.order_id = poo.order_id');
        $query->join('INNER JOIN', UserModel::tableName() . ' u', 'u.user_id = po.user_id');
        $query->andFilterWhere([
            'poo.order_id' => $this->order_id,
            'poo.object_id' => $this->object_id,
            'poo.object_type' => $this->object_type,
            'poo.object_price' => $this->object_price,
            'poo.object_qty' => $this->object_qty,
            'poo.object_tax' => $this->object_tax,
            'poo.created_time' => $this->created_time,
        ]);

        $query->andFilterWhere(['like', 'poo.transaction_id', $this->transaction_id]);
        if ($this->user_id) {
            $query->andFilterWhere(['like', 'u.display_name', $this->user_id]);
        }

        if ($this->order_object_id) {
            $query->andFilterWhere(['like', 'service.service_title', $this->order_object_id]);
        }

        $query->orderBy(['po.created_time' => SORT_DESC]);

        return $dataProvider;
    }

    /**
     * @HuuDoan
     * Insert product
     * @param type $order_id
     * @param type $objectinfo
     * @return boolean
     */
    public function saveProductByOrder($order_id = null, $object_id = null, $price = 0, $currency = '', $payment_gateway = '') {
        if (!$order_id || !$object_id)
            return false;

        $this->order_id = $order_id;
        $this->object_id = $object_id;
        $this->object_type = 1;
        $this->object_price = $price;
        $this->object_currency = $currency;
        $this->payment_gateway = $payment_gateway;
        $this->save(false);
    }

}
