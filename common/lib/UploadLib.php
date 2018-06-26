<?php

/**
 * @author: Phong Pham Hong <phongbro1805@gmail.com>
 * class upload file by Curl
 *
 * 
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
 * ex: max_size = 100000 byte
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
 * =============================================================================
 * Example:
 * <input name="upload" type="file"/>
 * upload:
 *  $upload = new UploadLib($_FILE["upload"])
 *  // set path
 *  $upload->setPath(array('as','bc'));
 *  
 *  $upload->uploadImage();
 * 
 */
namespace common\lib;

use ArrayAccess;
use common\lib\upload\UpFile;
use common\utilities\UtilityHtmlFormat;
use Yii;

class UploadLib {

    /**
     * config host
     */
    public $host;

    /**
     * const for upload
     */
    const PRE_FIX_FOLDER_RESIZE = 's';
    const UPLOAD_IMAGE = 1;
    const UPLOAD_FILES = 2;
    /**
     * CURL init
     */
    protected $cUrl;

    /**
     * @var type 
     */
    protected $upload_type;

    /**
     *
     * @var type 
     */
    protected $extension = array();

    /**
     *
     * @var type 
     */
    protected $create_thumb = true;

    /**
     *
     * @var type 
     */
    protected $path = array();

    /**
     *
     * @var type 
     * 
     * byte
     */
    protected $max_size;

    /**
     *
     * @var type 
     */
    protected $max_width;

    /**
     *
     * @var type 
     */
    protected $max_height;

    /**
     *
     * @var type 
     */
    protected $resize;

    /**
     * respon from serve
     * @var array
     */
    protected $allResponse;

    /**
     * status of reponse
     * @var int
     */
    protected $status;

    /**
     * only content from resonse result
     * @var array
     */
    protected $response;

    /**
     * add array files to upload
     * 
     * @var ArrayAccess
     */
    public $files;

    /**
     * errors
     */
    protected $errors = array();

    /**
     * getter and setter
     */
    public function getUploadtype() {
        return $this->upload_type;
    }

    public function getExtension() {
        return $this->extension;
    }

    public function getCreatethumb() {
        return $this->create_thumb;
    }

    public function getPath() {
        return $this->path;
    }

    public function getMaxsize() {
        return $this->max_size;
    }

    public function getMaxwidth() {
        return $this->max_width;
    }

    public function getMaxheight() {
        return $this->max_height;
    }

    public function getResize() {
        return $this->resize;
    }

    public function getResource() {
        return $this->resource;
    }

    public function setUpload_type($upload_type) {
        $this->upload_type = $upload_type;
    }

    public function setExtension($extension) {
        $this->extension = $extension;
    }

    public function setCreate_thumb($create_thumb) {
        $this->create_thumb = $create_thumb;
    }

    public function setPath($path) {
        $this->path = $path;
    }

    public function setMaxsize($max_size) {
        $this->max_size = $max_size;
    }

    public function setMaxwidth($max_width) {
        $this->max_width = $max_width;
    }

    public function setMaxheight($max_height) {
        $this->max_height = $max_height;
    }

    public function setResize($resize) {
        $this->resize = $resize;
    }

    public function getFiles() {
        return $this->files;
    }

    public function setFiles($files) {
        $this->files = $files;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function setErrors($errors) {
        $this->errors = $errors;
    }

    /**
     * build data to sent
     * using get_object_var to get value;
     * only get protected properties
     * 
     * @return array
     */
//    public function prepareData() {
//        /* check if files is not set, return false */
//        if (count($this->getFiles()) == 0) {
//            return false;
//        }
//        /* array to unset some properies */
//        $arrayUnset = array(
//            'host', 'cUrl', 'files', 'response', 'allResponse', 'status'
//        );
//
//        $p = get_object_vars($this);
//        foreach ($arrayUnset as $item) {
//            unset($p[$item]);
//        }
//        $file = $this->getFiles();
//
//        $param['options'] = json_encode($p);
//        $param['name'] = $file['name'];
//        $param['tmp_name'] = [
//            'tmp_name'  => $file['tmp_name'],
//            'type'  => $file['type'],
//            'name'  => $file['name'],
//            'size'  => $file['size'],
//        ];
//        return $param;
//    }
    
    public function prepareData() {
        /* check if files is not set, return false */
        if (count($this->getFiles()) == 0) {
            return false;
        }
        /* array to unset some properies */
        $arrayUnset = array(
            'host', 'cUrl', 'files', 'response', 'allResponse', 'status'
        );

        $p = get_object_vars($this);
        foreach ($arrayUnset as $item) {
            unset($p[$item]);
        }
        $file = $this->getFiles();

        $param['options'] = json_encode($p);
        $param['name'] = $file['name'];
        $param['tmp_name'] = new \CurlFile($file['tmp_name'], $file['type'], $file['name']); //'@' . $this->getFiles()['tmp_name'];
        return $param;
    }

    /**
     * constructor
     * set FILE that is uploaded when create intance of this class
     */
    public function __construct($file, $type = false) {
        if ($file) {
            $this->setFiles($file);
            $this->createPathFlUser();
        }
        $this->cUrl = curl_init();
        
        # host upload
        if(!$type) {
            $this->host = HOST_MEDIA.'index.php';
        } else {
            $this->host = HOST_MEDIA.'compressed.php';
        }
        
    }

    /**
     * Closes a curl session
     */
    public function __destruct() {
        // close our curl session
        if ($this->cUrl) {
            curl_close($this->cUrl);
        }
    }

    /**
     * start upload file by curl
     * 
     * firstly, get data from prepareData method
     * check data is valid or not
     * create CURL
     * set response to response propety
     * 
     * @return UploadLib $this
     */
//    public function upload($data = false) {
//        $data = !$data ? $this->prepareData() : $data;
//        $this->allResponse =  UpFile::up($data);
//        $this->processResponse();
//        return $this;
//    }
    
    public function upload($data = false) {
        /* set path default follow user_id */
        if (!$this->path) {
            $this->createPathFlUser();
        }
        // set curl POST options
        $curl = $this->cUrl;
        /* check data is true or not */
        $data = !$data ? $this->prepareData() : $data;
        if (!$data) {
            return false;
        }
        curl_setopt($curl, CURLOPT_URL, $this->host);
        curl_setopt($curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10000);

        $responseBody = curl_exec($curl);
        $this->allResponse = $responseBody;
        $this->processResponse();
        return $this;
    }

    /**
     * this method is used for upload images
     * set upload_type is const UPLOAD_IMAGE
     * 
     * @return upload()
     */
    public function uploadImage($link = false, $name = false) {
        $this->upload_type = self::UPLOAD_IMAGE;
        # set max size is 10 MB
        $this->max_size = 10485760;
        $this->setExtension(array(
            'img', 'jpg', 'jpeg', 'JPEG', 'JPG', 'IMG', 'png', 'PNG',
        ));
        return !$link ? $this->upload() : $this->uploadLink($link, $name);
    }

    /**
     * this method is used for upload files
     * set upload_type is const UPLOAD_FILES
     * 
     * @return upload()
     */
    public function uploadFile($link = false) {
        $this->upload_type = self::UPLOAD_FILES;
        return !$link ? $this->upload() : $this->uploadLink($link);
    }
    
    public function compressed($data = false) {
        UpFile::compressed($data);
    }

    /**
     * get response from server
     * json_decode this response property
     * @param boolen $decode : true: return json_decode
     *                    false: return json string
     * @return array
     */
    public function delelteFile($path, $file) {
        $curl = $this->cUrl;
        $this->host = HOST_MEDIA_IP . 'delete.php';
        $data = array('deletefile' => 1, 'path' => $path, 'file' => $file);
        curl_setopt($curl, CURLOPT_URL, $this->host);
        curl_setopt($curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10000);
        $responseBody = curl_exec($curl);
        $this->allResponse = $responseBody;
        $this->processResponse();
        return $this;
    }

    public function getAllResponse($decode = true) {
        if ($decode) {
            return json_decode($this->allResponse, true);
        } else {
            return $this->allResponse;
        }
    }

    /**
     * create path to upload follow user_id
     * upload with structure folder
     *  user_id/filename
     * @param $user_id
     * @return UploadLib $this
     */
    public function createPathFlUser($user_id = NULL) {
        $user_id = !$user_id ? Yii::$app->user->id : $user_id;
        $this->path = array('user', $user_id);
        return $this;
    }

    /**
     * process reponse result
     * assign to some properties
     * @return void
     */
    public function processResponse() {
        $result = $this->getAllResponse();
        $this->status = isset($result['status']) ? intval($result['status']) : NULL;
        $this->response = isset($result['response']) && is_array($result['response']) ? $result['response'] : array();
        $this->errors = (isset($this->response['error']) ? $this->response['error'] : array());
    }

    /**
     * get resize result from response
     */
    public function getResizeResponse() {
        if (isset($this->response['resize'])) {
            return $this->response['resize'];
        }
        return array();
    }

    /**
     * get size width and heigh of an image uploaded
     */
    public function getWidthHeigth() {
        $imagesize = $this->response;
        return array(
            isset($imagesize[0]) ? $imagesize[0] : null,
            isset($imagesize[1]) ? $imagesize[1] : null,
        );
    }

    /**
     * @author Phong Pham Hong <phongbro1805@gmail.com>
     * 
     * get folder with a param resize
     * @param array resize
     * @return string
     */
    public static function renderFolderResize($resize = array()) {
        return self::PRE_FIX_FOLDER_RESIZE . implode('_', $resize);
    }

    /**
     * get resize value param from Yii::params
     * 
     * @param string object
     * @return array
     */
    public static function getResizeParamFromYiiParams($object = null) {
        if(isset(Yii::$app->params['resize'][$object])) {
            $params = Yii::$app->params['resize'][$object]['size'];
            $flag = false;
            foreach($params as $array) {
                if($array[0] == 30 && $array[1] == 30) {
                    $flag = true;
                }
            }
            if($flag) {
                $params['v10'] = array(30,30);
            }
        } else {
            $params = array('v1' => array(30,30));
        }
        return array_values($params);
    }

    /**
     * 
     */
    public static function cronImageToDefaultSize($path, $file, $resize) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, HOST_MEDIA . '/cron_resize.php');
        $data = array(
            'path' => $path,
            'file' => $file,
            'resize' => $resize
        );
        curl_setopt($curl, CURLOPT_URL, HOST_MEDIA . '/cron_resize.php');
        curl_setopt($curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10000);
        $responseBody = curl_exec($curl);
        var_dump(curl_error($curl));
        var_dump($responseBody);
        curl_close($curl);
    }
    /**
     * get response from server
     * json_decode this response property
     * @param boolen $decode : true: return json_decode
     *                    false: return json string
     * @return array
     */
    public function uploadAvatarFb($user_id, $user_name) {
        $curl = $this->cUrl;
        $this->host = HOST_MEDIA_IP . 'avatar_fb.php';
        $data = ['user_id' => $user_id, 'user_name' => $user_name];
        curl_setopt($curl, CURLOPT_URL, $this->host);
        curl_setopt($curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10000);
        
        $responseBody = curl_exec($curl);
        $error = curl_error($curl);
        $this->allResponse = $responseBody;
        $this->processResponse();
        return $this;
    }
    
    /**
     * get response from server
     * json_decode this response property
     * @param boolen $decode : true: return json_decode
     *                    false: return json string
     * @return array
     */
    public function copy($from_path, $to_path) {
        $curl = $this->cUrl;
        $this->host = HOST_MEDIA_IP . 'copy_path.php';
        $data = array('from_path' => $from_path, 'to_path' => $to_path, 'delete'=>1);
        curl_setopt($curl, CURLOPT_URL, $this->host);
        curl_setopt($curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1000);
        $responseBody = curl_exec($curl);
        $this->allResponse = $responseBody;
        $this->processResponse();
        return $this;
    }
    /**
     * get response from server
     * json_decode this response property
     * @param boolen $decode : true: return json_decode
     *                    false: return json string
     * @return array
     */
    public function uploadImageOppFromUrl($feed_log_id, $url, $uniqueId) {
        $curl = $this->cUrl;
        $this->host = HOST_MEDIA_IP . 'upload_img_opp.php';
        $data = ['feed_log_id' => $feed_log_id, 'url' => $url, 'uniqueId'=>$uniqueId];
        curl_setopt($curl, CURLOPT_URL, $this->host);
        curl_setopt($curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10000);
        
        $responseBody = curl_exec($curl);
        $error = curl_error($curl);
        $this->allResponse = $responseBody;
        $this->processResponse();
        return $this;
    }
    
//    public function uploadLink($link, $name = false) {
//        $data = array();
//        $data['replace_name'] = $name ? UtilityHtmlFormat::stripUnicode($name) : false;
//        $data['resize'] = json_encode($this->resize);
//        $data['link'] = $link;
//        $data['table_name'] = $this->path[0];
//        if (!$data) {
//            return false;
//        }
//        $this->allResponse =  UpFile::link($data);
//        $this->processResponse();
//        return $this;
//    }
    
    
    
    public function uploadLink($link, $name = false) {
        /* set path default follow user_id */
        if (!$this->path) {
            $this->createPathFlUser();
        }
        // set curl POST options
        $curl = $this->cUrl;
        /* check data is true or not */
        $data = array();
        $data['replace_name'] = $name ? UtilityHtmlFormat::stripUnicode($name) : false;
        $data['resize'] = json_encode($this->resize);
        $data['link'] = $link;
        $data['table_name'] = $this->path[0];
        if (!$data) {
            return false;
        }
        curl_setopt($curl, CURLOPT_URL, HOST_MEDIA_IP . 'link.php');
        curl_setopt($curl, CURLOPT_NOBODY, FALSE);
        curl_setopt($curl, CURLOPT_POST, TRUE);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10000);

        $responseBody = curl_exec($curl);
        $this->allResponse = $responseBody;
        $this->processResponse();
        return $this;
    }

}
