<?php

namespace common\models\order;

use common\core\dbConnection\GlobalActiveRecord;
use common\models\admin\SettingsMessageSearch;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property string $information
 * @property string $content
 * @property string $shipping_name
 * @property string $shipping_email
 * @property string $shipping_phone
 * @property string $shipping_address
 * @property string $payment_name
 * @property string $payment_email
 * @property string $payment_phone
 * @property string $payment_address
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $shipping_id
 * @property integer $coupon_id
 * @property double $total
 * @property double $sub_total
 * @property double $shipping_total
 * @property double $coupon_total
 * @property integer $status
 *
 * @property OrderProduct[] $orderProducts
 */
class Order extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['information', 'content'], 'string'],
            [['shipping_name', 'shipping_phone', 'shipping_address'], 'required','message' => SettingsMessageSearch::t('form','required','{attribute} không được để rỗng.')],
            [['created_time', 'modified_time', 'created_by', 'modified_by', 'shipping_id', 'coupon_id', 'status'], 'integer'],
            [['total', 'sub_total', 'shipping_total', 'coupon_total'], 'number'],
            [['shipping_name', 'shipping_email', 'shipping_phone', 'shipping_address', 'payment_name', 'payment_email', 'payment_phone', 'payment_address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'information' => 'Information',
            'content' => 'Content',
            'shipping_name' => SettingsMessageSearch::t('order','shippingname','Họ tên'),
            'shipping_email' => SettingsMessageSearch::t('order','email','Email'),
            'shipping_phone' => SettingsMessageSearch::t('order','shippingphone','Điện thoại'),
            'shipping_address' => SettingsMessageSearch::t('order','shippingaddress','Địa chỉ'),
            'payment_name' => 'Payment Name',
            'payment_email' => 'Payment Email',
            'payment_phone' => 'Payment Phone',
            'payment_address' => 'Payment Address',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'shipping_id' => 'Shipping ID',
            'coupon_id' => 'Coupon ID',
            'total' => 'Total',
            'sub_total' => 'Sub Total',
            'shipping_total' => 'Shipping Total',
            'coupon_total' => 'Coupon Total',
            'status' => 'Status',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }
}
