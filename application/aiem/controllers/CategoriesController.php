<?php

namespace application\aiem\controllers;

use application\aiem\components\AiemController;
use common\core\enums\CategoriesEnum;
use common\core\enums\StatusEnum;
use common\core\model\LinkPagerAngular;
use common\models\category\CategoriesSearch;
use common\models\news\NewsSearch;
use common\models\product\ProductSearch;

class CategoriesController extends AiemController {

    public function actionIndex() {
        $alias = $this->getParam('alias');
        $page = (int)$this->getParam('page');
        $limit = (int)$this->getParam('limit');
        if(!$limit) $limit = 30;
        if(!$page) $page = 1;
        $category_main = CategoriesSearch::findOne(['alias' => $alias]);
        if(!$category_main) {
            $this->pageNotFound();
        }
        $breakcrumbs = CategoriesSearch::getArrayByObject(CategoriesSearch::breakcrumb($category_main->id));
        $this->alias = $breakcrumbs[0]['alias'];
        $listCategoryChild = CategoriesSearch::getAllByCategoryid($category_main->id);
        $params = [
            'category_main' => $category_main,
            'breakcrumbs'   => $breakcrumbs,
            'listCategoryChild' => $listCategoryChild,
            'limit'         => $limit,
            'page'          => $page,
        ];
        switch($category_main->show_type) {
            case CategoriesEnum::CATEGORY_SHOW_CONTACT:
                $view = 'contact';
                $params['category'] = $category_main;
                $this->contact = 1;
                break;
            case CategoriesEnum::CATEGORY_SHOW_ABOUT:
                $view = 'about';
                $params['news_main'] = $category_main;
                $params['category'] = $category_main;
                break;
            case CategoriesEnum::CATEGORY_SHOW_COURSE:
                $view = 'course';
                $params['news_main'] = $category_main;
                $params['category'] = $category_main;
                break;
            default:
                switch($category_main->type) {
                    case CategoriesEnum::CATEGORY_TYPE_PRODUCT:
                        $view = 'categoryproduct';
                        $post = r()->get();
                        $modelProduct = new ProductSearch();
                        $modelProduct->unsetAttributes();
                        $modelProduct->category_id = $category_main->id;
                        $modelProduct->status = StatusEnum::STATUS_ACTIVED;
                        $modelProduct->load($post);
                        if(!$modelProduct->modified_time) {
                            $modelProduct->modified_time = 'id';
                        }
                        if(!$modelProduct->image) {
                            $modelProduct->image = 'desc';
                        }
                        list($params['list'],$params['totalPage']) = $modelProduct->searchHome($limit, $limit * ($page - 1));
                        $params['pager'] = LinkPagerAngular::run($params['totalPage'], $limit, '/'.WEBNAME.'/categories/index');
                        
                        $params['modelProduct'] = $modelProduct;
                        if($this->isAjax()) {
                            $this->jsonencode([
                                'code'  => 200,
                                'html'  => $this->renderPartial('../categories/list_product', $params),
                            ]);
                        }
                        break;
                    case CategoriesEnum::CATEGORY_TYPE_NEWS:
                        $view = 'categorynews';
                        $limit = 10;
                        $params['limit'] = $limit;
                        $listNews = NewsSearch::getListByCategoryid($category_main->id, 1, $limit, $limit * ($page - 1), false, [270,189]);
                        $params['listNews'] = $listNews;
                        $params['totalPage'] = NewsSearch::getTotalByCategoryid($category_main->id);
                        $params['pager'] = LinkPagerAngular::run($params['totalPage'], 10, '/'.WEBNAME.'/categories/index');
                        break;
                }
                break;
        }
        return $this->Prender('../categories/'.$view,$params);
    }
    
}