<?php
use yii\helpers\Html;
use yii\web\View;
?>
<head>
    <meta charset="utf-8" />
    <title><?= Html::encode('AIEM application admin') ?></title>
    <?= Html::csrfMetaTags() ?>
    <meta name="description" content="overview &amp; stats" />
    <?=$this->head()?>
    <script type="text/javascript">
        var FORMAT_DATE_INPUT = 'dd-mm-yyyy';
        var PHP_SELF = '<?=PHP_SELF?>';
        var HTTP_HOST = '<?=HTTP_HOST?>';
        var HTTP_HOST_MAIN_ROUTE = '<?=HTTP_HOST.MAIN_ROUTE?>';
        var WEB_TYPE = '<?=WEB_TYPE?>';
        var HOST_MEDIA = '<?=HOST_MEDIA?>';
        var HOST_MEDIA_IP = '<?=HOST_MEDIA_IP?>';
        var HOST_MEDIA_IMAGES = '<?=HOST_MEDIA_IMAGES?>';
        var HOST_MEDIA_SWF = '<?=HOST_MEDIA_SWF?>';
        var HOST_MEDIA_FILES = '<?=HOST_MEDIA_FILES?>';
        var URL_ROLE = '<?=$this->createUrl('/settings/load/role')?>';
        var URL_IMAGE_UPLOAD = '<?=$this->createUrl('/settings/images/upload')?>';
        var URL_IMAGE_ADDLINK = '<?=$this->createUrl('/settings/images/addlink')?>';
        var URL_SWF_UPLOAD = '<?=$this->createUrl('/settings/swf/upload')?>';
        var URL_SWF_ADDLINK = '<?=$this->createUrl('/settings/swf/addlink')?>';
        var URL_FILE_UPLOAD = '<?=$this->createUrl('/settings/files/upload')?>';
        var URL_MENUADMIN_LOAD = '<?=$this->createUrl('/settings/access/loadmenu')?>';
        var URL_STATISTICAL_LOAD = '<?=$this->createUrl('/backend/leftdetail')?>';
        var URL_LOAD_MULTIMENU = '<?=$this->createUrl('/settings/load/multiallmenu')?>';
        var URL_LOAD_MAPPINGMULTIMENU = '<?=$this->createUrl('/settings/load/mappingmultiallmenu')?>';
        var URL_MAPPING_MULTIMENU = '<?=$this->createUrl('/settings/load/multimenu')?>';
        var URL_MAPPING_MAPPING = '<?=$this->createUrl('/settings/load/menu')?>';
        var DIRECTORY_MAIN = '<?=DIRECTORY_MAIN?>';
        var DIRECTORY_MAIN_2 = '<?=DIRECTORY_MAIN_2?>';
    </script>
</head>