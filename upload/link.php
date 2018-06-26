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

function filegetcontents($link) {
    $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
    $link = str_replace(' ', '%20', $link);
    return @file_get_contents('https://img.gs/zsztpvdrbc/full/'.$link,false, stream_context_create($arrContextOptions));
//    return @file_get_contents('http://blog.metrixa.com/filecontent.php?link=' . $link);
}

$post = $_POST;
$tableName = str_replace('/', '', $post['table_name']);
if (isset($post['link']) && $post['link'] != "") {
    $replace_name = isset($post['replace_name']) ? $post['replace_name'] : '';
    $link = trim($post['link']);
    $content = filegetcontents($link);
    if ($content) {
        $original_name = pathinfo($link, PATHINFO_BASENAME);
        if ($replace_name) {
            $a = explode('.', $original_name);
            $a[0] = $replace_name;
            $original_name = implode('.', $a);
        }
        $UploadDirectory = APPLICATION_PATH . 'images/' . $tableName . '/main/';
        $filename = getFileNameNew($UploadDirectory, $original_name);

        $link = 'images/' . $tableName . '/main/' . $filename;
        $linkMain = APPLICATION_PATH . 'images/' . $tableName . '/main/' . $filename;
        rmkdir(APPLICATION_PATH . 'images/' . $tableName . '/main/');

        if (file_put_contents($linkMain, $content)) {
            $resizeResult = array();
            if (isset($post['resize']) && $post['resize'] != "") {
                $resize = (array) json_decode($post['resize']);
                foreach ($resize as $key => $a) {
                    $tmp = implode('x', $a);
                    $file = 'images/' . $tableName . '/' . $tmp . '/' . $filename;
                    $linkResize = APPLICATION_PATH . $file;
                    if (CHECK_IMAGICK) {
                        $upload = new ResizeImagick();
                    } else {
                        $upload = new Resize();
                    }
                    $upload->setImageFile($linkMain);
                    list($width, $height) = explode('x', $tmp);
                    $upload->width = $width;
                    $upload->height = $height;
                    $upload->startResize();
                    $upload->save($linkResize);
                    $resizeResult[] = array(
                        'file' => $file,
                    );
                }
            }
            setResponse(200, [
                'link' => '/' . $link,
                'name' => $filename,
                'original_name' => $original_name,
                'ext' => pathinfo($filename, PATHINFO_EXTENSION),
                'baseUrl' => '/images/' . $tableName . '/main/',
                'resize' => $resizeResult,
            ]);
            echoResponse();
        }
    }
}

function rmkdir($path, $mode = 0775) {
    return is_dir($path) || ( rmkdir(dirname($path), $mode) && _mkdir($path, $mode) );
}

function _mkdir($path, $mode = 0775) {
    $old = umask(0);
    $res = @mkdir($path, $mode);
    umask($old);
    return $res;
}

function setResponse($code, $data) {
    global $result;
    $result = array('status' => $code, 'response' => $data);
    return $result;
}

function echoResponse() {
    global $result;
    echo json_encode($result);
    die();
}

function getFileNameNew($UploadDirectory, $FileName) {
    $extension = pathinfo($FileName, PATHINFO_EXTENSION);
    $name = stripUnicode(pathinfo($FileName, PATHINFO_FILENAME));
    $nameNew = $name;
    $link = $UploadDirectory . $name . '.' . $extension;
    $count = 1;
    while (is_file($link)) {
        $nameNew = $name . '(' . $count . ')';
        $link = $UploadDirectory . $nameNew . '.' . $extension;
        $count++;
    }
    return $nameNew . '.' . $extension;
}

function stripUnicode($str, $doi = '-') {
    $str = trim($str);
    $arrayPregReplace = [
        'à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|A|ầ|à' => 'a',
        'è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|E' => 'e',
        'ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ|I' => 'i',
        'ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|O' => 'o',
        'ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|U' => 'u',
        'ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ|Y' => 'y',
        'đ|Đ|D' => 'd',
        '[^a-zA-Z0-9 ]+' => ' ',
        '(\s)+' => ' ',
    ];
    foreach ($arrayPregReplace as $key => $value) {
        $str = preg_replace("/{$key}/", $value, $str);
    }
    $str = trim(strtolower(str_replace(' ', $doi, trim($str))));
    return $str;
}
