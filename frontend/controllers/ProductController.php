<?php

namespace frontend\controllers;

use common\core\enums\CartEnum;
use common\models\product\ProductsSearch;
use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;
class ProductController extends Controller{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        
        $behaviors['verbs'] = [
           'class' => VerbFilter::className(),
            'actions' => [
                'login'  => ['post'],
                'dashboard' => ['get'],
                'regist' => ['post'],
                'contact' => ['post'],
            ],
        ];
        return $behaviors;
    }
    
    public function beforeAction($action) {
        return parent::beforeAction($action);
    }
    
    /*
     * get products by category
     */
    public function actionGetproducts(){
        $result = [
            'products' => [],
            'maxpage' => 0,
        ];
        if(Yii::$app->request->get('category_id')){
            $model_product = new ProductsSearch();
            $model_product->category_id = (int)Yii::$app->request->get('category_id');
            if(Yii::$app->request->get('get_cart')){
                $carts = Yii::$app->session->has(CartEnum::CART_KEY) ? Yii::$app->session->get(CartEnum::CART_KEY) :[] ;
                $products = $model_product->getProductsByCategory($carts);
            }
            else{
                $products = $model_product->getProductsByCategory();
            }
            $result['products'] = isset($products['products']) ? $products['products'] : [];
            $result['maxpage'] = isset($products['maxpage']) ? $products['maxpage'] : [];
        }
        return $result;
    }
}
