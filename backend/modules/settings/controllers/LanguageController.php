<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace backend\modules\settings\controllers;

use backend\controllers\BackendController;
use common\utilities\UtilityArray;
use common\utilities\UtilityDirectory;
use Yii;
class LanguageController extends BackendController {


    public function actionIndex() {
        $dir = str_replace("\\","/",  Yii::getAlias("@common")."/messages/");
        $dir .= LANGUAGE . '/';
        $this->process($dir);
        $arrayFile = UtilityDirectory::scandir($dir);
        $arrayDataFile = array();
        foreach($arrayFile as $key => $value) {
            $arrayDataFile[$value] = include($dir.$value);
        }
        return $this->Prender('index',array(
            'arrayDataFile'  => $arrayDataFile,
        ));
    }
    
    public function process($dir) {
        if(r()->isPost) {
            $post = $_POST;
            foreach($post as $key => $item) {
                $arrayData = json_decode($item);
                $file = $dir . str_replace('_', '.', $key);
                $content = "<?php \n return " . UtilityArray::printArray($arrayData) ."\n?>";
                file_put_contents($file,$content);
            }
        }
    }
}
