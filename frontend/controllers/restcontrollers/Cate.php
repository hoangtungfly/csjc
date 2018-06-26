<?php


namespace frontend\controllers\restcontrollers;

use common\core\action\GlobalAction;
use common\core\enums\StatusEnum;
use common\models\category\Category;
use common\models\product\ProductSearch;

class Cate extends GlobalAction {

    public function run() {
        $id = (int)$this->getParam('id');
        $result = [];
        $category = Category::findOne($id);
        if($category) {
            $result['category'] = $category->getAttributes();
            $model = new ProductSearch();
            $model->status = StatusEnum::STATUS_ACTIVED;
            $query = ProductSearch::find();
            $query->select('id,name,price,image');
            $query->innerJoinWith('categoryProduct');
            $query->andFilterWhere(['=', 'category_product.category_id', $id]);
            $dataProvider = $model->search($query);
            $result['count'] = $dataProvider->getCount();
            $result['totalCount'] = $dataProvider->getTotalCount();
            $result['models'] = $dataProvider->getModels();
            $result['pagination'] = $dataProvider->getPagination();
            $result['sort'] = $dataProvider->getSort();
            $result['keys'] = $dataProvider->getKeys();
        }
        return $result;
    }

}
