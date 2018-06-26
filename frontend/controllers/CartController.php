<?php

namespace frontend\controllers;

use common\core\controllers\GlobalController;
use common\core\enums\CartEnum;
use common\models\cart\CartSearch;
use common\models\order\OrderSearch;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class CartController extends GlobalController {

    /**
     * @inheritdoc
     */

    public function actionIndex() {
        $this->layout = '@webmain/views/layouts/main';
        $rs = CartSearch::viewCart();
        $params = $rs;
        $params['params'] = $rs;
        if (is_file(APPLICATION_PATH . '/application/' . WEB_MAIN . '/views/main/cart.php')) {
            return $this->render('@webmain/views/main/cart', $params);
        } else {
            return $this->render('index', $params);
        }
    }

    public function actionEditdetail() {
        $dataPost = r()->post();
        if (!session()->has(CartEnum::CART_KEY)) {
            session()->set(CartEnum::CART_KEY, md5(rand(1000000, 100000000)));
        }
        
        if (isset($dataPost['id']) && isset($dataPost['count'])) {
            $model = CartSearch::changeCart($dataPost['id'], $dataPost['count']);
        }
        $result = CartSearch::viewCart();
        $link = APPLICATION_PATH . '/application/' . WEB_MAIN . '/views/main/cartdetail.php';
        $view = is_file($link) ? '@webmain/views/main/cartdetail' : 'cartdetail';
        echo $this->renderPartial($view, $result);
        die();
    }
    
    public function actionDeletedetail() {
        $dataPost = r()->post();
        if (isset($dataPost['id'])) {
            $model = CartSearch::deleteCart($dataPost['id']);
        }
        $result = CartSearch::viewCart();
        $link = APPLICATION_PATH . '/application/' . WEB_MAIN . '/views/main/cartdetail.php';
        $view = is_file($link) ? '@webmain/views/main/cartdetail' : 'cartdetail';
        echo $this->renderPartial($view, $result);
        die();
    }

    /**
     * get all category where status = StatusEnum::STATUS_ACTIVED
     * @return type
     */
    public function actionAdd() {
        $dataPost = r()->post();
        if (!session()->has(CartEnum::CART_KEY)) {
            session()->set(CartEnum::CART_KEY, md5(rand(1000000, 100000000)));
        }
        $result = [];
        if (isset($dataPost['id']) && isset($dataPost['count'])) {
            $model = CartSearch::addCart($dataPost['id'], $dataPost['count']);
            if ($model) {
                $result['product_name'] = $model->name;
                $result['product_name']{0} = strtolower($result['product_name']{0});
            }
        }
        $result += CartSearch::viewCart();
        echo $this->renderPartial('cart', $result);
        die();
    }

    /**
     * update cart
     * @return type
     */
    public function actionEdit() {
        $dataPost = r()->post();
        if (!session()->has(CartEnum::CART_KEY)) {
            session()->set(CartEnum::CART_KEY, md5(rand(100000, 1000000)));
        }
        $result = [];
        if (isset($dataPost['id']) && isset($dataPost['count'])) {
            $model = CartSearch::changeCart($dataPost['id'], $dataPost['count']);
            if ($model) {
                $result['product_name'] = $model->name;
                $result['product_name']{0} = strtolower($result['product_name']{0});
            }
        }
        $result += CartSearch::viewCart();
        echo $this->renderPartial('cart', $result);
        die();
    }

    /**
     * remove cart
     * @return type
     */
    public function actionDelete() {
        $dataPost = r()->post();
        $result = [];
        if (isset($dataPost['id'])) {
            $model = CartSearch::deleteCart($dataPost['id']);
            if ($model) {
                $result['product_name'] = $model->name;
                $result['product_name']{0} = strtolower($result['product_name']{0});
            }
        }
        $result += CartSearch::viewCart();
        echo $this->renderPartial('cart', $result);
        die();
    }
    
    public function actionProccess() {
        if(r()->isPost) {
            $dataPost = r()->post();
            $model = new OrderSearch();
//            $model->setScenario('frontend');
            if($model->load($dataPost) && $model->validate()) {
                $viewcart = CartSearch::viewCart();
                if(count($viewcart)) {
                    $model->sub_total = $viewcart['sub_total_float'];
                    $model->total = $viewcart['total_float'];
                    $model->save();
                    CartSearch::insertCart($model);
                    session()->set(CartEnum::CART_KEY, md5(rand(100000, 1000000)));
                }
                $this->jsonResponse(200);
            } else {
                echo json_encode(ActiveForm::validate($model));
                app()->end();
            }
        }
    }
    
    public function actionCount() {
        $this->jsonencode([
            'count' => CartSearch::countCart(),
            'price' => CartSearch::priceCart(),
        ]);
        die();
    }
    
    

}
