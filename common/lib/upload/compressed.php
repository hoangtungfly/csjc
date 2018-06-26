<?php

//error_reporting(E_ALL);
ini_set("display_errors", 0);
define('CHECK_IMAGICK', class_exists('Imagick'));
if (CHECK_IMAGICK) {
    require './ResizeImagick.php';
} else {
    require './Resize.php';
}
define('APPLICATION_PATH', str_replace('upload', '', str_replace('\\', '/', __DIR__)));
define('DS', '/');

$post = $_POST;
$tableName = str_replace('/', '', $post['table_name']);
$tmp = str_replace('/', '', $post['tmp']);
$filename = str_replace('/', '', $post['filename']);
$linkResize = APPLICATION_PATH . 'images/' . $tableName . '/' . $tmp . '/' . $filename;
$linkMain = APPLICATION_PATH . 'images/' . $tableName . '/main/' . $filename;
if (is_file($linkMain)) {
    if (CHECK_IMAGICK) {
        $upload = new ResizeImagick();
    } else {
        $upload = new Resize();
    }
    $upload->setImageFile($linkMain);
    if (strpos($tmp, 'w') === 0) {
        $width = (int)str_replace('w','',$tmp);
        $height = 0;
    } else if (strpos($tmp, 'h') === 0) {
        $height = (int)str_replace('h','',$tmp);
        $width = 0;
    } else {
        list($width, $height) = explode('x', $tmp);
    }
    if ($width == 0 && $height) {
        $width = (int) ($height * $upload->oriWidth / $upload->oriHeight);
    }
    $upload->width = $width;

    if ($height == 0 && $upload->width) {
        $height = (int) ($upload->width * $upload->oriHeight / $upload->oriWidth);
    }
    $upload->height = $height;

    if (!$height && !$width) {
        $upload->width = $upload->oriWidth;
        $upload->height = $upload->oriHeight;
    }

    $upload->startResize();
    $upload->save($linkResize);
}