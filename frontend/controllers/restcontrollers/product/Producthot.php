<?php

namespace frontend\controllers\restcontrollers\product;

use common\core\action\GlobalAction;
use common\models\category\CategoriesSearch;
use common\models\product\ProductSearch;

class Producthot extends GlobalAction {

    public function run() {
        return [
            'ProductHot' => ProductSearch::ProductHot(10,[266, 0]),
        ];
    }

}
