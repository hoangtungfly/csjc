<?php

/**
 *
 * @author dungnguyenanh
 */

namespace backend\modules\system\controllers;

use backend\controllers\BackendController;
use common\core\enums\CategoryEnum;
use common\core\enums\NewsEnum;
use common\models\category\CategoriesSearch;
use common\models\news\NewsSearch;

class MainController extends BackendController {

    public function actionSitemap() {
        return $this->PRender("sitemap", array(
        ));
    }

    public function actionRobot() {
        return $this->PRender("robot", array(
                    'text' => file_get_contents(APPLICATION_PATH . '/robots.txt'),
        ));
    }

    public function actionProcessrobot() {
        $text = trim($this->getParam('text'));
        file_put_contents(APPLICATION_PATH . '/robots.txt', $text);
    }

    public function actionProcesssitemap() {
        echo file_get_contents(HTTP_HOST . '/site/sitemap');
    }

}
