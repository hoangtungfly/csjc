<?php

namespace application\aiem\controllers;

use application\aiem\components\AiemController;
use common\models\category\CategoriesSearch;
use common\models\news\NewsSearch;

class NewsController extends AiemController {
    
    
    public function actionIndex() {
        $id = (int)$this->getParam('id');
        if(!$id) {
            $this->pageNotFound();
        }
        $news_main = NewsSearch::findOne($id);
        if(!$news_main) {
            $this->pageNotFound();
        }
        $news = NewsSearch::getObject($news_main);
//        $category = CategoriesSearch::findOne($news_main->category_id);
//        $listNews = $category ? NewsSearch::getListByCategoryid($category['id'], 1, 6, 0, [$news_main->id], [218,150]) : false;
//        $tags = $news_main->tags ? explode(',', $news_main->tags) : false;
        return $this->Prender('index', [
//            'news_main' => $news_main,
//            'category'  => $category,
            'news' => $news,
        ]);
    }
    public function actionList() {
        $listNews = NewsSearch::getListNewsNewByLimit();
        return $this->render('list',['listNews' => $listNews]);
    }
}