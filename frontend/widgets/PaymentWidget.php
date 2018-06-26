<?php

namespace frontend\widgets;

use yii\base\Widget;

/**
 * @author HuuDoan
 * @date 24/08/2015
 * widget return form of payment method
 */
class PaymentWidget extends Widget {

    public $model = null;
    public $totalAmount = 1;
    public $serviceInfo = null;
    public $booking = null;
    public $result_id = '';

    /**
     * run and return form
     */
    public function run() {
        return $this->render('lopayment', [
                    'model' => $this->model
                    , 'totalAmount' => $this->totalAmount
                    , 'result_id'=> $this->result_id
        ]);
    }

}
