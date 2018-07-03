<?php

namespace application\aiem\controllers;

use application\aiem\components\AiemController;
use common\models\category\CategoriesSearch;
use common\models\project\ProjectSearch;

class ProjectController extends AiemController {
    
    
    public function actionIndex() {
        $id = (int)$this->getParam('id');
        if(!$id) {
            $this->pageNotFound();
        }
        $project_main = ProjectSearch::findOne($id);
        if(!$project_main) {
            $this->pageNotFound();
        }
        $project = ProjectSearch::getObject($project_main);
        $listProject = ProjectSearch::getListByCategoryid(1, 6, 0, [$project_main->id], [218,150]);
        return $this->Prender('index', [
            'project' => $project,
            'listProject' => $listProject,
        ]);
    }
    
    public function actionList() {
        $listProject = ProjectSearch::getListProjectByLimit();
        return $this->render('list',['listProject' => $listProject]);
    }
}