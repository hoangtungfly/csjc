<?php

namespace frontend\controllers;

use common\core\controllers\GlobalController;
use common\core\enums\WishlistEnum;
use common\models\product\WishlistSearch;

/**
 * Site controller
 */
class WishlistController extends GlobalController {

    /**
     * @inheritdoc
     */

    public function actionIndex() {
        $this->layout = '@webmain/views/layouts/main';
        $rs = WishlistSearch::viewWishlist();
        $params = $rs;
        $params['params'] = $rs;
        if (is_file(APPLICATION_PATH . '/application/' . WEB_MAIN . '/views/main/wishlist.php')) {
            return $this->render('@webmain/views/main/wishlist', $params);
        } else {
            return $this->render('index', $params);
        }
    }

    public function actionDeletedetail() {
        $dataPost = r()->post();
        if (isset($dataPost['id'])) {
            $model = WishlistSearch::deleteWishlist($dataPost['id']);
        }
        $result = WishlistSearch::viewWishlist();
        $link = APPLICATION_PATH . '/application/' . WEB_MAIN . '/views/main/wishlistdetail.php';
        $view = is_file($link) ? '@webmain/views/main/wishlistdetail' : 'wishlistdetail';
        echo $this->renderPartial($view, $result);
        die();
    }

    /**
     * get all category where status = StatusEnum::STATUS_ACTIVED
     * @return type
     */
    public function actionAdd() {
        $dataPost = r()->post();
        if (!session()->has(WishlistEnum::WISHLIST_KEY)) {
            session()->set(WishlistEnum::WISHLIST_KEY, md5(rand(1000000, 100000000)));
        }
        $result = [];
        if (isset($dataPost['id'])) {
            $model = WishlistSearch::addWishlist($dataPost['id']);
            if ($model) {
                $result['product_name'] = $model->name;
                $result['product_name']{0} = strtolower($result['product_name']{0});
            }
        }
        $result += WishlistSearch::viewWishlist();
        echo $this->renderPartial('wishlist', $result);
        die();
    }

    /**
     * remove wishlist
     * @return type
     */
    public function actionDelete() {
        $dataPost = r()->post();
        $result = [];
        if (isset($dataPost['id'])) {
            $model = WishlistSearch::deleteWishlist($dataPost['id']);
            if ($model) {
                $result['product_name'] = $model->name;
                $result['product_name']{0} = strtolower($result['product_name']{0});
            }
        }
        $result += WishlistSearch::viewWishlist();
        echo $this->renderPartial('wishlist', $result);
        die();
    }
    
    public function actionCount() {
        echo WishlistSearch::countWishlist();
        die();
    }
    
    

}
