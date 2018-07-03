<?php
$alias = (WEBNAME != WEB_MAIN) ? WEBNAME . '/' : '';
return array(
    'tin-tuc' => WEBNAME . '/news/list',
    'du-an' => WEBNAME . '/project/list',
    $alias .'<alias:[a-zA-Z0-9-]+>' => DS . WEBNAME . '/main/category',
    ['pattern' => $alias.'<alias:[a-zA-Z0-9-]+>/', 'route' => WEBNAME . '/main/category', 'suffix' => '/'],
    
    'rss/<alias:(.*)>/' => '/site/rss',
    ['pattern' => 'rss/<alias:(.*)>', 'route' => WEBNAME . '/rss/index', 'suffix' => '/'],
    
    '404.html' => WEBNAME . '/main/404',
    'feeds' => WEBNAME . '/main/feed',
    
    '<alias:[a-zA-Z0-9-]+>-<id:[0-9]+>.html' => WEBNAME . '/news/index',
);