<?php

namespace frontend\controllers;

use common\core\controllers\GlobalController;
use common\core\enums\ProductcompareEnum;
use common\models\product\ProductCompareSearch;

/**
 * Site controller
 */
class ProductcompareController extends GlobalController {

    /**
     * @inheritdoc
     */

    public function actionIndex() {
        $this->layout = '@webmain/views/layouts/main';
        $rs = ProductCompareSearch::viewProductCompare([125,125]);
        $params = $rs;
        $params['params'] = $rs;
        if (is_file(APPLICATION_PATH . '/application/' . WEB_MAIN . '/views/main/productcompare.php')) {
            return $this->render('@webmain/views/main/productcompare', $params);
        } else {
            return $this->render('index', $params);
        }
    }

    public function actionDeletedetail() {
        $dataPost = r()->post();
        if (isset($dataPost['id'])) {
            $model = ProductCompareSearch::deleteProductCompare($dataPost['id']);
        }
        $result = ProductCompareSearch::viewProductCompare([125,125]);
        $link = APPLICATION_PATH . '/application/' . WEB_MAIN . '/views/main/productcomparedetail.php';
        $view = is_file($link) ? '@webmain/views/main/productcomparedetail' : 'productcomparedetail';
        echo $this->renderPartial($view, $result);
        die();
    }

    /**
     * get all category where status = StatusEnum::STATUS_ACTIVED
     * @return type
     */
    public function actionAdd() {
        $dataPost = r()->post();
        if (!session()->has(ProductcompareEnum::PRODUCTCOMPARE_KEY)) {
            session()->set(ProductcompareEnum::PRODUCTCOMPARE_KEY, md5(rand(1000000, 100000000)));
        }
        $result = [];
        if (isset($dataPost['id'])) {
            $model = ProductCompareSearch::addProductCompare($dataPost['id']);
            if ($model) {
                $result['product_name'] = $model->name;
                $result['product_name']{0} = strtolower($result['product_name']{0});
            }
        }
        $result += ProductCompareSearch::viewProductCompare([125,125]);
        echo $this->renderPartial('productcompare', $result);
        die();
    }

    /**
     * remove product_compare
     * @return type
     */
    public function actionDelete() {
        $dataPost = r()->post();
        $result = [];
        if (isset($dataPost['id'])) {
            $model = ProductCompareSearch::deleteProductCompare($dataPost['id']);
            if ($model) {
                $result['product_name'] = $model->name;
                $result['product_name']{0} = strtolower($result['product_name']{0});
            }
        }
        $result += ProductCompareSearch::viewProductCompare([125,125]);
        echo $this->renderPartial('productcompare', $result);
        die();
    }
    
    public function actionCount() {
        echo ProductCompareSearch::countProductCompare();
        die();
    }
    
    

}
