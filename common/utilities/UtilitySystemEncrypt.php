<?php

/**
 * Encrypt/Decrupt data for payment
 * 
 * @authro HuuDoan
 * @version 1.0
 * @date 08/28/2015
 */

namespace common\utilities;

use common\utilities\AES\AES;

class UtilitySystemEncrypt extends AES {

    const ENC_BLOCK_SIZE = 256;

    public $debug = false;

    /**
     * contructor
     * 
     * @param string $data
     * @param string $key
     * @return parent __contruct
     */
    public function __construct($data = null) {
        $blockSize = self::ENC_BLOCK_SIZE;
        $this->getKeyFromStorage();
        return parent::__construct($data, $this->key, $blockSize);
    }

    /**
     * get private key from private storate file
     */
    protected function getKeyFromStorage() {
        $this->key = '';
    }

    public function encrypt() {
        $this->debug();
        return parent::encrypt();
    }

    public function decrypt() {
        $this->debug();
        return parent::decrypt();
    }

    /**
     * debug
     */
    public function debug() {
        if ($this->debug) {
            var_dump(get_object_vars($this));
        }
    }

}
