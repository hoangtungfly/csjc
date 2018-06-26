<?php


//error_reporting(E_ALL);
ini_set("display_errors", 0);
define('CHECK_IMAGICK',class_exists('Imagick'));
require './Uploadhandel.php';
define('APPLICATION_PATH', str_replace('upload', '', __DIR__));
define('DS', '/');
/**
 * ===============================================================
 * Option for upload
 * ===============================================================
 * # type for upload
 * int upload_type:
 *  + 1: upload only images
 *  + 2: upload any files
 * 
 * # allowed extensions to upload
 * array extension
 * ex: array('img','jpg')
 * 
 * # create image thumb if file upload is image
 * boolen create_thumb
 *  + true: create image thumb
 *  + false: not create image thumb
 * 
 * # path to upload
 * array path
 * ex: array('images','top1')
 * 
 * # max size to upload
 * int max_size
 * ex: max_size = 100000
 * System doest allow set max_size over 500000
 * 
 * # max width
 * int max_width max width of image file upload
 * 
 * # max height
 * int max_height max height of image file upload
 * 
 * # array resize
 * array resize 
 * ex array(
 *  array(10,20),
 *  array(30,500),
 *  array(10,20,10,20)
 * )
 * if array(10,20) system will create thumb with width is 10, height is 20
 * if array(10,20,10,20) system will create thumb with crop x1=10,x2=20,y1=10,y2=20
 * 
 */
/** array match option in param to method in upload handle class * */
$array_match_options = array(
    'upload_type' => 'setUploadType',
    'create_thumb' => 'setCreateThumb',
    'extension' => 'SetValidExtensions',
    'max_size' => 'SetMaximumFileSize',
    'max_width' => 'SetMaximumWidth',
    'max_height' => 'SetMaximumHeight',
    'path' => 'setDirUpload'
);


/**
 * result that return for client
 */
$result = array();
$s['tmp_name'] = $_FILES;
if (isset($_POST['options'])) {
    $post['options'] = json_decode($_POST['options'], true);

    $_FILES['tmp_name']['name'] = $_POST['name'];
    /* create instance of uploadhandel class */
    $upload = new Uploadhandel($_FILES['tmp_name']);
    /* process options */
    $option = isset($post['options']) ? $post['options'] : array();

    foreach ($option as $key => $item) {
        if (isset($array_match_options[$key]) && $array_match_options[$key]) {
            $upload->$array_match_options[$key]($item);
        }
    }
    /* process path */
    $uploadCheck = $upload->save();
    $resizeResult = array();
    /* process create thumb */
    if (is_array($post['options']['resize']) && $upload->getResizeModel() && strtolower(pathinfo($_FILES['tmp_name'],PATHINFO_EXTENSION)) != 'gif') {
        if (isset($post['options']['quality_resize'])) {
            $upload->getResizeModel()->quality = $post['options']['quality_resize'];
        }
        $countresize = count($post['options']['resize']);

        foreach ($post['options']['resize'] as $keyResize => $item) {
            $count = count($item);
            /* resize with width and height */
            if ($count <= 2 && $count > 0) {
                $w = isset($item[0]) ? $item[0] : null;
                $h = isset($item[1]) ? $item[1] : null;
                $upload->getResizeModel()->width = $w;
                $upload->getResizeModel()->height = $h;
                $upload->getResizeModel()->startResize();
                /* resize with crop */
            } elseif ($count >= 4) {
                $upload->getResizeModel()->paramCrop = $item;
                $upload->getResizeModel()->crop();
            }
            $renderFileThumb = $upload->renderFileThumb($item);
            $upload->getResizeModel()->save($renderFileThumb['fulldir']);
            $resizeResult[] = array(
                'file' => $renderFileThumb['file'],
                'folder' => $renderFileThumb['folder'],
                'error' => $upload->getResizeModel()->getError()
            );
            $upload->getResizeModel()->setErros(NULL);
            if ($key < $countresize && CHECK_IMAGICK) {
                $upload->getResizeModel()->setImagickObject();
            }
        }
    }
    /**
     * add result to global
     */
    if ($uploadCheck) {
        if ($upload->getUploadType() == Uploadhandel::UPLOAD_IMAGE) {
            setResponse(200, array(
                'name' => $upload->GetFileName(),
                'original_name' => $upload->getOriginalName(),
                'ext' => $upload->getFileExtension(),
                'imagesize' => $upload->GetImageSize(),
                'size' => $upload->getFileSize(),
                'baseUrl' => str_replace(APPLICATION_PATH, '', $upload->GetUploadDirectory()),
                'resize' => $resizeResult
            ));
        } else {
            setResponse(200, array(
                'name' => $upload->GetFileName(),
                'size' => $upload->getFileSize(),
                'original_name' => $upload->getOriginalName(),
                'ext' => $upload->getFileExtension(),
                'baseUrl' => str_replace(APPLICATION_PATH, '', $upload->GetUploadDirectory()),
            ));
        }
    } else {
        setResponse(403, array(
            'name' => $upload->GetFileName(),
            'error' => $upload->getErrors()
        ));
    }
    /**
     * return result for clients
     */
    echoResponse();
    die();
} else {
    echo echoResponse();
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
