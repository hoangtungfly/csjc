<?php

namespace common\models\payments;

use common\core\enums\payments\PaymentGatewayEnum;
use common\core\model\GlobalModel;
use common\core\payments\LoPayment;
use common\models\org\OrgCardsEncrypt;
use common\utilities\UtilitySystemEncrypt;
use InvalidArgumentException;
use yii\db\Query;

/**
 * PayFlowProModel class.
 */
class baseGateway extends GlobalModel {

    protected $gatewayType = '';
    protected $fieldEncrypt = array(
    );

    public function rules() {
        return array(
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
        );
    }

    public function attributeFieldType() {
        return array(
        );
    }

    /**
     * encode data
     */
    public function encodeData() {
        return json_encode($this->attributes);
    }

    /**
     * decode data
     * 
     * @param json $data
     */
    public function decodeData($data) {
        $json = json_decode($data, true);
        if (!is_array($json)) {
            throw new InvalidArgumentException('$data is invalid');
        }
        $this->attributes = $json;
        $this->setAttributes($json, false);
        return $this;
    }

    /**
     * encryp data to save database
     * 
     * @return OrgCardsEncrypt
     */
    public function encrypData() {
        $_AES = new UtilitySystemEncrypt();
        $_AES->setKey(self::getGeneralKey());
        foreach ($this->fieldEncrypt as $field) {
            if (trim($this->$field) != '') {
                $_AES->setData(trim($this->$field));
                $this->$field = $_AES->encrypt();
            }
        }
        return $this;
    }

    /**
     * decrypt data
     * 
     * @return OrgCardsEncrypt
     */
    public function decrypData() {
        $_AES = new UtilitySystemEncrypt();
        $_AES->setKey(self::getGeneralKey());
        foreach ($this->fieldEncrypt as $field) {
            if (trim($this->$field) != '') {
                $_AES->setData(trim($this->$field));
                $this->$field = $_AES->decrypt();
            }
        }
        return $this;
    }

    /**
     * @author Phong Pham Hong
     * Test gateway info
     * @param type $data
     */
    public function testGateway() {
        $model = new LoPayment();
        $model->testGateWayPayment($this->gatewayType, $this->attributes);
        if (count($model->getErrors()) > 0) {
            $this->addError('errormessage', $model->toStringErrors());
            return false;
        }
        return true;
    }

    /**
     * get key from database
     * 
     * @return type
     */
    protected static function getGeneralKey() {
        $query = new Query();
        $query->select('value')
                ->from('key_encryption')
                ->where('key_encrypt =:k', [':k' => PaymentGatewayEnum::KEY_ASE_ENCRYPT]);

        return $query->scalar();
    }

}
