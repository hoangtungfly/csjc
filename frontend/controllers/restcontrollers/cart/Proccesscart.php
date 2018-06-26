<?php


namespace frontend\controllers\restcontrollers\cart;

use common\core\action\GlobalAction;
use common\core\enums\CartEnum;
use common\models\cart\CartSearch;
use common\models\order\OrderSearch;
use common\utilities\UtilityArray;

class Proccesscart extends GlobalAction {

    public function run() {
        $result = [];
        if(r()->isPost) {
            $dataPost = r()->post();
            $model = new OrderSearch();
            $model->setScenario('frontend');
            if($model->load($dataPost) && $model->validate()) {
                $viewcart = CartSearch::viewCart();
                if(count($viewcart)) {
                    $model->sub_total = $viewcart['sub_total_float'];
                    $model->total = $viewcart['total_float'];
                    $model->save();
                    CartSearch::insertCart($model);
                    session()->set(CartEnum::CART_KEY, md5(rand(100000, 1000000)));
                }
                $result = [
                    'code' => 200,
                ];
            } else {
                $result = [
                    'code' => 400,
                    'data' => UtilityArray::jsonEncodeValidateAngular($model),
                ];
            }
        }
        return $result;
    }

}
