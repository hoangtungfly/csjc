<?php


namespace frontend\controllers\restcontrollers\cart;

use common\core\action\GlobalAction;
use common\core\enums\CartEnum;
use common\models\cart\CartSearch;

class Viewcart extends GlobalAction {

    public function run() {
        return CartSearch::viewCart();
    }

}
