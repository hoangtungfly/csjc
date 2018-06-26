<?php
/**
 *
 * @author dungnguyenanh
 */
namespace backend\modules\company\controllers;

use backend\controllers\BackendController;
use yii\filters\VerbFilter;

class CompanyController extends BackendController {
    public function actionIndex() {
        if(!isset($_GET['CompanySearch']['lang'])) {
            $_GET['CompanySearch']['lang'] = 'vi';
            r()->get();
        }
        r()->setQueryParams($_GET);
        return parent::actionIndex();
    }
}