<?php

/**
 * Some function for process url
 *
 * @author DungNguyenAnh
 * @date 11/22/2015
 */

namespace common\utilities;

use yii\helpers\ArrayHelper;

class UtilityFile {
    /* Kiểm tra file tồn tại hay không? Nếu tồn tại thì đổi link file + 1 vào đuôi */

    public static function isFile($name, $duoi, $directFile) {
        $name1 = $name;
        $link = $directFile . $name1 . '.' . $duoi;
        $dem = 1;
        while (is_file(APPLICATION_PATH . $link)) {
            $name1 = $name . '(' . $dem . ')';
            $link = $directFile . $name1 . '.' . $duoi;
            $dem++;
        }
        $name = $name1;
        return array('name' => $name, 'link' => $link, 'duoi' => $duoi);
    }

    /* Save file */

    public static function saveFile($linkSave, $linkCron) {
        if (!is_file($linkSave)) {
            $content = self::filegetcontent($linkCron);
            if ($content) {
                self::fileputcontents($linkSave, $content);
                return true;
            }
        }
        return false;
    }

    public static function fileputcontents($filename, $data, $owner = 'apache') {
        $filename = str_replace('\\','/',$filename);
        if (self::rmkdir(dirname($filename))) {
            if (is_file($filename)) {
                $old = umask(0);
                $dir = dirname($filename);
                @chmod($dir, 0775);
                @chown($dir, $owner);
                @chmod($filename, 0775);
                @chown($filename, $owner);
                umask($old);
            }
            file_put_contents($filename, $data);
            return true;
        }
        return false;
    }

    public static function filegetcontent($filename) {
        if (self::rmkdir(dirname($filename))) {
            return file_get_contents($filename);
        }
        return false;
    }

    public static function rmkdir($path, $mode = 0775) {
        return is_dir($path) || ( rmkdir(dirname($path), $mode) && _mkdir($path, $mode) );
    }

    public static function _mkdir($path, $mode = 0775, $owner = 'apache') {
        $old = umask(0);
        $res = @mkdir($path, $mode);
        @chown($path, $owner);
        umask($old);
        return $res;
    }

    public static function dmkdir($dir, $owner = 'apache') {
        $flag = true;
        if (!is_dir($dir)) {
            $oldmask = umask(0);
            $flag = @mkdir($dir, 0775);
            chown($dir, $owner);
            umask($oldmask);
        }
        return $flag;
    }

    public static function scandir($dir) {
        if (is_dir($dir)) {
            $array = scandir($dir);
            unset($array[0]);
            unset($array[1]);
            if (count($array) > 0)
                return $array;
        }
        return false;
    }

    public static function rootTree($link, &$directory, $dem = 0) {
        $bac1 = self::scandir($link);
        $dem++;
        if ($bac1 && count($bac1) > 0) {
            $directory .= '<ul class="rootdirectory ' . ($dem == 1 ? 'active' : '') . '">';
            foreach ($bac1 as $tem) {
                $$linkchild = $link . '/' . $tem;
                if (preg_match("/\./", $tem)) {
                    if (!preg_match("/\.(png|jpg|gif|PNG|JPG|GIF|ico|ICO)$/", $tem)) {
                        $directory .= '<li><span class="file" data-href="' . $$linkchild . '">' . $tem . '</span></li>';
                    }
                } else {
                    $directory .= '<li><span class="directory" data-href="' . $$linkchild . '">' . $tem . '</span>';
                    self::rootTree($link . '/' . $tem, $directory, $dem);
                    $directory .= '</li>';
                }
            }
            $directory .= '</ul>';
        }
    }

    public static function deleteFile($files) {
        if (!is_array($files)) {
            $files = [$files];
        }
        $flag = false;
        if (count($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                    $flag = true;
                }
            }
        }
        return $flag;
    }
    
    public static $arrayFileInWeb;

    public static function getFileInWeb($link, $owner = 'apache') {
        if(!isset(self::$arrayFileInWeb[$link])) {
            self::$arrayFileInWeb[$link] = false;
            if ($link && is_file($link)) {
                $old = umask(0);
                $dir = dirname($link);
                @chmod($dir, 0775);
                @chown($dir, $owner);
                @chmod($link, 0775);
                @chown($link, $owner);
                umask($old);
                self::$arrayFileInWeb[$link] =  file_get_contents($link);
            }
        }
        return self::$arrayFileInWeb[$link];
    }
    
    public static function replaceContentFileByDir($dirNew,$dirOld, $link) {
        return str_replace([$dirOld,updateUpperFirstCharacter($dirOld)], 
                [$dirNew,updateUpperFirstCharacter($dirNew)], 
                UtilityFile::getFileInWeb($link)
            );
    }

}
