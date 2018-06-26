<?php

/**
 * @author: PhongPhamHong<phongbro1805@gmail.com>
 * Class for upload handel on server

 * overrided from:
 *  @author John Ciacia <Sidewinder@extreme-hq.com>
 *  @version 1.0
 *  @copyright Copyright (c) 2007, John Ciacia
 *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 * @date: 12/23/2013
 */
require './UploadBase.php';
if(CHECK_IMAGICK) {
    require './ResizeImagick.php';
} else {
    require './Resize.php';
}

class Uploadhandel extends UploadBase {

    /**
     * default max size
     * 
     * 1GB
     */
    const MAX_FILE_SIZE = 131072000;

    /**
     * type upload
     */
    const UPLOAD_IMAGE = 1;
    const UPLOAD_FILES = 2;

    /**
     * folder prefix name for resize and crop
     */
    const PRE_FIX_FOLDER_RESIZE = '';
    const PRE_FIX_FOLDER_CROP = '';

    /**
     * upload type
     */
    protected $uploadType;

    /**
     * array allowed extension for upload image
     */
    protected $allowedImgExt = array('gif', 'jpg', 'jpeg', 'png', 'PNG', 'JPEG', 'JPG', 'GIF');

    /**
     * base DIR folder to upload
     */
    public function getBaseDir() {
        return APPLICATION_PATH;
    }

    /**
     * get base DIR for image folder
     */
    public function getBaseDirImage() {
        return $this->getBaseDir() . 'images' . DS;
    }

    /**
     * get base DIR for files
     */
    public function getBaseDirFile() {
        return $this->getBaseDir() . 'images' . DS;
    }

    /**
     * true: create thumb
     * false: not create thumb
     * default is true
     * 
     * @var boolen
     */
    protected $createThumb = true;

    /**
     * dir upload
     */
    protected $dirUpload;

    /**
     * resize image object
     * 
     * @var ResizeImagick
     */
    protected $resizeModel;

    public function getUploadType() {
        return $this->uploadType;
    }

    public function getCreateThumb() {
        return $this->createThumb;
    }

    public function setUploadType($uploadType) {
        $this->uploadType = $uploadType;
    }

    public function setCreateThumb($createThumb) {
        $this->createThumb = $createThumb;
    }

    public function getResizeModel() {
        return $this->resizeModel;
    }

    public function getDirUpload() {
        return $this->dirUpload;
    }

    public function setDirUpload($dirUpload) {
        $this->dirUpload = $dirUpload;
    }

    /**
     * @phongph
     * 
     * construct
     */
    public function __construct($_FILE) {
        /* validate $_FILT */
        if (!isset($_FILE['tmp_name'])) {
            $this->setErrors('name_empty');
            return false;
        }
        /**
         * set defaul values
         */
        $this->SetFileName($_FILE['name']);
        $this->SetTempName($_FILE['tmp_name']);
    }

    /**
     * @phongph
     * 
     * start upload images
     *  + set some default value like maxumum file size, SetValidExtensions
     *  + set SetUploadDirectory
     * @param type $server_path
     * @return boolen
     */
    public function save() {
        /* set max file size */
        if ($this->MaximumFileSize > self::MAX_FILE_SIZE) {
            $this->MaximumFileSize = self::MAX_FILE_SIZE;
        }
        /* if upload image */
        if ($this->uploadType === self::UPLOAD_IMAGE) {
            /* set allowed extension */
            if (!$this->ValidExtensions) {
                $this->SetValidExtensions($this->allowedImgExt);
            }
            $this->SetUploadDirectory($this->getBaseDirImage());
        } else {

            $this->SetUploadDirectory($this->getBaseDirFile());
        }
        /* set dir to upload */
        $this->buildDirFromParam();
        /* start upload */
        $result = $this->UploadFile();
        if ($result && $this->uploadType == self::UPLOAD_IMAGE && $this->createThumb) {
            if(CHECK_IMAGICK) {
                $this->resizeModel = new ResizeImagick();
            } else {
                $this->resizeModel = new Resize();
            }
            try {
                $path = pathinfo($this->FileName);
                $ext = strtolower($path['extension']);
                $filename = $path['filename'].'.'.$ext;
                $this->resizeModel->setImageFile($this->getFullDir() . $filename);
            } catch (Exception $e) {
                //  $this->resizeModel = null;
            }
        } else {
            $this->resizeModel = null;
        }

        return $result;
    }

    /**
     * @phongph
     * render file thumb name
     * Dir is:
     *  + if resize with width and height. Folder name is started with prefix 's'
     *  + if resize crop. Folder name is started with prefix 'c'
     *  + folder located in folder of original file
     * @return array  
     */
    public function renderFileThumb($size = array()) {
        $count = count($size);
        $dir = $this->GetUploadDirectory();
        $folderResize = null;
        $thumbname = $this->FileName;
        if ($count == 1) {
            $folderResize = self::PRE_FIX_FOLDER_RESIZE . array_shift($size);
        } else if ($count == 2) {
            list($w, $h) = $size;
            $w = intval($w);
            $h = intval($h);
            $folderResize = self::PRE_FIX_FOLDER_RESIZE . $w . 'x' . $h;
        } else if ($count > 2) {
            list($x1, $x2, $y1, $y2) = $size;
            $x1 = intval($x1);
            $x2 = intval($x2);
            $y1 = intval($y1);
            $y2 = intval($y2);
            $folderResize = self::PRE_FIX_FOLDER_CROP . $x1 . 'x' . $x2 . 'x' . $y1 . 'x' . $y2;
        }
        $dir = str_replace('main/','',$dir);
        $dir = $dir . $folderResize . DS;

        return array(
            'fulldir' => $dir . $thumbname,
            'folder' => $folderResize,
            'file' => $thumbname
        );
    }

    /**
     * @phongph
     * get file name without extension
     * 
     * @param string name
     */
    public function getOriginalFileName() {
        $path = pathinfo($this->FileName);
        return isset($path['filename']) ? $path['filename'] : null;
    }

    /**
     * @phongph
     * 
     * create dir from array to upload
     * @param Uploadhandel dirUpload
     * @return string dir
     */
    public function buildDirFromParam() {
        $dir = $this->GetUploadDirectory();
        if (is_array($this->dirUpload)) {
            $a = $this->dirUpload;
            foreach($a as $key => $value) {
                if(!$value) {
                    unset($this->dirUpload[$key]);
                }
            }
            $dir .= implode(DS, $this->dirUpload) . DS;
        }
        $this->SetUploadDirectory($dir);
    }

}
