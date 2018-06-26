<?php

/*
 * Edit images
 *
 * Edit by: PhongPh
 * Date: 07/06/2013
 */
class Resize {

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
     * @var resource
     */
    protected $image;

    /**
     * @var replaceImage;
     */
    protected $replaceImage;

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
        return $this->image;
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
     *
     * @param string $file Path to image file
     * @return ImageManipulator for a fluent interface
     * @throws InvalidArgumentException
     */
    public function setImageFile($file) {
        if (!(is_readable($file) && is_file($file))) {
            $this->setErros($this->errorCode(2));
            return false;
        }
        
        if (is_resource($this->image)) {
            imagedestroy($this->image);
        }
        $this->fileInfo = getimagesize($file);
        list ($this->oriWidth, $this->oriHeight, $type) = $this->fileInfo;

        switch ($type) {
            case IMAGETYPE_GIF :
                $this->image = imagecreatefromgif($file);
                break;
            case IMAGETYPE_JPEG :
                $this->image = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG :
                $this->image = imagecreatefrompng($file);
                break;
            default :
                $this->setErros($this->errorCode(3));
                return false;
        }

        return $this;
    }

    /**
     * 
     * @param type $width
     * @param type $height
     * @return type
     */
    protected function buildSampledImage($width, $height) {

        $temp = imagecreatetruecolor($width, $height);
        imagecopyresampled($temp, $this->image, 0, 0, 0, 0, $width, $height, $this->oriWidth, $this->oriHeight);
        return $this->_replace($temp);
    }

    /**
     * resize the current image
     *
     * resize with correct width and height
     * @return ImageManipulator for a fluent interface
     * @throws RuntimeException
     */
    public function startResize() {
        $width = intval($this->width);
        $height = intval($this->height);

        if (!is_resource($this->image)) {
            $this->setErros($this->errorCode(4));
            return false;
        }
        if ($width > 0 && $height == 0) {
            $height = round($width / $this->oriWidth * $this->oriHeight);
        } else if ($height > 0 && $width == 0) {
            $width = round($height / $this->oriHeight * $this->oriWidth);
        } else if ($width == 0 && $height == 0) {
            $this->setErros($this->errorCode(5));
            return false;
        }
        $this->buildSampledImage($width, $height);
    }

    /**
     * Crop image
     *
     * @param int|array $x1 Top left x-coordinate of crop box or array of coordinates
     * @param int       $y1 Top left y-coordinate of crop box
     * @param int       $x2 Bottom right x-coordinate of crop box
     * @param int       $y2 Bottom right y-coordinate of crop box
     * @return ImageManipulator for a fluent interface
     * @throws RuntimeException
     */
    public function crop() {
        list($x1, $y1, $x2, $y2) = $this->paramCrop;

        if (!is_resource($this->image)) {
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

        $temp = imagecreatetruecolor($width, $height);
        imagecopy($temp, $this->image, 0, 0, $x1, $y1, $width, $height);

        return $this->_replace($temp);
    }

    /**
     * Replace current image resource with a new one
     *
     * @param resource $res New image resource
     * @return ImageManipulator for a fluent interface
     * @throws UnexpectedValueException
     */
    protected function _replace($res) {
        if (!is_resource($res)) {
            $this->setErros($this->errorCode(2));
            return false;
        }
        if (is_resource($this->replaceImage)) {
            imagedestroy($this->replaceImage);
        }
        $this->replaceImage = $res;

        return $this;
    }

    /*
     * phongph
     * apply a sharpen filter for image
     * @param resource 
     * @return resource
     */

    public function applySharpen($temp) {
        $sharpen = array
            (
            array(-1, -1, -1),
            array(-1, 16, -1),
            array(-1, -1, -1),
        );
        // Calculate the sharpen divisor
        $divisor = array_sum(array_map('array_sum', $sharpen));
        imageconvolution($temp, $sharpen, $divisor, 0);
        return $temp;
    }

    /**
     * Save current image to file
     *
     * @param string $fileName
     * @return void
     * @throws RuntimeException
     */
    public function save($fileName, $type = IMAGETYPE_JPEG) {
        if ($this->getError()) {
            return false;
        }
        /* create folder thumb */
        $path = explode(DS, $fileName);
        unset($path[count($path) - 1]);
        $this->rmkdir(implode(DS, $path));
        /*
         * apply a filter shappen
         */
        if ($this->enableShappen)
            $this->replaceImage = $this->applySharpen($this->replaceImage);

        switch ($type) {
            case IMAGETYPE_GIF :
                if (!@imagegif($this->replaceImage, $fileName)) {
                    $this->setErros($this->errorCode(4));
                    return false;
                }
                break;
            case IMAGETYPE_PNG :
                if (!@imagepng($this->replaceImage, $fileName)) {
                    $this->setErros($this->errorCode(4));
                    return false;
                }
                break;
            case IMAGETYPE_JPEG :
            default :
                $quality = (is_numeric($this->quality) && $this->quality > 0) ? $this->quality : self::QUALITY_JPEG;
                if (!@imagejpeg($this->replaceImage, $fileName, $quality)) {
                    $this->setErros($this->errorCode(4));
                    return false;
                }
        }

        return true;
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
    public function rmkdir($path, $mode = 0775) {
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
    public function _mkdir($path, $mode = 0775) {
        $old = umask(0);
        $res = @mkdir($path, $mode);
        umask($old);
        return $res;
    }

}
