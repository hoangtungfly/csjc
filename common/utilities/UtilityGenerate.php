<?php

/**
 * @description Process string
 *
 * @author phongphamhong
 * @date 11.27.2013
 */

namespace common\utilities;

class UtilityGenerate {

    static protected $key = 'utopia';
    public $numeric = array(
        1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 0 => 0
    );
    public $alpha = array(
        1 => "a", 30 => "r", 55 => "I",
        2 => "b", 31 => "s",
        3 => "c", 32 => "t",
        4 => "d", 33 => "u",
        5 => "e", 34 => "v",
        10 => "f", 35 => "w",
        11 => "g", 40 => "x",
        12 => "h", 41 => "y",
        13 => "i", 42 => "z",
        14 => "j", 43 => "A",
        15 => "k", 44 => "B",
        20 => "l", 45 => "C",
        21 => "m", 50 => "D",
        22 => "n", 51 => "E",
        23 => "o", 52 => "F",
        24 => "p", 53 => "G",
        25 => "q", 54 => "H"
    );
    public $alpha_re = array(
        "a" => "01", "r" => "30", "I" => "55",
        "b" => "02", "s" => "31",
        "c" => "03", "t" => "32",
        "d" => "04", "u" => "33",
        "e" => "05", "v" => "34",
        "f" => "10", "w" => "35",
        "g" => "11", "x" => "40",
        "h" => "12", "y" => "41",
        "i" => "13", "z" => "42",
        "j" => "14", "A" => "43",
        "k" => "15", "B" => "44",
        "l" => "20", "C" => "45",
        "m" => "21", "D" => "50",
        "n" => "22", "E" => "51",
        "o" => "23", "F" => "52",
        "p" => "24", "G" => "53",
        "q" => "25", "H" => "54",
    );

    /**
     * @phongph
     * generate a random sort code
     * @param int $lenght lenght of string code that you want to generate
     *            - Min is 3
     * @return string
     */
    public static function randomCode($lenght = 6) {
        $g = new UtilityGenerate();
        if ($lenght >= 2) {
            $string = '';
            $meger = $g->numeric + $g->alpha_re;
            for ($i = 0; $i <= $lenght; $i++) {
                $string = $string . array_rand($meger);
            }
            return $string;
        }
        return false;
    }

    /*
     * @author: minhbn
     * @email: minhbachngoc@orenj.com
     * @date: 02/01/2014 
     * @edit Phong Pham Hong
     * add more userid
     */

    // encrypt 
    static function encrypt($string, $keyCode = null) {
        $user = Yii::app()->user->id;
        if (is_numeric($keyCode) && $keyCode) {
            $user = $keyCode;
        }
        $key = md5((int) $user + (int) $user % 5);
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return self::encode_base64($result);
    }

    // decrypt
    static function decrypt($string, $keyCode = null) {
        $user = Yii::app()->user->id;
        if (is_numeric($keyCode) && $keyCode) {
            $user = $keyCode;
        }
        $key = md5((int) $user + (int) $user % 5);
        $result = '';
        $string = self::decode_base64($string);
        //
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    // permanent encrypt
    static function pe_encrypt($string) {
        $key = md5(Yii::app()->user->id) . self::$key;
        $result = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }
        return self::encode_base64($result);
    }

    // decrypt
    static function pe_decrypt($string) {
        $key = md5(Yii::app()->user->id) . self::$key;
        $result = '';
        $string = self::decode_base64($string);

        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key)) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }
        return $result;
    }

    //
    //base64
    static function encode_base64($sData) {
        $sBase64 = base64_encode($sData);
        return str_replace('=', '', strtr($sBase64, '+/', '-_'));
    }

    static function decode_base64($sData) {
        $sBase64 = strtr($sData, '-_', '+/');
        return base64_decode($sBase64 . '==');
    }

    /**
     * Get unqid code
     */
    public static function getUniqueCode() {
        return uniqid(time() . rand());
    }

    //* end minhbn

    public static function byteFormat($bytes, $unit = "", $decimals = 2) {
        $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4,
            'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);

        $value = 0;
        if ($bytes > 0) {
            // Generate automatic prefix by bytes 
            // If wrong prefix given
            if (!array_key_exists($unit, $units)) {
                $pow = floor(log($bytes) / log(1024));
                $unit = array_search($pow, $units);
            }

            // Calculate byte value by prefix
            $value = ($bytes / pow(1024, floor($units[$unit])));
        }

        // If decimals is not numeric or decimals is less than 0 
        // then set default value
        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }

        // Format output
        return sprintf('%.' . $decimals . 'f ' . $unit, $value);
    }

    /**
     * get folder size
     * @param type $path
     * @return int
     */
    public static function foldersize($path) {
        $total_size = 0;
        if (!is_dir($path)) {
            return $total_size;
        }
        $files = scandir($path);
        $cleanPath = rtrim($path, '/') . '/';

        foreach ($files as $t) {
            if ($t <> "." && $t <> "..") {
                $currentFile = $cleanPath . $t;
                if (is_dir($currentFile)) {
                    $size = self::foldersize($currentFile);
                    $total_size += $size;
                } else {
                    $size = filesize($currentFile);
                    $total_size += $size;
                }
            }
        }
        return $total_size;
    }

    /**
     * 
     * @param type $dir
     */
    public static function rrmdir($dir) {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file)
                if ($file != "." && $file != "..") {
                    self::rrmdir("$dir/$file");
                }
            rmdir($dir);
        } else if (file_exists($dir))
            unlink($dir);
    }

    /**
     * @author Phong Pham Hong
     * 
     * generate random domain 
     * @param string
     * @return string
     */
    public static function randomDomain($string, $addSuffix = false) {
        $string = strtolower(preg_replace("/[^a-zA-Z0-9_-]+/", "", $string));
        return $string . ($addSuffix ? rand(0, 100) : '');
    }

    /**
     * @authro Phong Pham Hong
     * 
     * append GET params to url
     * @param string url
     * @param array param
     * @return string
     */
    public static function appendParamsToUrl($url, $param = array()) {
        $ap = '?';
        if (strpos($url, '?') !== false) {
            $ap = '&';
        }
        return $url . $ap . http_build_query($param);
    }

}
