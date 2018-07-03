<?php

namespace application\aiem\controllers;

use application\aiem\components\AiemController;
use common\core\enums\LanguageEnum;
use common\core\enums\StatusEnum;
use common\models\admin\SettingsMessageSearch;
use common\models\category\CategoriesSearch;
use common\models\company\CompanySearch;
use common\models\settings\MailSettingsSearch;
use yii\widgets\ActiveForm;
use function app;
use function session;

class MainController extends AiemController {

    public function actionIndex() {
        $alias = trim($this->getParam('alias'));
        $lang = session()->has('lang') ? session()->get('lang'): app()->language;
        switch ($lang) {
            case 'vi-VI' : 
                $lang = LanguageEnum::VI;                break;
            default :
                $lang = LanguageEnum::EN;                break;
        }
        if(!$alias) {
            $model = CategoriesSearch::find()->where(['home' => StatusEnum::STATUS_ACTIVED,'lang' => $lang])->orderBy('id desc')->one();
        } else {
//            if($alias == 'en') {
//                $model = CategoriesSearch::find()->where(['home' => StatusEnum::STATUS_ACTIVED,'lang' => LanguageEnum::EN])->orderBy('id desc')->one();
//            } 
//            else {
                $model = CategoriesSearch::findOne(['alias' => $alias]);
//            }
        }
        if (!$model) {
            $this->pageNotFound();
        }
                
        $alias = $model->alias;
        $this->alias = $model->alias;
        app()->language = $model->lang;
        session()->set('lang',app()->language);
        $this->setSeoAttribute($model->attributes);
        return $this->render('index', [
            'data' => $model->convertJsonObject($model->content),
            'model' => $model,
        ]);
    }
    
    public function actionCategory() {
        return $this->actionIndex();
    }
    
    public function action404() {
        header("HTTP/1.0 404 Not Found");
        if(session()->has('lang')) {
            app()->language = session()->get('lang');
        }
        return $this->render('404');
    }
}
