<?php

/**
 * This class allows a user to upload and 
 * validate their files.
 *
 * @author John Ciacia <Sidewinder@extreme-hq.com>
 * @version 1.0
 * @copyright Copyright (c) 2007, John Ciacia
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * 
 * @edited: Phong Pham Hong <phongbro1805@gmail.com>
 * Editing content:
 *      + Add error code
 *      + Change error report
 *      + Add allowed mine type
 */
class UploadBase {

    /**
     * some properties for config
     */
    #override file
    #default is true
    public $IsOverride = true;

    /**
     * @var string contains the name of the file to be uploaded.
     */
    protected $FileName;

    /**
     * @var string contains the temporary name of the file to be uploaded.
     */
    protected $TempFileName;

    /**
     * @var string contains directory where the files should be uploaded.
     */
    protected $UploadDirectory;

    /**
     * @var string contains an array of valid extensions which are allowed to be uploaded.
     */
    protected $ValidExtensions;

    /**
     * @var string contains a message which can be used for debugging.
     */
    protected $Message;

    /**
     * @var integer contains maximum size of fiels to be uploaded in bytes.
     */
    protected $MaximumFileSize;

    /**
     * @var bool contains whether or not the files being uploaded are images.
     */
    protected $IsImage;

    /**
     * @var string contains the email address of the recipient of upload logs.
     */
    protected $Email;

    /**
     * @var integer contains maximum width of images to be uploaded.
     */
    protected $MaximumWidth;

    /**
     * @var integer contains maximum height of images to be uploaded.
     */
    protected $MaximumHeight;

    /**
     * @var getimagesize 
     */
    protected $GetImageSize;

    /**
     * original file name
     * 
     * @var string
     */
    protected $OriginalName;

    /**
     * extension of file
     * 
     * @var string
     */
    protected $FileExtension;

    /**
     * file size
     */
    protected $FileSize;

    /**
     * allowed header
     * @var type 
     */
    public $allowed = array(
        'application/arj',
        'application/excel',
        'application/gnutar',
        'application/mspowerpoint',
        'application/msword',
        'application/octet-stream',
        'application/onenote',
        'application/pdf',
        'application/plain',
        'application/postscript',
        'application/powerpoint',
        'application/rar',
        'application/rtf',
        'application/vnd.ms-excel',
        'application/vnd.ms-excel.addin.macroEnabled.12',
        'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
        'application/vnd.ms-excel.sheet.macroEnabled.12',
        'application/vnd.ms-excel.template.macroEnabled.12',
        'application/vnd.ms-office',
        'application/vnd.ms-officetheme',
        'application/vnd.ms-powerpoint',
        'application/vnd.ms-powerpoint.addin.macroEnabled.12',
        'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
        'application/vnd.ms-powerpoint.slide.macroEnabled.12',
        'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
        'application/vnd.ms-powerpoint.template.macroEnabled.12',
        'application/vnd.ms-word',
        'application/vnd.ms-word.document.macroEnabled.12',
        'application/vnd.ms-word.template.macroEnabled.12',
        'application/vnd.oasis.opendocument.chart',
        'application/vnd.oasis.opendocument.database',
        'application/vnd.oasis.opendocument.formula',
        'application/vnd.oasis.opendocument.graphics',
        'application/vnd.oasis.opendocument.graphics-template',
        'application/vnd.oasis.opendocument.image',
        'application/vnd.oasis.opendocument.presentation',
        'application/vnd.oasis.opendocument.presentation-template',
        'application/vnd.oasis.opendocument.spreadsheet',
        'application/vnd.oasis.opendocument.spreadsheet-template',
        'application/vnd.oasis.opendocument.text',
        'application/vnd.oasis.opendocument.text-master',
        'application/vnd.oasis.opendocument.text-template',
        'application/vnd.oasis.opendocument.text-web',
        'application/vnd.openofficeorg.extension',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.openxmlformats-officedocument.presentationml.slide',
        'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'application/vnd.openxmlformats-officedocument.presentationml.template',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'application/vocaltec-media-file',
        'application/wordperfect',
        'application/x-bittorrent',
        'application/x-bzip',
        'application/x-bzip2',
        'application/x-compressed',
        'application/x-excel',
        'application/x-gzip',
        'application/x-latex',
        'application/x-midi',
        'application/xml',
        'application/x-msexcel',
        'application/x-rar',
        'application/x-rar-compressed',
        'application/x-rtf',
        'application/x-shockwave-flash',
        'application/x-sit',
        'application/x-stuffit',
        'application/x-troff-msvideo',
        'application/x-zip',
        'application/x-zip-compressed',
        'application/zip',
        'audio/*',
        'image/*',
        'multipart/x-gzip',
        'multipart/x-zip',
        'text/plain',
        'text/rtf',
        'text/richtext',
        'text/xml',
        'video/*'
    );
    public $mime_types = array(
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpe' => 'image/jpeg',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'bmp' => 'image/bmp',
        'flv' => 'video/x-flv',
        'js' => 'application/x-javascript',
        'json' => 'application/json',
        'tiff' => 'image/tiff',
        'css' => 'text/css',
        'xml' => 'application/xml',
        'doc' => 'application/msword',
        'docx' => 'application/msword',
        'csv' => 'application/vnd.ms-excel',
        'xls' => 'application/vnd.ms-excel',
        'xlt' => 'application/vnd.ms-excel',
        'xlm' => 'application/vnd.ms-excel',
        'xld' => 'application/vnd.ms-excel',
        'xla' => 'application/vnd.ms-excel',
        'xlc' => 'application/vnd.ms-excel',
        'xlw' => 'application/vnd.ms-excel',
        'xll' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pps' => 'application/vnd.ms-powerpoint',
        'rtf' => 'application/rtf',
        'pdf' => 'application/pdf',
        'html' => 'text/html',
        'htm' => 'text/html',
        'php' => 'text/html',
        'txt' => 'text/plain',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpe' => 'video/mpeg',
        'mp3' => 'audio/mpeg3',
        'wav' => 'audio/wav',
        'aiff' => 'audio/aiff',
        'aif' => 'audio/aiff',
        'avi' => 'video/msvideo',
        'wmv' => 'video/x-ms-wmv',
        'mov' => 'video/quicktime',
        'zip' => 'application/zip',
        'rar' => 'application/octet-stream',
        'tar' => 'application/x-tar',
        'swf' => 'application/x-shockwave-flash',
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ott' => 'application/vnd.oasis.opendocument.text-template',
        'oth' => 'application/vnd.oasis.opendocument.text-web',
        'odm' => 'application/vnd.oasis.opendocument.text-master',
        'odg' => 'application/vnd.oasis.opendocument.graphics',
        'otg' => 'application/vnd.oasis.opendocument.graphics-template',
        'odp' => 'application/vnd.oasis.opendocument.presentation',
        'otp' => 'application/vnd.oasis.opendocument.presentation-template',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'ots' => 'application/vnd.oasis.opendocument.spreadsheet-template',
        'odc' => 'application/vnd.oasis.opendocument.chart',
        'odf' => 'application/vnd.oasis.opendocument.formula',
        'odb' => 'application/vnd.oasis.opendocument.database',
        'odi' => 'application/vnd.oasis.opendocument.image',
        'oxt' => 'application/vnd.openofficeorg.extension',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
        'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
        'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
        'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
        'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
        'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
        'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
        'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
        'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
        'sldm' => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
        'thmx' => 'application/vnd.ms-officetheme',
        'onetoc' => 'application/onenote',
        'onetoc2' => 'application/onenote',
        'onetmp' => 'application/onenote',
        'onepkg' => 'application/onenote',
    );

    /**
     *
     * @var array error code
     */
    public $errorCode = array(
        'name_empty' => 'File name is empty',
        'extension' => 'This file is invalid extension',
        'file_invalid' => 'This file is not allowed to upload.',
        'size_empty' => 'File size is empty',
        'no_dir' => 'The dictionary does not exist',
        'no_permission' => 'Permission denied for directories created',
        'over_size' => 'File is too big.',
        'width_over' => 'The width of the image exceeds the maximum size',
        'height_over' => 'The height of the image exceeds the maximum size',
        'file_exist' => 'This file is exist',
        '' => 'Unknow error'
    );

    /**
     * contain all error messages
     */
    protected $errors = array();

    /**
     * return message error with specific code
     * @param string $code
     * @return string
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * store messages error 
     * 
     * @param string $code
     */
    public function setErrors($code) {
        if (is_array($this->errors)) {
            $this->errors = array();
        }
        $text = isset($this->errorCode[$code]) ? $this->errorCode[$code] : '';
        switch ($code) {
            case 'extension':
                $text .= ' Allowed extensions: ' . implode(',', $this->ValidExtensions);
                break;
            case 'over_size':
                $text .= ' Maximum size is ' . self::formatBytes($this->MaximumFileSize);
                break;
        }
        $this->errors[] = $text;
    }

    /**
     * @method returns whether the extension of file to be uploaded
     *    is allowable or not.
     * @return true the extension is valid.
     * @return false the extension is invalid.
     */
    function ValidateExtension() {

        $FileName = trim($this->FileName);
        if ($FileName) {
            $FileParts = pathinfo($FileName);
            $this->FileExtension = strtolower($FileParts['extension']);
            $this->FileName = $FileParts['filename'].'.'. $this->FileExtension;
            $ValidExtensions = $this->ValidExtensions;

            if (!$this->ValidateExtensionFromSystem() || (is_array($ValidExtensions) && count($ValidExtensions) > 0 && !in_array($this->FileExtension, $ValidExtensions))) {
                $this->setErrors('extension');
                return false;
            }
            return true;
        }
        $this->setErrors('name_empty');
        return false;
    }

    /**
     * valiate file extension from array extension that system allow $this->mime_types
     * need FileExtension is setted
     * @return true if this file mine type is valid
     */
    function ValidateExtensionFromSystem() {
        $ext = $this->FileExtension;
        if ($ext != '' && isset($this->mime_types[$ext])) {
            return true;
        }
        return false;
    }

    function ValidateSize() {
        $MaximumFileSize = $this->MaximumFileSize;
        $TempFileName = $this->GetTempName();
        if ($TempFileName) {
            $TempFileSize = filesize($TempFileName);
            $this->FileSize = $TempFileSize;
            if (is_numeric($MaximumFileSize) && $MaximumFileSize <= $TempFileSize) {
                $this->setErrors('over_size');
                return false;
            }
            return true;
        }
        return false;
    }

    function ValidateExistance() {
        $FileName = $this->FileName;
        $UploadDirectory = $this->UploadDirectory;
        $File = $UploadDirectory . $FileName;
        /* save original file name */
        $this->OriginalName = $FileName;

        if (!$this->IsOverride && $FileName != '' && file_exists($File)) {
            $this->setErrors('file_exist');
            return false;
        } else {
            $UniqueName = $this->getFileNameNew($UploadDirectory, $FileName);
            $this->SetFileName($UniqueName);
        }
        return TRUE;
    }
    
    function getFileNameNew($UploadDirectory, $FileName) {
        $extension = pathinfo($FileName,PATHINFO_EXTENSION);
        $name = pathinfo($FileName, PATHINFO_FILENAME);
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

    /**
     * create directory
     */
    function createDirectory() {
        $UploadDirectory = $this->GetUploadDirectory();
        if (substr($UploadDirectory, -1) != "/") {
            $NewDirectory = $UploadDirectory . "/";
            $this->SetUploadDirectory($NewDirectory);
        }
        $this->rmkdir($this->getFullDir());
        return true;
    }

    /**
     * @return true the image is smaller than the alloted dimensions.
     * @return false the width and/or height is larger then the alloted dimensions.
     */
    function ValidateImage() {
        $MaximumWidth = $this->MaximumWidth;
        $MaximumHeight = $this->MaximumHeight;
        $TempFileName = $this->TempFileName;
        $this->GetImageSize = @getimagesize($TempFileName);

        if ($this->GetImageSize) {
            //$Width is the width in pixels of the image uploaded to the server.
            //$Height is the height in pixels of the image uploaded to the server.
            list($Width, $Height) = $this->GetImageSize;
            if ($MaximumWidth && $Width > $MaximumWidth) {
                $this->setErrors('width_over');
                return false;
            }

            if ($MaximumHeight && $Height > $MaximumHeight) {
                $this->setErrors('height_over');
                return false;
            }
            if ($Height == 0 && $Width == 0) {
                $this->setErrors('size_empty');
                return false;
            }
        }
        return true;
    }

    /**
     * @method  uploads the file to the server after passing all the validations.
     * @validate file with :   
     *  + validate extension
     *  + validate size
     *  + validate existance
     *  + validate image source
     * @debug   
      //         var_dump($this->ValidateExtension(),
      //          $this->ValidateSize(),
      //          $this->ValidateExistance(),
      //           $this->ValidateImage());
     * @return true the file was uploaded.
     * @return false the upload failed.
     */
    function UploadFile() {
        if (
                $this->ValidateExtension() &&
                $this->ValidateSize() &&
                $this->ValidateExistance() &&
                $this->ValidateImage()
        ) {
            $FileName = $this->FileName;
            $TempFileName = $this->TempFileName;
            $UploadDirectory = $this->getFullDir(); //var_dump($FileName,$TempFileName,$UploadDirectory);die('ss');
            /* create dir */
            $this->createDirectory();
            if (copy($TempFileName, $UploadDirectory . $FileName)) {
                return true;
            }
        }
        return false;
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
    public function _mkdir($path, $mode = 0775, $owner = 'apache') {
        $old = umask(0);
        $res = @mkdir($path, $mode);
        chown($path, $owner);
        umask($old);
        return $res;
    }

    /**
     * get full dir
     * include __DIR__
     * @return string
     */
    public function getFullDir() {
        return $this->GetUploadDirectory();
    }

    public static function formatBytes($bytes, $precision = 2) {
        $base = log($bytes) / log(1024);
        $def = array("", "k", "M", "G", "T");
        $suffix = $def[floor($base)];
        return round(pow(1024, $base - floor($base)), $precision) . $suffix;
    }

    #Accessors and Mutators beyond this point.
    #Siginificant documentation is not needed.

    function SetFileName($argv) {
        $this->FileName = trim($argv);
    }

    function SetUploadDirectory($argv) {
        $this->UploadDirectory = $argv;
    }

    function SetTempName($argv) {
        $this->TempFileName = $argv;
    }

    function SetValidExtensions($argv) {
        $this->ValidExtensions = $argv;
    }

    function SetMessage($argv) {
        $this->Message = $argv;
    }

    function SetMaximumFileSize($argv) {
        $this->MaximumFileSize = $argv;
    }

    function SetEmail($argv) {
        $this->Email = $argv;
    }

    function SetIsImage($argv) {
        $this->IsImage = $argv;
    }

    function SetMaximumWidth($argv) {
        $this->MaximumWidth = $argv;
    }

    function SetMaximumHeight($argv) {
        $this->MaximumHeight = $argv;
    }

    function GetFileName() {
        return $this->FileName;
    }

    function GetUploadDirectory() {
        return $this->UploadDirectory;
    }

    function GetTempName() {
        return $this->TempFileName;
    }

    function GetValidExtensions() {
        return $this->ValidExtensions;
    }

    function GetMessage() {
        return $this->Message;
    }

    function GetMaximumFileSize() {
        return $this->MaximumFileSize;
    }

    function GetEmail() {
        return $this->Email;
    }

    function GetIsImage() {
        return $this->IsImage;
    }

    function GetMaximumWidth() {
        return $this->MaximumWidth;
    }

    function GetMaximumHeight() {
        return $this->MaximumHeight;
    }

    function GetImageSize() {
        return $this->GetImageSize;
    }

    public function getOriginalName() {
        return $this->OriginalName;
    }

    public function getFileExtension() {
        return $this->FileExtension;
    }

    public function getFileSize() {
        return $this->FileSize;
    }

}
