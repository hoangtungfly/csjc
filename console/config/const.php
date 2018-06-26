<?php

$server = $_SERVER;
if(!isset($server['REQUEST_URI'])) {
    $server['REQUEST_URI'] = '';
}
if(!isset($server['HTTP_HOST'])) {
    $server['HTTP_HOST'] = HOST_PL;
}
if(isset($server['HTTP_USESSSL']) && $server['HTTP_USESSSL'] == 'on') {
    define('SCHEME','https');
    $_SERVER['HTTPS'] = 'on';
} else {
    define('SCHEME',isset($server['REQUEST_SCHEME']) ? $server['REQUEST_SCHEME'] : (isset($server['HTTPS']) && $server['HTTPS'] == 'on' ? 'https' : 'http'));
}
define('REQUEST_URI',$server['REQUEST_URI']);
define('PHP_SELF', phpself($server));
define('DOMAIN',$server['HTTP_HOST']);
define('DOMAIN_SERVER',phpdomain($server));
define('HTTP_HOST', SCHEME . '://' . DOMAIN . (PHP_SELF ? PHP_SELF : ''));
define('HTTP_HOST_PUBLIC', SCHEME . '://' . DOMAIN . (PHP_SELF ? PHP_SELF : '').'/');
define('HOST_PUBLIC', 'http://' . DOMAIN_SERVER . PHP_SELF);

/*WEB NAME IN DIRECTORY APPLICATION*/
define('WEBSERVER', phpwebname($server));
define('WEBNAME', WEBSERVER ? WEBSERVER : WEB_MAIN);
/*CONFIG MAIN_LOCAL*/
$web_type = '/backend/web';
define('DIRECTORY_MAIN', $web_type . '/');
define('DIRECTORY_MAIN_2', $web_type);
define('MAIN_ROUTE', WEB_TYPE != '' ? '/' . WEB_TYPE : '');
define('LANGUAGE', 'en');
define('VERSION', "1.3");

/*CONFIG FORMAT DATE*/
define('FORMAT_DATE', 'd-M-Y');
define('FORMAT_DATETIME', 'd-M-Y H:i A');
define('FORMAT_DATE_INPUT', 'dd-mm-yyyy');
define('FORMAT_DATETIME_INPUT', 'MM/DD/YYYY H:m');


/*CONFIG TYPE USER IN table user*/
define('APP_TYPE_USER', 0);
define('APP_TYPE_ADMIN', 3);
define('APP_TYPE_CUSTOMERS', 1);

/*MIN FILE CSS JAVASCRIPT '' or '.min'*/
define('MIN_MEDIA_FILES', '');

/*UPLOAD FILE*/
define('HOST_MEDIA', HTTP_HOST . '/upload/');
define('HOST_MEDIA_IP', HTTP_HOST .  '/upload/');
define('HOST_MEDIA_IMAGES', HTTP_HOST .  '/images/');
define('HOST_MEDIA_SWF', HTTP_HOST .  '/images/');
define('HOST_MEDIA_FILES', HTTP_HOST .  '/images/');
define('HOST_MEDIA_RESIZE', APPLICATION_PATH . '/images/');
define('DS', '/');


define('MAX_BUTTON_PAGE', 5);
define('CURRENCY_CODE', '$');
define('CURRENCY_DISPLAYED', 'AUD');

/*PAGER*/
define('PAGESIZE',20);

/*CONFIG ANGULARJS*/
define('ANGULARJS', false);
define('ANGULARJS_WRITEFILE', false);

define('LINK_PUBLIC', '/application/' . WEBNAME . '/public/');
define('DIR_LINKPUBLIC', APPLICATION_PATH . '/application/' . WEBNAME . '/public/');
define('DIR_LINKPUBLIC_PARTIAL', DIR_LINKPUBLIC . '/partials/');

define('LINK_PUBLIC_ADMIN','/backend/web/');
define('LINK_PUBLIC_ADMIN_PARTIAL','/backend/web/partials/');
define('DIR_LINKPUBLIC_ADMIN', APPLICATION_PATH . LINK_PUBLIC_ADMIN);
define('DIR_LINKPUBLIC_ADMIN_PARTIAL', APPLICATION_PATH . LINK_PUBLIC_ADMIN_PARTIAL);

define('DEFAULT_BRAINTREE_GATEWAY_KEY', 'braintree');
define('BRAINTREE_USER', 's7snq6q9rpbtrzym');
define('BRAINTREE_PUBLIC_KEY', 'khjqwrqckw23rrn2');
define('BRAINTREE_PRIVATE_KEY', 'c8f022dbe1953b615c43a1b0f0ccf333');
define('PLAN_BRAINTREE_SUBSCRIPTION', 'doan1234');

