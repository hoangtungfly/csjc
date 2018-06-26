<?php

namespace common\models\admin;

use common\core\enums\StatusEnum;
use common\core\enums\web\SettingsWebTemplateEnum;
use common\core\model\CopywebHtml;
use common\models\web\SettingsWebTemplateSearch;
use common\utilities\UtilityArray;
use common\utilities\UtilityDirectory;
use common\utilities\UtilityFile;
use common\utilities\UtilityHtmlFormat;
use Yii;

class SettingsWebcronSearch extends SettingsWebcron {
    
    public $array_template = [];
    
    public function cron() {
        /* write web into public directory */
        set_time_limit(30000);
        $arrayObjectFile = $this->link ? json_decode($this->link,true) : [];
        if (count($arrayObjectFile)) {
            $copyweb = false;
            foreach ($arrayObjectFile as $key => $object) {
                if(!($model = CopywebSearch::findOne(['linkweb' => $object['link']]))) {
                    $model = new CopywebSearch();
                    $model->linkweb = $object['link'];
                }
                $model->directory = $this->directory;
                if(!trim($object['content'])) {
                    $object['content'] = filegetcontents($object['link']);
                }
                $model->content_html = $object['content'];
                $model->filename = $object['filename'];
                $model->save();
                $model->copyweb();
            }
            $this->content_file = json_encode($arrayObjectFile);
        }
    }
    
    function writeAssets() {
        $linkAssets = Yii::getAlias('@application') . '/' . $this->directory . '/assets/AppAsset.php';
        if (is_file($linkAssets)) {
            return false;
        }
        $linkFileIndex = Yii::getAlias('@application') . '/' . $this->directory . '/public/index.html';
        if (!is_file($linkFileIndex))
            return false;
        
        $content = UtilityFile::getFileInWeb($linkFileIndex);
        $arrayCss = UtilityHtmlFormat::getLinkCssByContent($content);
        $arrayJs = UtilityArray::addArray(['app/app.js','app/directive.js','app/controller.js',
                            ], UtilityHtmlFormat::getLinkJsByContent($content));
        
        
        if (!is_file($linkAssets)) {
            $listDir = UtilityDirectory::scandir(Yii::getAlias('@application'));
            list($dir, $linkAssetsCopy) = UtilityDirectory::getFileAssetsFirst();
            if (!$linkAssetsCopy)
                return false;
            $content = UtilityFile::replaceContentFileByDir($this->directory, $dir, $linkAssetsCopy);
            $content = preg_replace('/public \$css = \[([^©]+?(\];))/', 'public \$css = [ ];', $content);
            $content = preg_replace('/public \$js = \[([^©]+?(\];))/', 'public \$js = [ ];', $content);
            UtilityFile::fileputcontents($linkAssets, $content);
        }
        $contentPhp = UtilityFile::getFileInWeb($linkAssets);
        $arraySearch = array();
        $arrayReplace = array();
        $this->getContentCssJs($contentPhp, 'css', $arraySearch, $arrayReplace, $arrayCss);
        $this->getContentCssJs($contentPhp, 'js', $arraySearch, $arrayReplace, $arrayJs);
        $contentPhp = str_replace($arraySearch, $arrayReplace, $contentPhp);
        UtilityFile::fileputcontents($linkAssets, $contentPhp);
    }

    public function getContentCssJs($contentPhp, $tag, &$arraySearch, &$arrayReplace, $arrayCss) {
        preg_match_all('/public \$' . $tag . ' = \[([^©]+?(\];))/', $contentPhp, $arrayStringCss);
        if (isset($arrayStringCss[1]) && isset($arrayStringCss[1][0])) {
            $contentCss = preg_replace('/,$/', '', trim(str_replace(['];', "'", '"'], '', $arrayStringCss[1][0])));
            $result = array_keys(array_flip(UtilityArray::addArray(array_values(UtilityArray::trim(explode(',', $contentCss), true)), $arrayCss)));
            if ($tag == 'css') {
                $this->addFileCss($result);
            }
            $arraySearch[] = "public \${$tag} = [" . $arrayStringCss[1][0];
            $arrayReplace[] = "public \${$tag} = [\n\t'" . implode("',\n\t'", $result) . "'\n\t];";
        }
    }

    public function addFileCss(&$result) {
        $result[] = 'css/dev.css';
        /* dir layouts */
        $linkCss = Yii::getAlias('@application') . '/' . $this->directory . '/public/css/dev.css';
        if (!is_file($linkCss)) {
            UtilityFile::fileputcontents($linkCss, '');
        }
    }

//    /*Ghi file vào thư mục component*/
    function writeComponent() {
        $name = updateUpperFirstCharacter($this->directory) . 'Controller.php';
        $linkFileComponents = Yii::getAlias('@application') . '/' . $this->directory . '/components/' . $name;
        if (!is_file($linkFileComponents)) {
            list($dir, $linkComponent) = UtilityDirectory::getFileComponentsFirst();
            if (!$linkComponent)
                return false;
            $content = UtilityFile::replaceContentFileByDir($this->directory, $dir, $linkComponent);
            UtilityFile::fileputcontents($linkFileComponents, $content);
        }
    }

    /* Ghi file vào thư mục controller */

    function writeController() {
        $linkJsonController = Yii::getAlias('@application') . '/' . $this->directory . '/controllers/JsonController.php';
        $linkMainController = Yii::getAlias('@application') . '/' . $this->directory . '/controllers/MainController.php';
        if (!is_file($linkJsonController)) {
            $content = UtilityFile::replaceContentFileByDir($this->directory, 'directory', Yii::getAlias('@common') . '/generator/JsonController.php');
            UtilityFile::fileputcontents($linkJsonController, $content);
        }
        if (!is_file($linkMainController)) {
            $content = UtilityFile::replaceContentFileByDir($this->directory, 'directory', Yii::getAlias('@common') . '/generator/MainController.php');
            UtilityFile::fileputcontents($linkMainController, $content);
        }
    }

    /* Ghi file module */

    function writeModule() {
        $linkModule = Yii::getAlias('@application') . '/' . $this->directory . '/' . updateUpperFirstCharacter($this->directory) . 'Module.php';
        if (!is_file($linkModule)) {
            list($dir, $linkModuleOld) = UtilityDirectory::getFileModuleFirst();
            if (!$linkModuleOld)
                return false;
            $content = UtilityFile::replaceContentFileByDir($this->directory, $dir, $linkModuleOld);
            UtilityFile::fileputcontents($linkModule, $content);
        }
    }

    /* Ghi file vào main */

    public function writeMain() {
        $linkMain = Yii::getAlias('@application') . '/' . $this->directory . '/main.php';
        $content = UtilityFile::getFileInWeb(Yii::getAlias('@common') . '/generator/main.php');

        $content = str_replace('//replace', $this->getRewriteStrMain(), $content);
        UtilityFile::fileputcontents($linkMain, $content);
    }

    public function getRewriteStrMain() {
        $strl = '';
        if ($this->rewrite) {
            $array_json = json_decode($this->rewrite);
            $arrayReplace = [];
            if ($array_json && count($array_json)) {
                foreach ($array_json as $item) {
                    if ($item->linkrewrite && $item->link) {
                        $arrayReplace[] = "\$alias . '" . $item->linkrewrite . "' => WEBNAME . '" . $item->link . "'";
                        if (preg_match('~/$~', $item->linkrewrite)) {
                            $arrayReplace[] = "['pattern' => \$alias.'" . $item->linkrewrite . "', 'route' => WEBNAME . '" . $item->link . "', 'suffix' => '/']";
                        }
                    }
                }
            }
            if (count($arrayReplace)) {
                $strl = implode(",\n\t", $arrayReplace);
            }
        }
        return $strl;
    }

    /* Ghi file vào views */

    public function writeViews() {
        /* dir json */
        $linkRssPhp = Yii::getAlias('@application') . '/' . $this->directory . '/views/json/rss.php';
        if (!is_file($linkRssPhp)) {
            $content = UtilityFile::replaceContentFileByDir($this->directory, 'directory', Yii::getAlias('@common') . '/generator/json/rss.php');
            UtilityFile::fileputcontents($linkRssPhp, $content);
        }
        $linkXmlPhp = Yii::getAlias('@application') . '/' . $this->directory . '/views/json/xml.php';
        if (!is_file($linkXmlPhp)) {
            $content = UtilityFile::replaceContentFileByDir($this->directory, 'directory', Yii::getAlias('@common') . '/generator/json/xml.php');
            UtilityFile::fileputcontents($linkXmlPhp, $content);
        }

        /* dir layouts */
        $linkMainPhp = Yii::getAlias('@application') . '/' . $this->directory . '/views/layouts/main.php';
        if (!is_file($linkMainPhp)) {
            $content = UtilityFile::replaceContentFileByDir($this->directory, 'directory', Yii::getAlias('@common') . '/generator/layouts/main.php');
            
            $linkFileIndex = Yii::getAlias('@application') . '/' . $this->directory . '/public/index.html';
            $contentFileIndex = UtilityFile::getFileInWeb($linkFileIndex);
            if($contentFileIndex && ($contentBodyFileIndex = UtilityHtmlFormat::getContentBody($contentFileIndex))) {
                $contentBodyFileIndex = str_replace(['src="',"src='"],['src="' . LINK_PUBLIC,"src='" . LINK_PUBLIC],$contentBodyFileIndex);
                $content = str_replace('<ng-view></ng-view>',$contentBodyFileIndex,$content);
            }
            
            $this->layout = $content;
            UtilityFile::fileputcontents($linkMainPhp, $content);
        }

        $linkHeadPhp = Yii::getAlias('@application') . '/' . $this->directory . '/views/layouts/partials/head.php';
        if (!is_file($linkHeadPhp)) {
            $content = UtilityFile::replaceContentFileByDir($this->directory, 'directory', Yii::getAlias('@common') . '/generator/layouts/partials/head.php');
            $this->head = $content;
            UtilityFile::fileputcontents($linkHeadPhp, $content);
        }
        $listFile = UtilityDirectory::scandir(Yii::getAlias('@application') . '/' . $this->directory . '/views/main/');
        if($listFile) {
            foreach($listFile as $file) {
                $linkFilePhp = Yii::getAlias('@application') . '/' . $this->directory . '/views/main/'.$file;
                if (!is_file($linkFilePhp)) {
                    $content = UtilityFile::replaceContentFileByDir($this->directory, 'directory', Yii::getAlias('@common') . '/generator/main/'.$file);
                    $this->array_template['404']['content_html'] = $content;
                    UtilityFile::fileputcontents($linkFilePhp, $content);
                }
            }
        }
    }

    /* Ghi file vào app */

    public function writeApp() {
        $linkAppJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/app.js';
        if (!is_file($linkAppJs)) {
            $content = UtilityFile::getFileInWeb(Yii::getAlias('@common') . '/generator/app/app.js');
        } else {
            $content = UtilityFile::getFileInWeb($linkAppJs);
            $content = preg_replace("/controller\: 'MainController'\}\)\.[^~]+?(when\(ALIAS \+ '\:alias\.rss', \{)/", "controller: 'MainController'}).
                whenroutereplace.
                when(ALIAS + '/:alias.rss', {", $content);
        }
        $content = str_replace('whenroutereplace', $this->getRewriteStrApp(), $content);
        UtilityFile::fileputcontents($linkAppJs, $content);

        $linkControllerJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/controller.js';
        if (!is_file($linkControllerJs)) {
            $content = UtilityFile::getFileInWeb(Yii::getAlias('@common') . '/generator/app/controller.js');
            UtilityFile::fileputcontents($linkControllerJs, $content);
            $this->array_template['index']['content_js'] = UtilityHtmlFormat::getFunctionControllerApp($content, 'main');
        }

        $linkDirectiveJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/directive.js';
        if (!is_file($linkDirectiveJs)) {
            UtilityFile::fileputcontents($linkDirectiveJs, '');
        }

        $this->updateFunctionControllerApp();
        $this->createFileViewControllerApp();
        
        $this->updateFunctionDirectiveApp();
        $this->createFileViewDriectiveApp();
        
        $this->createFunctionIndexApp();
        $this->createFunctionJsonApp();
        $this->addWebTemplateByRewriteLink();
    }

    /* Tạo các file view ứng với controller app */

    public function createFileViewControllerApp() {
        $this->createFileView('index');
        if ($this->rewrite) {
            $array_json = json_decode($this->rewrite);
            if ($array_json && count($array_json)) {
                foreach ($array_json as $item) {
                    if ($item->linkrewrite && $item->link) {
                        $file = UtilityHtmlFormat::getStrEndSource($item->link);
                        $this->createFileView($file);
                    }
                }
            }
        }
    }

    /* Tạo các file view ứng với directive app */

    public function createFileViewDriectiveApp() {
        $this->createFileView('header');
        $this->createFileView('footer');
    }

    public function createFileView($file, $content = '') {
        $linkFile = Yii::getAlias('@application') . '/' . $this->directory . '/views/main/' . $file . '.php';
        if (!is_file($linkFile)) {
            UtilityFile::fileputcontents($linkFile, $content);
        }
    }
    
    public function deleteFileView($file) {
        $linkFile = Yii::getAlias('@application') . '/' . $this->directory . '/views/main/' . $file . '.php';
        if (is_file($linkFile)) {
            UtilityFile::deleteFile($linkFile);
        }
    }

    public function createFunctionJsonApp() {
        $function_content_template = "    public function actiontemplate() {\n        {param}\$this->jsonencode([\n        ]);\n    }";
        $link_controller_json = Yii::getAlias('@application') . '/' . $this->directory . '/controllers/JsonController.php';

        $array_file = ['indexjson','headerjson', 'footerjson'];

        if ($this->rewrite) {
            $array_json = json_decode($this->rewrite);
            if ($array_json && count($array_json)) {
                foreach ($array_json as $item) {
                    if ($item->linkrewrite && $item->link) {
                        $file = UtilityHtmlFormat::getStrEndSource($item->link);
                        if ($file != '404') {
                            
                            preg_match_all('/<([a-zA-Z]+):/',$item->linkrewrite,$params);
                            if(is_array($params) && isset($params[1])) {
                                $html_param = '';
                                foreach($params[1] as $param) {
                                    $html_param .= "\$$param = \$this->getParam('$param');\n        ";
                                    $this->processParams($param,$html_param);
                                }
                                $this->processModelParam($file, $html_param);
                                $array_file[] = [$file.'json',$html_param];
                            } else {
                                $array_file[] = $file.'json';
                            }
                        }
                    }
                }
            }
        }
        foreach ($array_file as $file) {
            $html_param = '';
            if(is_array($file)) {
                $html_param = $file[1];
                $file = $file[0];
            }
            $fileUpper = updateUpperFirstCharacter($file);
            $contentFunctionWrite = str_replace(['template','{param}'],[$fileUpper,$html_param], $function_content_template);
            $this->array_template[str_replace('json','',$file)]['content_php'] = $contentFunctionWrite;
            $this->updateFunctionActionController($link_controller_json, $fileUpper, $contentFunctionWrite);
        }
    }
    
    public function processParams($param, &$html_param) {
        switch($param) {
            case 'page':
                $html_param .= "if(!\$page) \$page = 1;\n        ";
                $html_param .= "\$limit = PAGESIZE;\n        ";
                $html_param .= "\$offset = \$limit * (\$page - 1);\n        ";
                break;
        }
    }
    
    public function processModelParam($file, &$html_param) {
        switch($file) {
            case 'categories' : 
                $html_param .= "\$model = \common\models\category\CategoriesSearch::findOne(['alias' => \$alias]);\n        ";
                $html_param .= "if(!\$model){return false;}\n        ";
                $html_param .= "\$category_id = \$model->id;\n        ";
                $html_param .= "\$level = \$model->getLevel();\n        ";
                break;
            case 'product' :
                $html_param .= "\$model = \common\models\product\ProductsSearch::findOne(\$pid);\n        ";
                break;
            case 'news' : 
                $html_param .= "\$model = \common\models\news\NewsSearch::findOne(\$id);\n        ";
                break;
            case 'tag' : 
                $html_param .= "\$model = \common\models\settings\TagsSearch::findOne(\$tag);\n        ";
                break;
        }
    }
    
    public function createFunctionActionjsonController($file) {
        $link_controller_json = Yii::getAlias('@application') . '/' . $this->directory . '/controllers/JsonController.php';
        $function_content_template = "public function actiontemplate() {\n\t\$this->jsonencode([\n\t]);\n\t}";
        $fileUpper = updateUpperFirstCharacter($file.'json');
        $contentFunctionWrite = str_replace('template', $fileUpper, $function_content_template);
        $this->updateFunctionActionController($link_controller_json, $fileUpper, $contentFunctionWrite);
        return $contentFunctionWrite;
    }
    
    public function deleteFunctionActionjsonController($file) {
        $fileUpper = updateUpperFirstCharacter($file);
        $link_controller_json = Yii::getAlias('@application') . '/' . $this->directory . '/controllers/JsonController.php';
        $this->updateFunctionActionController($link_controller_json, $fileUpper.'json', '', true);
    }

    public function createFunctionIndexApp() {
        $function_content_template = "    public function actiontemplate() {\n        return \$this->ARender('trender');\n    }";
        $link_controller_index = Yii::getAlias('@application') . '/' . $this->directory . '/controllers/MainController.php';

        $array_file = ['index','header', 'footer'];

        if ($this->rewrite) {
            $array_json = json_decode($this->rewrite);
            if ($array_json && count($array_json)) {
                foreach ($array_json as $item) {
                    if ($item->linkrewrite && $item->link) {
                        $file = UtilityHtmlFormat::getStrEndSource($item->link);
                        $array_file[] = $file;
                    }
                }
            }
        }

        foreach ($array_file as $file) {
            $fileUpper = updateUpperFirstCharacter($file);
            $contentFunctionWrite = str_replace(['template','trender'], [$fileUpper,$file], $function_content_template);
            $this->updateFunctionActionController($link_controller_index, $fileUpper, $contentFunctionWrite);
        }
    }
    
    public function createFunctionActionindexController($file) {
        $function_content_template = "    public function actiontemplate() {\n        return \$this->ARender('trender');\n    }";
        $link_controller_index = Yii::getAlias('@application') . '/' . $this->directory . '/controllers/MainController.php';
        $fileUpper = updateUpperFirstCharacter($file);
        $contentFunctionWrite = str_replace(['template','trender'], [$fileUpper,$file], $function_content_template);
        $this->updateFunctionActionController($link_controller_index, $fileUpper, $contentFunctionWrite);
    }
    
    public function deleteFunctionActionindexController($file) {
        $link_controller_index = Yii::getAlias('@application') . '/' . $this->directory . '/controllers/MainController.php';
        $fileUpper = updateUpperFirstCharacter($file);
        $this->updateFunctionActionController($link_controller_index, $fileUpper, '', true);
    }

    public function updateFunctionActionController($link, $actionjson, $contentFunctionWrite, $type = false) {
        $contentFile = filegetcontents($link);
        if ($contentFile) {
            $contentFunction = UtilityHtmlFormat::getFunction($contentFile, $actionjson);
            if ($contentFunction) {
                $contentFile = str_replace($contentFunction, $contentFunctionWrite, $contentFile);
                if($type) {
                    UtilityFile::fileputcontents($link, $contentFile);
                }
            } else {
                $contentFile = UtilityHtmlFormat::insertStringToContentByPosition($contentFile, "\n" . $contentFunctionWrite . "\n", strrpos($contentFile, '}'));
                UtilityFile::fileputcontents($link, $contentFile);
            }
            
        }
    }

    /* Update or create controllers app khi có rewrite link */

    public function updateFunctionControllerApp() {
        $linkControllerJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/controller.js';
        
        $content = UtilityFile::getFileInWeb(Yii::getAlias('@common').'/generator/app/template/main_controller.js');
        $this->array_template['index']['content_js'] = $content;
        $this->array_template['index']['type'] = SettingsWebTemplateEnum::TYPE_CONTROLLER;
        $this->updateFunctionControllerAppByNameAndContent($linkControllerJs,'index',$content);
        
        if ($this->rewrite) {
            $array_json = json_decode($this->rewrite);
            if ($array_json && count($array_json)) {
                $arrayReplace = [];
                $contentTemplate = UtilityFile::getFileInWeb(Yii::getAlias('@common').'/generator/app/template/controller.js');
                foreach ($array_json as $item) {
                    if ($item->linkrewrite && $item->link) {
                        $file = UtilityHtmlFormat::getStrEndSource($item->link);
                        $fileUpper = updateUpperFirstCharacter($file);
                        if (!isset($arrayReplace[$file])) {
                            $content = str_replace(['Template','template'],[$fileUpper,$file],$contentTemplate);
                            $arrayReplace[$file] = $content;
                            $this->array_template[$file]['content_js'] = $content;
                            $this->array_template[$file]['type'] = SettingsWebTemplateEnum::TYPE_CONTROLLER;
                            $this->updateFunctionControllerAppByNameAndContent($linkControllerJs,$file,$content);
                        }
                    }
                }
            }
        }
    }
    
    /*Tạo function controller app*/
    public function createFunctionControllerApp($file) {
        $fileUpper = updateUpperFirstCharacter($file);
        $linkControllerJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/controller.js';
        $contentTemplate = UtilityFile::getFileInWeb(Yii::getAlias('@common').'/generator/app/template/controller.js');
        $content = str_replace(['Template','template'],[$fileUpper,$file],$contentTemplate);
        $this->updateFunctionControllerAppByNameAndContent($linkControllerJs,$file,$content);
        return $content;
    }
    
    /*Xóa function controller app*/
    public function deleteFunctionControllerApp($file, $content = '') {
        $linkControllerJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/controller.js';
        $this->updateFunctionControllerAppByNameAndContent($linkControllerJs,$file,'', true);
    }
    
    /* Update or create 1 controller app bằng tên controller và nội dung của nó */
    public function updateFunctionControllerAppByNameAndContent($link, $actionjson, $contentFunctionWrite, $type = false) {
        $contentFile = filegetcontents($link);
        if ($contentFile !== false) {
            $contentFunction = UtilityHtmlFormat::getFunctionControllerApp($contentFile, $actionjson);
            if ($contentFunction) {
                $contentFile = str_replace($contentFunction, $contentFunctionWrite, $contentFile);
                if($type) {
                    UtilityFile::fileputcontents($link, $contentFile);
                }
            } else {
                $contentFile .= "\n" . $contentFunctionWrite . "\n";
                UtilityFile::fileputcontents($link, $contentFile);
            }
            
        }
    }
    
    /*Tạo function directive app*/
    public function updateFunctionDirectiveApp() {
        if ($this->rewrite) {
            $linkControllerJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/directive.js';
            $array_json = ['header','footer'];
            if ($array_json && count($array_json)) {
                $arrayReplace = [];
                $contentTemplate = UtilityFile::getFileInWeb(Yii::getAlias('@common').'/generator/app/template/directive.js');
                foreach ($array_json as $file) {
                    $fileUpper = updateUpperFirstCharacter($file);
                    if (!isset($arrayReplace[$file])) {
                        $content = str_replace(['Template11','template11'],[$fileUpper,$file],$contentTemplate);
                        $arrayReplace[$file] = $content;
                        $this->array_template[$file]['content_js'] = $content;
                        $this->array_template[$file]['type'] = SettingsWebTemplateEnum::TYPE_DIRECTIVE;
                        $this->updateFunctionDirectiveAppByNameAndContent($linkControllerJs,$file,$content);
                    }
                }
            }
        }
    }
    /*Tạo function directive app*/
    public function createFunctionDirectiveApp($file) {
        $fileUpper = updateUpperFirstCharacter($file);
        $linkControllerJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/directive.js';
        $contentTemplate = UtilityFile::getFileInWeb(Yii::getAlias('@common').'/generator/app/template/directive.js');
        $content = str_replace(['Template11','template11'],[$fileUpper,$file],$contentTemplate);
        $this->updateFunctionDirectiveAppByNameAndContent($linkControllerJs,$file,$content);
        return $content;
    }
    /*Xóa function directive app*/
    public function deleteFunctionDirectiveApp($file) {
        $linkControllerJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/directive.js';
        $this->updateFunctionDirectiveAppByNameAndContent($linkControllerJs,$file,'');
    }
    
    /* Update or create 1 controller app bằng tên controller và nội dung của nó */
    public function updateFunctionDirectiveAppByNameAndContent($link, $actionjson, $contentFunctionWrite) {
        $contentFile = filegetcontents($link);
        if ($contentFile !== false) {
            $contentFunction = UtilityHtmlFormat::getFunctionDirectiveApp($contentFile, $actionjson);
            if ($contentFunction) {
                $contentFile = str_replace($contentFunction, $contentFunctionWrite, $contentFile);
            } else {
                $contentFile .= "\n" . $contentFunctionWrite . "\n";
            }
            UtilityFile::fileputcontents($link, $contentFile);
        }
    }

    /* Lấy file rewrite link cho vào app.js */

    public function getRewriteStrApp() {
        $strl = '';
        if ($this->rewrite) {
            $array_json = json_decode($this->rewrite);
            $arrayReplace = [];
            if ($array_json && count($array_json)) {
                foreach ($array_json as $item) {
                    if ($item->linkrewrite && $item->link) {
                        $linkrewrite = preg_replace('/(\:[^>]+>)|(\/)/', '', $item->linkrewrite);
                        $file = UtilityHtmlFormat::getStrEndSource($item->link);
                        $fileUpper = updateUpperFirstCharacter($file);
                        $arrayReplace[] = "when(ALIAS + '" . str_replace('<', ':', $linkrewrite) . "', {\n\t\t"
                                . "templateUrl: LINK_PUBLIC + 'partials/main/{$file}.html',\n\t\t"
                                . "controller: '{$fileUpper}Controller',\n\t\t})";
                    }
                }
            }
            if (count($arrayReplace)) {
                $strl = implode(".\n\t\t", $arrayReplace);
            }
        }
        return $strl;
    }

    public function addWebTemplateByRewriteLink() {
        
        if (count($this->array_template)) {
            foreach ($this->array_template as $file => $item) {
                    $model = SettingsWebTemplateSearch::findOne([
                                'web_id' => $this->id,
                                'name' => $file,
                    ]);
                    if (!$model) {
                        $model = new SettingsWebTemplateSearch();
                        $model->web_id = $this->id;
                        $model->name = $file;
                        $model->type = isset($item['type']) ? $item['type'] : SettingsWebTemplateEnum::TYPE_CONTROLLER;
                        $model->content_js = isset($item['content_js']) ? $item['content_js'] : '';
                        $model->content_php = isset($item['content_php']) ? $item['content_php'] : '';
                        $model->content_html = isset($item['content_html']) ? $item['content_html'] : '';
                        $model->save(false);
                    }
            }
        }
    }
    
    public function saveFile() {
        $linkMainPhp = Yii::getAlias('@application') . '/' . $this->directory . '/views/layouts/main.php';
        $linkHeadPhp = Yii::getAlias('@application') . '/' . $this->directory . '/views/layouts/partials/head.php';
        UtilityFile::fileputcontents($linkMainPhp, $this->layout);
        UtilityFile::fileputcontents($linkHeadPhp, $this->head);
    }
    
    public function getFileLayoutHead() {
        $linkMainPhp = Yii::getAlias('@application') . '/' . $this->directory . '/views/layouts/main.php';
        $linkHeadPhp = Yii::getAlias('@application') . '/' . $this->directory . '/views/layouts/partials/head.php';
        $this->layout = UtilityFile::filegetcontent($linkMainPhp);
        $this->head = UtilityFile::filegetcontent($linkHeadPhp);
    }
    
    public function saveTemplate($modelWebTemplate) {
        /*@var $modelWebTemplate SettingsWebTemplateSearch */
        if($modelWebTemplate->type == SettingsWebTemplateEnum::TYPE_CONTROLLER) {
            $linkControllerJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/controller.js';
            $this->updateFunctionControllerAppByNameAndContent($linkControllerJs,$modelWebTemplate->name,$modelWebTemplate->content_js, true);
        }
        
        if($modelWebTemplate->type == SettingsWebTemplateEnum::TYPE_DIRECTIVE) {
            $linkControllerJs = Yii::getAlias('@application') . '/' . $this->directory . '/public/app/directive.js';
            $this->updateFunctionDirectiveAppByNameAndContent($linkControllerJs,$modelWebTemplate->name,$modelWebTemplate->content_js, true);
        }
        
        $fileUpper = updateUpperFirstCharacter($modelWebTemplate->name);
        $link_controller_json = Yii::getAlias('@application') . '/' . $this->directory . '/controllers/JsonController.php';
        $this->updateFunctionActionController($link_controller_json, $fileUpper.'json', $modelWebTemplate->content_php, true);
        
        $this->createFunctionActionindexController($modelWebTemplate->name);
        $link_view = Yii::getAlias('@application') . '/' . $this->directory . '/views/main/'.$modelWebTemplate->name.'.php';
        UtilityFile::fileputcontents($link_view, $modelWebTemplate->content_html);
    }

}
