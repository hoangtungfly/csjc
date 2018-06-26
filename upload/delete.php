<?php
error_reporting(0);
$result = array();
if(isset($_POST['deletefile'])){
    $path = trim($_POST['path']);
    $file = trim($_POST['file']);
    $basepath = __DIR__ . $path . $file;
    if(file_exists($basepath)) {
        @unlink($basepath);
    }
    die();
} else {
    echo echoResponse();
}

function echoResponse() {
    global $result;
    echo json_encode($result);
    die();
}
