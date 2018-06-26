<?php

namespace application\directory\controllers;
use application\directory\components\DirectoryController;
use common\models\category\CategoriesSearch;
use common\core\enums\CategoryEnum;
use common\utilities\UtilityFile;

class JsonController extends DirectoryController {
    public function actionConfig() {
        $result = [
            'system_settings' => $this->array_config(),
            'feeds' => CategoriesSearch::getAllCategoryRss(CategoryEnum::CATEGORY_ALIAS_NOT_ID),
        ];
        if (ANGULARJS_WRITEFILE) {
            UtilityFile::fileputcontents(DIR_LINKPUBLIC_PARTIAL . 'json/config.json', json_encode($result));
        }
        $this->jsonencode($result);
    }
}