<?php

use common\models\settings\SystemSettingSearch;

$config = SystemSettingSearch::getConfigCommon();
?>

<head>
    <title><?=$config['meta_title']?></title>
    <meta name="keywords" content="<?=$config['meta_keyword']?>" />
    <meta name="description" content="<?=$config['meta_description']?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="INDEX,FOLLOW,ALL" />
    
    <link  rel="shortcut icon" type="image/x-icon" href="<?=$config['logo']?>" />
    
    <meta http-equiv="expires" content="0" />
    <meta name="resource-type" content="document" />
    <meta name="distribution" content="global" />
    <meta http-equiv="Refresh" content="3600" />
    <meta name="robots" content="index, follow" />
    <meta name="revisit-after" content="1 days" />
    <meta name="rating" content="general" />
    <meta name="copyright" content="<?=$config['copyright']?>" />
    <link rel="canonical" href="<?=$config['curl']?>">
    
    <link ng-repeat="category in feeds" href="{{category.link_rss}}" rel="alternate" type="application/rss+xml" title="{{category.name}}" />

    <meta name="google-site-verification" content="<?=$config['google_site_vertification']?>" />

    <!-- BEGIN GEO -->
    <meta name="geo.region" content="<?=$config['geo_region']?>" />
    <meta name="geo.placename" content="<?=$config['geo_region']?>" />
    <meta name="geo.position" content="<?=$config['geo_position']?>" />
    <meta name="ICBM" content="<?=$config['geo_icbm']?>" />
    <!-- END GEO -->
        
    <!-- BEGIN META DC -->
    <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />
    <meta name="DC.title" content="<?=$config['dc_title']?>" />
    <meta name="DC.subject" content="<?=$config['dc_subject']?>" />
    <meta name="DC.creator" content="<?=$config['dc_creator']?>" />
    <meta name="DC.subject" content="<?=$config['dc_subject']?>" />
    <meta name="DC.publisher" content="<?=$config['dc_publisher']?>" />
    <meta name="DC.date" content="<?=$config['dc_date']?>" />
    <meta name="DC.type" content="<?=$config['dc_type']?>" />
    <meta name="DC.source" content="<?=$config['dc_source']?>" />
    <meta name="DC.relation" content="<?=$config['dc_relation']?>" />
    <meta name="DC.coverage" content="<?=$config['dc_coverage']?>" />
    <meta name="DC.rights" content="<?=$config['dc_rights']?>" />
    <meta name="DC.language" scheme="ISO639-1" content="vi" />
    <!-- END META DC -->
    <!-- BEGIN META OG -->
    <meta property="og:image" content="<?=$config['og_image']?>" itemprop="thumbnailUrl" />
    <meta property="og:site_name" content="<?=$config['meta_title']?>" />
    <meta property="og:url" content="<?=$config['curl']?>" />
    <meta property="og:type" content="<?=$config['og_type']?>" />
    <meta property="og:title" itemprop="headline" content="<?=$config['meta_title']?>" />
    <meta property="og:description" content="<?=$config['meta_description']?>" />

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?=HOST_PUBLIC.'/'?>" />
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="apple-touch-icon" href="apple-touch-icon.png" />
    <script type="text/javascript">
        var LINK_PUBLIC = '<?=LINK_PUBLIC?>';
        var WEBNAME = '<?=WEBNAME?>';
        var LINKJSON = '<?='/'.WEBNAME.'/json/'?>';
        var ALIAS = '<?=WEBSERVER ? '/' . WEBSERVER : ''?>';
    </script>
    <?php $this->head() ?>
</head>