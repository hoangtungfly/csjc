<?php

use common\models\category\CategoriesSearch;
use common\models\news\NewsSearch;
use common\models\product\ProductSearch;
use common\utilities\UtilityUrl;
$config = $this->context->array_config();
$config['curl'] = UtilityUrl::realURL();
$config['og_image'] = $config['logo'];
$config['og_type'] = $config['meta_title'];
$get = r()->get();
if(isset($get['id'])) {
    $id = (int)$get['id'];
    $news = NewsSearch::findOne($id);
    if($news) {
        $config['meta_title'] = $news->meta_title ? $news->meta_title : $news->name;
        $config['meta_description'] = $news->meta_description ? $news->meta_description : $news->name;
        $config['meta_keyword'] = $news->meta_keyword ? $news->meta_keyword : $news->name;
        $config['og_image'] = $news->getimage([],$news->image);
        if(isset($get['categoryalias'])) {
            $category = CategoriesSearch::findOne(['alias' => $get['categoryalias']]);
            if($category) {
                $config['og_type'] = $category->name;
            }
        }
        $config['og_type'] = $news->getimage([],$news->image);
    }
} else if(isset($get['alias'])) {
    $category = CategoriesSearch::findOne(['alias' => $get['alias']]);
    if($category) {
        $config['meta_title'] = $category->meta_title ? $category->meta_title : $category->name;
        $config['meta_description'] = $category->meta_description ? $category->meta_description : $category->name;
        $config['meta_keyword'] = $category->meta_keyword ? $category->meta_keyword : $category->name;
    } else {
        $product = ProductSearch::findOne(['alias' => $get['alias']]);
        if($product) {
            $config['meta_title'] = $product->meta_title ? $product->meta_title : $product->name;
            $config['meta_description'] = $product->meta_description ? $product->meta_description : $product->name;
            $config['meta_keyword'] = $product->meta_keyword ? $product->meta_keyword : $product->name;
        } else {
            $config['meta_title'] = $get['alias'];
            $config['meta_description'] = $get['alias'];
            $config['meta_keyword'] = $get['alias'];
        }
    }
    if(isset($get['page'])) {
        $config['meta_title'] = ' trang ' . $get['page'];
    }
}
if(isset($get['search'])) {
    $config['meta_title'] = $get['search'];
    $config['meta_description'] = $get['search'];
    $config['meta_keyword'] = $get['search'];
}
if(isset($get['tag'])) {
    $config['meta_title'] = $get['tag'];
    $config['meta_description'] = $get['tag'];
    $config['meta_keyword'] = $get['tag'];
}

$menu_rss = CategoriesSearch::getAllCategoryRss();
?>

<head>
    <title><?=$config['meta_title']?></title>
    <meta name="keywords" content="<?=$config['meta_keyword']?>" />
    <meta name="description" content="<?=$config['meta_description']?>" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="INDEX,FOLLOW,ALL" />
    
    <link  rel="shortcut icon" type="image/x-icon" href="<?=isset($config['favico']) ? $config['favico'] : $config['logo']?>" />
    <link href="<?=LINK_PUBLIC?>/img/apple-touch-icon.png" rel="apple-touch-icon-precomposed">
    <link href="<?=LINK_PUBLIC?>//img/apple-touch-icon-114x114.png" sizes="114x114" rel="apple-touch-icon-precomposed">
    <link href="<?=LINK_PUBLIC?>//img/apple-touch-icon-72x72.png" sizes="72x72" rel="apple-touch-icon-precomposed">
    <link href="<?=LINK_PUBLIC?>//img/apple-touch-icon-144x144.png" sizes="144x144" rel="apple-touch-icon-precomposed">
    
    <meta http-equiv="expires" content="0" />
    <meta name="resource-type" content="document" />
    <meta name="distribution" content="global" />
    <meta http-equiv="Refresh" content="3600" />
    <meta name="robots" content="index, follow" />
    <meta name="revisit-after" content="1 days" />
    <meta name="rating" content="general" />
    <meta name="copyright" content="<?=  strip_tags($config['copyright'])?>" />
    <link rel="canonical" href="<?=$config['curl']?>">
    <?php if($menu_rss) { ?>
    <?php foreach($menu_rss as $key => $feed) { ?>
    <link href="<?=$feed['link_rss']?>" rel="alternate" type="application/rss+xml" title="<?=$feed['name']?>" />
    <?php } ?>
    <?php } ?>
    <meta name="google-site-verification" content="<?=$config['google_site_vertification']?>" />

    <!-- BEGIN GEO -->
    <meta name="geo.region" content="<?=$config['geo_region']?>" />
    <meta name="geo.placename" content="<?=$config['geo_region']?>" />
    <meta name="geo.position" content="<?=$config['geo_position']?>" />
    <meta name="ICBM" content="<?=$config['geo_icbm']?>" />
    <!-- END GEO -->
    
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
        var HTTP_MEDIA = '<?=HTTP_HOST?>';
        var HTTP_HOST = '<?=HTTP_HOST?>';
        var WEBNAME = '<?=WEBNAME?>';
        var HOTLINE = '<?=isset($config['hotline']) ? $config['hotline'] : ''?>';
        var DEVICE = <?=  UtilityUrl::isMobile() ? 1 : 0?>;
        var WEB_TYPE = '/';
    </script>
    <?php $this->head() ?>
    <?php if(isset($config['google_analytic'])) { ?>
    <?=$config['google_analytic']?>
    <?php } ?>
</head>