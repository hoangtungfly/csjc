<?php

namespace common\models\payments;

use common\core\dbConnection\GlobalActiveRecord;
use common\core\enums\payments\PaymentGatewayEnum;
use common\core\enums\StatusEnum;
use InvalidArgumentException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * This is the model class for table "payment_gateway".
 *
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $payment_key
 * @property integer $payment_status
 * @property string $payment_icon
 * @property integer $payment_order
 * @property string $payment_config
 * @property integer $created_time
 * @property integer $payment_testmode
 * @property integer $is_primary
 * @property array $payment_currency
 */
class PaymentGateway extends GlobalActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'payment_gateway';
    }

    /**
     *
     * @var payflowproGateway
     */
    public $gateWayModel;
    public $gatewayType = null;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['payment_name', 'payment_key', 'payment_currency', 'is_primary'], 'required'],
            [['payment_status', 'payment_order', 'created_time', 'payment_testmode', 'is_primary'], 'integer'],
            [['payment_config'], 'string'],
            [['payment_currency'], 'ruleValidateCurrency'],
            [['payment_name', 'payment_icon'], 'string', 'max' => 255],
            [['payment_key'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'payment_id' => 'Payment ID',
            'payment_name' => 'Payment Name',
            'payment_key' => 'Payment Key',
            'payment_status' => 'Payment Status',
            'payment_icon' => 'Payment Icon',
            'payment_order' => 'Payment Order',
            'payment_config' => 'Payment Config',
            'created_time' => 'Created Time',
            'payment_testmode' => 'Payment Testmode',
            'is_primary' => 'Is Primary',
        ];
    }

    /**
     * set gateway model
     */
    public function setGateWayModel($type) {
        switch ($type) {
            case DEFAULT_BRAINTREE_GATEWAY_KEY:
                $this->gateWayModel = new braintreeGateway();
                break;
            default :
                throw new InvalidArgumentException('Invalid ' . $type);
        }

        $this->payment_key = $type;
        $this->gatewayType = $type;
    }

    public function validatePrimaryKey($data) {
        $array = array(PaymentGatewayEnum::IS_PRIMARY, PaymentGatewayEnum::IS_NOT_PRIMARY);
        if (in_array($data, $array)) {
            return true;
        }

        return false;
    }

    /**
     * validate currency
     * 
     * @param type $attributes
     * @param type $params
     */
    public function ruleValidateCurrency($attributes, $params) {
        $currency = app()->params['payment_config']['allowed_currencies'];
        $this->getCurrencies();
        if ($this->gatewayType == DEFAULT_BRAINTREE_GATEWAY_KEY && count($this->payment_currency) > 1) {
            $this->addError('paymment_currency', 'currencies are invalid');
        } else if (array_diff($this->payment_currency, $currency)) {
            $this->addError('payment_currency', 'currencies are invalid');
        } else {
            # validate removed currencies are used on opportunites or not
            if (!$this->isNewRecord) {
                # validate primary and currencies
                $queryModel = PaymentGateway::findOne($this->payment_id);
                $queryModel->getCurrencies();
                # validate primary and currencies
            }
        }
    }

    /**
     * beforeSave 
     * @return parrent method
     */
    public function beforeSave($insert) {
        $this->setCurrencies();
        return parent::beforeSave($insert);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->andFilterWhere([
            'payment_id' => $this->payment_id,
            'payment_status' => $this->payment_status,
            'payment_order' => $this->payment_order,
            'created_time' => $this->created_time,
            'payment_testmode' => $this->payment_testmode,
            'is_primary' => $this->is_primary,
        ]);

        $query->andFilterWhere(['like', 'payment_name', $this->payment_name])
                ->andFilterWhere(['like', 'payment_key', $this->payment_key])
                ->andFilterWhere(['like', 'payment_icon', $this->payment_icon])
                ->andFilterWhere(['like', 'payment_config', $this->payment_config])
                ->andFilterWhere(['like', 'payment_currency', $this->payment_currency]);

        return $dataProvider;
    }

    /**
     * @HuuDoan
     * Get info payment gateway
     */
    public static function getGateWayPrimary() {
        $model = self::find()->where(['payment_status' => StatusEnum::STATUS_ACTIVED])->orderBy(['is_primary' => SORT_DESC])->one();
        /*@var $model PaymentGateway */
        if($model) {
            if($model->payment_currency && $model->payment_currency{0} == '[') {
                $model->payment_currency = json_decode($model->payment_currency,true);
            }
        }
        return $model;
    }

    /**
     * saving datas
     * 
     * encryp data of gatewaymodel and encode to json string 
     * @return boolen
     */
    public function savingDatas() {
        # validate gateWayModel
        if ($this->validate() && $this->gateWayModel->validate() && $this->gateWayModel->testGateway()) {
            $this->payment_config = $this->gateWayModel->encrypData()->encodeData();
            $this->payment_status = StatusEnum::STATUS_ACTIVED;
            $transaction = app()->db->beginTransaction();
            try {
                if ($this->is_primary == PaymentGatewayEnum::IS_PRIMARY) {
                    app()->db->createCommand("update " . self::tableName() . ""
                                    . " SET is_primary = " . PaymentGatewayEnum::IS_NOT_PRIMARY . ""
                                    . " AND payment_id <> " . (int) $this->payment_id)
                            ->execute();
                } elseif (!$this->checkPrimaryCardExist()) {
                    $this->is_primary = PaymentGatewayEnum::IS_PRIMARY;
                }
                $this->save(false);
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
            return true;
        }
        return false;
    }

    public function checkPrimaryCardExist() {
        $query = new Query();
        $query->select('payment_id');
        $query->from(self::tableName());
        $query->where('is_primary= :is_primary', [':is_primary' => PaymentGatewayEnum::IS_PRIMARY]);
        if (!$this->isNewRecord) {
            $query->andWhere('payment_id != :payment_id', [':payment_id' => (int) $this->payment_id]);
        }

        $q = $query->one();
        if ($q) {
            return true;
        }

        return false;
    }

    public function findByPk($id) {
        $model = self::findOne($id);
        if ($model) {
            $model->setGateWayModel($model->payment_key);
            $model->gateWayModel->decodeData($model->payment_config)->decrypData();
        }

        return $model;
    }

    /**
     * 
     * @return type
     */
    public function getCurrencies() {
        $result = $this->payment_currency;
        $syscurrencies = (isset(Yii::$app->params['payment_config']['allowed_currencies']) ? Yii::$app->params['payment_config']['allowed_currencies'] : array());
        if ($this->payment_currency && !is_array($this->payment_currency)) {
            $result = in_array($this->payment_currency, $syscurrencies) ? (array) $this->payment_currency : json_decode($this->payment_currency, false);
        }
        $this->payment_currency = is_array($result) ? $result : (isset(Yii::$app->params['payment_config']['allowed_currencies']) ? Yii::$app->params['payment_config']['allowed_currencies'] : array());
        return $this;
    }

    /**
     * 
     * @return \OrgPaymentGateway
     */
    public function setCurrencies() {
        if (is_array($this->payment_currency) && $this->payment_currency) {
            $this->payment_currency = json_encode($this->payment_currency);
        } else {
            $this->payment_currency = json_encode(array(CURRENCY_CODE));
        }
        return $this;
    }

    /**
     * config field currencies
     * 
     * choose selectbox or checkbox
     * @return string
     */
    public function configFieldCurrency($type = null) {
        $a = [
            DEFAULT_BRAINTREE_GATEWAY_KEY => 'single',
            DEFAULT_SECURPAY_GATEWAY_KEY => 'multiple',
            DEFAULT_PAYFLOWPRO_GATEWAY_KEY => 'multiple'
        ];
        if ($type !== null) {
            return $a[$type];
        }
        return $a;
    }

    /**
     * update primary card
     * @return type
     */
    public static function updateDefaultPrimaryCard() {
        return app()->db->createCommand("UPDATE payment_gateway "
                                . "SET is_primary = " . PaymentGatewayEnum::IS_PRIMARY . " LIMIT 1")
                        ->execute();
    }

}
