<?php
error_reporting(1);
ini_set('display_errors','On');
$result = array();

define('DS', '/');
require './ResizeImagick.php';

if(isset($_REQUEST['user_id']) && isset($_REQUEST['user_name'])){
    $user_name = $_REQUEST['user_name'];
    $user_id = $_REQUEST['user_id'];
    $part_arr = [
        'media',
        'images',
        'user',
        $user_id
    ];
    set_time_limit(1000000000);
    $url = 'https://graph.facebook.com/'.$user_name.'/picture?width=340&height=340';
    $imgface = getSSLPage($url);
    if($imgface) {
        $part = __DIR__ . DS . implode(DS, $part_arr) . DS;
        $part_resize = __DIR__ . DS . implode(DS, $part_arr) . DS . 's60_60' . DS;
        if (!is_dir($part)) {
            $old = umask(0);
            @mkdir($path, 0775);
            chown($path, 'apache');
            umask($old);
        }
        if (!is_dir($part_resize)) {
            $old = umask(0);
            @mkdir($part_resize, 0775);
            chown($part_resize, 'apache');
            umask($old);
        }
        $file_name = $user_name .'.jpg';
        $image = $part . $file_name;
        $image_resize = $part_resize . $file_name;
        //var_dump($imgface);
        file_put_contents($image, $imgface);
        //var_dump($abc);
        //file_put_contents($image_resize, $imgface);
        actionRunCompress($image, $image_resize, 340);
        cropImg($image_resize, $image_resize);
    }
    
} else {
    echo echoResponse();
}
function actionRunCompress($img_path, $im_sz_path, $size = 180, $quantity = 85) {
    if (file_exists($img_path)) {
        $imagick_type = new Imagick();
        $imagick_type->readImage($img_path);
        $imagick_type->setImageCompression(imagick::COMPRESSION_JPEG);
        $imagick_type->setImageCompressionQuality($quantity);
        $imagick_type->stripImage();
        // PHOTOSHOP
        $imagick_type->unsharpMaskImage(0, 0.5, 1, 0.05);
        $imagick_type->resizeImage($size, 0, imagick::FILTER_LANCZOS, 1);
        $imagick_type->writeImage($im_sz_path);
        $imagick_type->destroy();
    }
}


function cropImg($img, $imgcrop) {
    $resizeModel = new ResizeImagick();
    $resizeModel->setImageFile($img);
        //resize with width and height
    $resizeModel->width = 60;
    $resizeModel->height = 60;
    $resizeModel->startResize();
    //resize with crop
    $resizeModel->save($imgcrop);
    $resizeModel->setErros(NULL);
    //$resizeModel->setImagickObject();
}
function echoResponse() {
    global $result;
    echo json_encode($result);
    die();
}

function getSSLPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    if($error != '') {
        //var_dump($error, $url);
    }
    curl_close($ch);
    return $result;
}
