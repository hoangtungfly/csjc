<?php

/*
 * Edit images
 *
 * Edit by: PhongPh
 * Date: 07/06/2013
 */

class ResizeImagick {

    CONST QUALITY_JPEG = 70;

    /*
     * set option
     */

    public $enableShappen = true;

    /**
     * @var int
     */
    public $width;

    /**
     * @var int
     */
    public $height;

    /**
     * @var int
     */
    public $quality;

    /**
     * @var array
     */
    public $crop = array();

    /**
     * width of original image
     */
    public $oriWidth;

    /**
     * height of original image
     */
    public $oriHeight;

    /**
     * information of file
     */
    public $fileInfo;
    /*
     * @var string
     */
    protected $extension;

    /**
     * @var Imagick
     */
    protected $imagick = null;

    /**
     * @var string;
     */
    protected $imageResouce;

    /**
     * param for crop function
     * 
     * @var array
     */
    public $paramCrop = array();

    /**
     * file thumb name
     * 
     * @var string
     */
    protected $thumbName;

    /**
     * store errors 
     * 
     * @var string
     */
    protected $error;

    /**
     * Returns the GD image resource
     *
     * @return resource
     */
    public function getResource() {
        return $this->imagick;
    }

    /**
     * store error into error property
     * set error 
     * only replace new error when current error property is null and param $string is not null
     * 
     * @param string $string
     */
    public function setErros($string) {
        $this->error = $string;
    }

    /**
     * get error message
     */
    public function getError() {
        return $this->error;
    }

    /**
     * error code
     * 
     * @return string error
     */
    public function errorCode($code) {
        $error = array(
            2 => 'No resource file',
            3 => 'Not supported',
            4 => 'No image set',
            5 => 'Invalid size',
            6 => 'No dir'
        );
        return isset($error[$code]) ? $error[$code] : 'Unknow error';
    }

    /**
     * Set image resource from file
     * Set property imagick
     *
     * @param string $file Path to image file
     * @return ResizeImagick for a fluent interface
     */
    public function setImageFile($file) {
        if (!(is_readable($file) && is_file($file))) {
            $this->setErros($this->errorCode(2));
            return false;
        }
        $this->imageResouce = $file;
        $this->fileInfo = getimagesize($file);

        list ($this->oriWidth, $this->oriHeight) = $this->fileInfo;
        try {
            $this->setImagickObject();
        } catch (Exception $e) {
            $this->setErros($e->getMessage());
        }
        return $this;
    }

    /**
     * @return ResizeImagick
     */
    public function setImagickObject() {
        $this->imagick = new Imagick($this->imageResouce);
        $this->imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $this->imagick->setimagecompressionquality(self::QUALITY_JPEG);
        return $this;
    }

    /*
     * phongph
     * apply a sharpen filter for image
     * @return ResizeImagick
     */

    public function applySharpen() {
        $this->imagick->stripImage();
        // PHOTOSHOP
        $this->imagick->unsharpMaskImage(0, 0.5, 1, 0.05);
        return $this;
    }

    /**
     * resize the current image
     *
     * resize with correct width and height
     * @return ResizeImagick
     */
    public function startResize() {
        $width = intval($this->width);
        $height = intval($this->height);

        if ($this->imagick == null) {
            $this->setErros($this->errorCode(4));
            return false;
        }
        if ($width == 0 && $height == 0) {
            $this->setErros($this->errorCode(5));
            return false;
        }
        try {
            if (!$width || !$height) {
                $this->imagick->thumbnailimage($width, $height);
            } else {
                $this->imagick->cropThumbnailImage($width, $height);
            }
        } catch (Exception $e) {
            $this->setErros($e->getMessage());
        }
        return $this;
    }

    /**
     * Crop image
     *
     * @param int|array $x1 Top left x-coordinate of crop box or array of coordinates
     * @param int       $y1 Top left y-coordinate of crop box
     * @param int       $x2 Bottom right x-coordinate of crop box
     * @param int       $y2 Bottom right y-coordinate of crop box
     * @return ResizeImagick
     */
    public function crop() {
        list($x1, $y1, $x2, $y2) = $this->paramCrop;

        if (!$this->imagick) {
            $this->setErros($this->errorCode(4));
            return false;
        }
        if (is_array($x1) && 4 == count($x1)) {
            list($x1, $y1, $x2, $y2) = $x1;
        }

        $x1 = max($x1, 0);
        $y1 = max($y1, 0);

        $x2 = min($x2, $this->oriWidth);
        $y2 = min($y2, $this->oriHeight);

        $width = $x2 - $x1;
        $height = $y2 - $y1;
        try {
            $this->imagick->cropimage($width, $height, $x1, $y1);
        } catch (Exception $e) {
            $this->setErros($e->getMessage());
        }
        return $this;
    }

    /**
     * Save current image to file
     *
     * @param string $fileName
     * @return void
     * @throws RuntimeException
     */
    public function save($fileName) {
        if ($this->getError()) {
            return false;
        }
        try {
            /* create folder thumb */
            $path = explode(DS, $fileName);
            unset($path[count($path) - 1]);
            $this->rmkdir(implode(DS, $path));
            if ($this->enableShappen) {
                $this->applySharpen();
            }
            $this->imagick->writeImage($fileName);
            return true;
        } catch (Exception $e) {
            $this->setErros($e->getMessage());
        }
        return false;
    }

    /*
     * get file name of an url
     * @param URL string
     * @reutn string
     */

    public static function getFileNameFrUrl($url) {
        $a = explode('/', $url);
        return $a[count($a) - 1];
    }

    /**
     * @phongph
     * Creates directories recursively
     *
     * @access private
     * @param  string  $path Path to create
     * @param  integer $mode Optional permissions
     * @return boolean Success
     */
    protected function rmkdir($path, $mode = 0775) {
        return is_dir($path) || ( $this->rmkdir(dirname($path), $mode) && $this->_mkdir($path, $mode) );
    }

    /**
     * @phongph
     * Creates directory
     *
     * @access private
     * @param  string  $path Path to create
     * @param  integer $mode Optional permissions
     * @return boolean Success
     */
    protected function _mkdir($path, $mode = 0775) {
        $old = umask(0);
        $res = @mkdir($path, $mode);
        umask($old);
        return $res;
    }

    /**
     * 
     */
    public function __destruct() {
        if ($this->imagick) {
            $this->imagick->destroy();
        }
    }

}
