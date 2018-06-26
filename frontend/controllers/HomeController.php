<?php
namespace frontend\controllers;

use common\core\controllers\GlobalController;

class HomeController extends GlobalController {
    public function actionIndex() {
        return $this->ARender('index');
    }
    
    public function actionAbout() {
        return $this->ARender('about');
    }
    
    public function actionContact() {
        return $this->ARender('contact');
    }
    
    public function actionDashboard() {
        return $this->ARender('dashboard');
    }
    
    public function actionLogin() {
        return $this->ARender('login');
    }
    
    public function actionCategory() {
        return $this->ARender('index');
    }
    
    public function actionSavefile() {
        $file = $this->getParam('file');
        $title = $this->getParam('title');
        $content = $this->getParam('content');
        $html = $this->renderPartial('savefile',[
            'title' => $title,
            'content' => $content,
        ]);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        echo $html;
        app()->end();
    }
    
    public function actionPrintfile() {
        $file = $this->getParam('file');
        $title = $this->getParam('title');
        $content = $this->getParam('content');
        $html = $this->renderPartial('printfile',[
            'title' => $title,
            'content' => $content,
        ]);
        echo $html;
        app()->end();
    }
    
    public function actionSendmail() {
        echo $this->renderPartial('sendmail');
        app()->end();
    }
}