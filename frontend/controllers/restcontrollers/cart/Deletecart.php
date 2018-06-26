<?php


namespace frontend\controllers\restcontrollers\cart;

use common\core\action\GlobalAction;
use common\core\enums\CartEnum;
use common\models\cart\CartSearch;

class Deletecart extends GlobalAction {

    public function run() {
        $dataPost = r()->post();
        if(isset($dataPost['id'])) {
            CartSearch::deleteCart($dataPost['id']);
        }
        return CartSearch::viewCart();
    }

}
