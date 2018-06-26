<?php

namespace application\directory\controllers;

use application\directory\components\DirectoryController;

class MainController extends DirectoryController {

    public function actionIndex() {
        return $this->ARender('index');
    }
    
    public function actionShare() {
        return $this->ARender('share');
    }
    
    public function actionUltity() {
        return $this->ARender('ultity');
    }
    
    public function actionSendmail() {
        return $this->ARender('sendmail');
    }
    
    public function actionComment() {
        return $this->ARender('comment');
    }
    
    public function actionFeeds() {
        return $this->ARender('feeds');
    }
    
    public function action404() {
        return $this->ARender('404');
    }
}