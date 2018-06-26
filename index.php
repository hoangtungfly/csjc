<?php
$REQUEST_URI = $_SERVER['REQUEST_URI'];
date_default_timezone_set('Asia/Saigon');
define('APPLICATION_PATH', str_replace(['\\'], ['/'], __DIR__));
if(preg_match('~/(admin|backend)~', $REQUEST_URI)) {
    include(__DIR__.'/backend/web/index.php');
} else {
    include(__DIR__.'/frontend/web/index.php');
}