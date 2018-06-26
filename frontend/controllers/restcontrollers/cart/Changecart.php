<?php


namespace frontend\controllers\restcontrollers\cart;

use common\core\action\GlobalAction;
use common\core\enums\CartEnum;
use common\models\cart\CartSearch;

class Changecart extends GlobalAction {

    public function run() {
        $dataPost = r()->post();
        if(!session()->has(CartEnum::CART_KEY)) {
            session()->set(CartEnum::CART_KEY, md5(rand(100000, 1000000)));
        }
        if(isset($dataPost['id']) && isset($dataPost['count'])) {
            $model = CartSearch::changeCart($dataPost['id'], $dataPost['count']);
        }
        return CartSearch::viewCart();
    }

}
