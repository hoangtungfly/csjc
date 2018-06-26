<?php

namespace frontend\controllers\restcontrollers\product;

use common\core\action\GlobalAction;
use common\models\category\CategoriesSearch;
use common\models\product\ProductSearch;

class Productrelaction extends GlobalAction {

    public function run() {
        $id = (int) $this->getParam('id');
        $result = [];
        if ($id && ($model = ProductSearch::findOne($id))) {
            $category_id = explode(',', $model->category_id);
            $category_id = (int) $category_id[count($category_id) - 1];
            $modelCategory = CategoriesSearch::findOne($category_id);
            if ($modelCategory) {
                $level = $modelCategory->getLevel();
                $offset = ProductSearch::ProductCategoryTotal($category_id, $level, $id);
                $limit = 20;
                $w_h = [266, 0];
                
                $result = ProductSearch::ProductCategory($category_id, $level, 20, $offset, $w_h);
                if (count($result) < $limit) {
                    $not_in = [$id];
                    if ($result) {
                        foreach ($result as $key => $item) {
                            $not_in[] = $item['id'];
                        }
                    }
                    $result = array_merge($result,ProductSearch::ProductCategory($category_id, $level, $limit - count($result), 0, $w_h, $not_in));
                }
            }
        }
        return [
            'ProductRelaction' => $result,
        ];
    }

}
