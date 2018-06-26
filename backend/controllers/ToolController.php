<?php

namespace backend\controllers;

use common\core\controllers\GlobalController;
use common\core\enums\StatusEnum;
use common\models\admin\SettingsCronSearch;
use common\models\admin\SettingsImages;
use common\models\news\NewsSearch;
use common\models\product\ProductSearch;
use common\models\settings\TagsSearch;
use common\utilities\UtilityDirectory;
use common\utilities\UtilityHtmlFormat;
use Yii;

/**
 * Site controller
 */
//class SiteController extends MemberBaseController
class ToolController extends GlobalController {
    public function actionTestglob() {
        $cacheFile = Yii::getAlias('@cache') . '/file/mapping_settings_table*.bin';
        $files = glob($cacheFile);
        var_dump($files, $cacheFile);
        die();
    }
    
    public function actionLoadtxt() {
        echo file_get_contents(APPLICATION_PATH . '/log/log_cron.txt');
    }
    
    public function actionUpdatetags() {
        TagsSearch::updateTags();
    }
    
    public function actionVideo() {
        echo $this->renderPartial('video');
    }
    
    public function actionTesterror() {
        echo $a;
    }
    
    public function actionUpdatenews() {
//        set_time_limit(1000000);
        $item = NewsSearch::find()->select('id,content')->where('id = 3270')->one();
        $result = $item->content;
        $result = preg_replace("/<p>\(<a class='link_cat'[^~]+?(<\/p>)/",'',$result);
        $result = preg_replace('/<p><span id="more-[^~]+?(<\/p>)/','',$result);
        echo $result;die();
//        $data = [];
//        foreach($list as $key => $news) {
//            if ($news->category_id != "") {
//                $arrayCategoryId = explode(",", $news->category_id);
//                foreach ($arrayCategoryId as $key => $value) {
//                    $data[] = [$value, $news->id, time(), user()->id];
//                }
//            }
//        }
//        Yii::$app->db->createCommand()->batchInsert(CategoryNews::tableName(), ['category_id', 'news_id', 'created_time', 'created_by'], $data)->execute();
    }
    
    public function actionCronall() {
        $list = SettingsCronSearch::find()->where(['status' => StatusEnum::STATUS_ACTIVED,'table_name' => 'news'])->all();
        foreach($list as $item) {
            $item->content_log = '';
            $item->cronAll();
        }
    }
    
    public function actionCronone() {
        $id = (int)$this->getParam('id');
        $item = SettingsCronSearch::findOne($id);
        if($item && $item->status) {
            $item->content_log = '';
            $item->cronAll();
        }
    }
    
    public function actionUpdateimage() {
        $list = NewsSearch::find()->all();
        if($list) {
            foreach($list as $item) {
//                $item
            }
        }
    }
    
    public function actionCopydirectory() {
        UtilityDirectory::copyAndReplaceDirectory('dogo', 'noithat');
        die();
    }
    
    public function actionAddproduct() {
        set_time_limit(100000000);
        $dir = 'http://localhost/WEB/';
        $list_dir = getLinkContentDir($dir);
        foreach ($list_dir as $key => $dir_name1) {
            $category_id = 0;
            if ($dir_name1 == 'Kientruc/') {
                $category_id = 1;
                continue;
            }
            if ($dir_name1 == 'Noi%20that/') {
                $category_id = 2;
            }
            if ($category_id) {
                $dir2 = $dir . $dir_name1 ;
                $list_dir_product = getLinkContentDir($dir2);
                foreach ($list_dir_product as $key => $value) {
                    $alias = UtilityHtmlFormat::stripUnicode($value);
                    if(!($model = ProductSearch::findOne(['alias' => $alias]))) {
                        $dir_product = $dir2 . $value;
                        $model = new ProductSearch();
                        $model->category_id = (string)$category_id;
                        $model->name = trim(preg_replace('/[ ]+/',' ',str_replace(['-','/'],[' ',' '],urldecode($value))));
                        $list_image = getLinkContentDir($dir_product);
                        $images = [];
                        
                        if($list_image) {
                            foreach($list_image as $k_image => $v_image) {
                                if(preg_match('~\.(jpg|png)$~',  strtolower($v_image))) {
                                    $value_link = $dir_product . $v_image;
                                    $array = SettingsImages::updateImageByLink($value_link, 'product', false, false);
                                    if(!$model->image && isset($array['name'])) {
                                        $model->image = $array['name'];
                                    } else {
                                        $images[] = $array;
                                    }
                                }
                            }
                            $model->images = json_encode($images);
                            $model->save();
//                            die();
                        }
                    }
                }
            }
        }
    }
} 