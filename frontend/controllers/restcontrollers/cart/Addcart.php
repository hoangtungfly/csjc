<?php


namespace frontend\controllers\restcontrollers\cart;

use common\core\action\GlobalAction;
use common\core\enums\CartEnum;
use common\models\cart\CartSearch;

class Addcart extends GlobalAction {

    public function run() {
        $dataPost = r()->post();
        if(!session()->has(CartEnum::CART_KEY)) {
            session()->set(CartEnum::CART_KEY, md5(rand(1000000, 100000000)));
        }
        $result = [];
        if(isset($dataPost['id']) && isset($dataPost['count'])) {
            $model = CartSearch::addCart($dataPost['id'], $dataPost['count']);
            if($model) {
                $result['product_name'] = $model->name;
                $result['product_name']{0} = strtolower($result['product_name']{0});
            }
        }
        $result += CartSearch::viewCart();
        return $result;
    }

}
