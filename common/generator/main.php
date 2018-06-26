<?php

$alias = WEBNAME != WEB_MAIN ? WEBNAME.'/' : '';

return array(
    $alias.'<alias:(.*)>.rss' => WEBNAME . '/json/rss',
    //replace
);