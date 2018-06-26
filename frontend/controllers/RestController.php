<?php
namespace frontend\controllers;

use yii\rest\ActiveController;
use yii\web\Response;

class RestController extends ActiveController {
    
    public $modelClass = 'common\models\system\SysConfig';
    
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = [ 'application/json' => Response::FORMAT_JSON];
        return $behaviors;
    }
    
    public function actions() {
        $actions = parent::actions();

        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create']);

        $actionsChildren = [
            'category' => [
                'class' => 'frontend\controllers\restcontrollers\Cate',
                'modelClass' => 'common\models\category\Category',
            ],
            'contact' => [
                'class' => 'frontend\controllers\restcontrollers\Contact',
                'modelClass' => 'common\models\system\SysContact',
            ],
            'login' => [
                'class' => 'frontend\controllers\restcontrollers\Login',
                'modelClass' => 'common\models\system\SysContact',
            ],
            'sendemail' => [
                'class' => 'frontend\controllers\restcontrollers\Sendemaillink',
                'modelClass' => 'common\models\settings\Sendemail',
            ],
            'insertrating' => [
                'class' => 'frontend\controllers\restcontrollers\Insertrating',
                'modelClass' => 'common\models\settings\Rating',
            ],
            'comment' => [
                'class' => 'frontend\controllers\restcontrollers\Comment',
                'modelClass' => 'common\models\settings\CommentSearch',
            ],
            'commentlist' => [
                'class' => 'frontend\controllers\restcontrollers\Commentlist',
                'modelClass' => 'common\models\settings\CommentSearch',
            ],
            'like' => [
                'class' => 'frontend\controllers\restcontrollers\Like',
                'modelClass' => 'common\models\settings\Thank',
            ],
            'count' => [
                'class' => 'frontend\controllers\restcontrollers\Count',
                'modelClass' => 'common\models\settings\NewsSearch',
            ],
            'countaccess' => [
                'class' => 'frontend\controllers\restcontrollers\Countaccess',
                'modelClass' => 'common\models\settings\NewsSearch',
            ],
            'addcart' => [
                'class' => 'frontend\controllers\restcontrollers\cart\Addcart',
                'modelClass' => 'common\models\settings\NewsSearch',
            ],
            'deletecart' => [
                'class' => 'frontend\controllers\restcontrollers\cart\Deletecart',
                'modelClass' => 'common\models\settings\NewsSearch',
            ],
            'viewcart' => [
                'class' => 'frontend\controllers\restcontrollers\cart\Viewcart',
                'modelClass' => 'common\models\settings\NewsSearch',
            ],
            'changecart' => [
                'class' => 'frontend\controllers\restcontrollers\cart\Changecart',
                'modelClass' => 'common\models\settings\NewsSearch',
            ],
            'proccesscart' => [
                'class' => 'frontend\controllers\restcontrollers\cart\Proccesscart',
                'modelClass' => 'common\models\settings\NewsSearch',
            ],
            'productrelaction' => [
                'class' => 'frontend\controllers\restcontrollers\product\Productrelaction',
                'modelClass' => 'common\models\settings\NewsSearch',
            ],
            'producthot' => [
                'class' => 'frontend\controllers\restcontrollers\product\Producthot',
                'modelClass' => 'common\models\settings\NewsSearch',
            ],
        ];
        $actions += $actionsChildren;
        
        return $actions;
    }

    protected function verbs() {
        return [
            'category' => ['POST', 'GET'],
            'contact' => ['POST'],
        ];
    }
}