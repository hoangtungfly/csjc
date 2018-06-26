<?php

namespace common\models\web;

use common\core\enums\web\SettingsWebTemplateEnum;
use common\models\admin\SettingsWebcronSearch;
use common\utilities\UtilityHtmlFormat;
use Yii;

class SettingsWebTemplateSearch extends SettingsWebTemplate {

    public function getContent() {
        $modelWebCron = SettingsWebcronSearch::findOne($this->web_id);
        /* action json
         * BEGIN
         */
        $link_controller_json = Yii::getAlias('@application') . '/' . $modelWebCron->directory . '/controllers/JsonController.php';
        $contentFile = filegetcontents($link_controller_json);
        if ($contentFile) {
            $contentFunction = UtilityHtmlFormat::getFunction($contentFile, $this->name . 'json');
            if ($contentFunction) {
                $this->content_php = $contentFunction;
            }
        }
        /* END */
        /* js
         * BEGIN
         */
        if ($this->type == SettingsWebTemplateEnum::TYPE_CONTROLLER) {
            $linkControllerJs = Yii::getAlias('@application') . '/' . $modelWebCron->directory . '/public/app/controller.js';
            $contentFile = filegetcontents($linkControllerJs);
            if ($contentFile) {
                $contentFunction = UtilityHtmlFormat::getFunctionControllerApp($contentFile, $this->name);
                if ($contentFunction) {
                    $this->content_js = $contentFunction;
                }
            }
        }
        if ($this->type == SettingsWebTemplateEnum::TYPE_DIRECTIVE) {
            $linkControllerJs = Yii::getAlias('@application') . '/' . $modelWebCron->directory . '/public/app/directive.js';
            $contentFile = filegetcontents($linkControllerJs);
            if ($contentFile) {
                $contentFunction = UtilityHtmlFormat::getFunctionDirectiveApp($contentFile, $this->name);
                if ($contentFunction) {
                    $this->content_js = $contentFunction;
                }
            }
        }
        /* END */
        $link_html = $linkControllerJs = Yii::getAlias('@application') . '/' . $modelWebCron->directory . '/views/main/'.$this->name.'.php';
        $contentFile = filegetcontents($link_html);
        if($contentFile) {
            $this->content_html = $contentFile;
        }
    }

}
