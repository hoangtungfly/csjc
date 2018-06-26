<?php

namespace common\utilities;

use Yii;

class UtilityDirectory {

    public static function dmkdir($dir) {
        $flag = true;
        if (!is_dir($dir)) {
            $oldmask = umask(0);
            $flag = @mkdir($dir, 0775);
            @umask($oldmask);
        }
        return $flag;
    }

    public static function createDir($dir) {
        $arrayDirectory = explode("/", $dir);
        $count = count($arrayDirectory) - 1;
        $array = array();
        for ($i = $count; $i >= 0; $i--) {
            if (!preg_match("/\./", $arrayDirectory[$i])) {
                $value = implode("/", $arrayDirectory) . '/';
                if (is_dir($value))
                    break;
                if (self::dmkdir($value)) {
                    if (count($array) > 0) {
                        foreach ($array as $k => $v) {
                            self::dmkdir($v);
                        }
                    }
                    break;
                }
                $array[] = $value;
            }
            unset($arrayDirectory[$i]);
        }
    }

    public static function scandir($dir) {
        if (is_dir($dir)) {
            $array = scandir($dir);
            $result = array();
            foreach ($array as $key => $value) {
                if ($key > 1) {
                    $result[] = $value;
                }
            }
            if (count($result) > 0)
                return $result;
        }
        return false;
    }
    
    public static function scanFile($dir) {
        if (is_dir($dir)) {
            $array = scandir($dir);
            $result = array();
            foreach ($array as $key => $value) {
                if ($key > 1 && preg_match('/\./',$value)) {
                    $result[] = $value;
                }
            }
            if (count($result) > 0)
                return $result;
        }
        return false;
    }

    public static function caythumuc($link, &$thumuc, $dem = 0) {
        $bac1 = self::scandir($link);
        $dem++;
        if ($bac1 && count($bac1) > 0) {
            $thumuc .= '<ul class="nutthumuc ' . ($dem == 1 ? 'active' : '') . '">';
            foreach ($bac1 as $tem) {
                $linkcon = $link . '/' . $tem;
                if (preg_match("/\./", $tem)) {
                    if (!preg_match("/\.(png|jpg|gif|PNG|JPG|GIF|ico|ICO)$/", $tem)) {
                        $thumuc .= '<li><span class="file" data-href="' . $linkcon . '">' . $tem . '</span></li>';
                    }
                } else {
                    $thumuc .= '<li><span class="thumuc" data-href="' . $linkcon . '">' . $tem . '</span>';
                    self::caythumuc($link . '/' . $tem, $thumuc, $dem);
                    $thumuc .= '</li>';
                }
            }
            $thumuc .= '</ul>';
        }
    }

    public static function deleteDiretory($arrayDir) {
        if (!is_array($arrayDir)) {
            $arrayDir = [$arrayDir];
        }
        if (count($arrayDir)) {
            foreach ($arrayDir as $key => $dir) {
                $adir = self::scandir($dir);
                if (is_array($adir) && count($adir)) {
                    foreach ($adir as $item) {
                        if (strpos($item, '.') === FALSE) {
                            self::deleteDiretory($dir . '/' . $item);
//                                rmdir($dir.'/'.$item);
                        } else {
                            self::deleteFile($dir . '/' . $item);
                        }
                    }
                }
            }
        }
    }

    public static function deleteFile($arrayFile) {
        if (!is_array($arrayFile)) {
            $arrayFile = [$arrayFile];
        }
        if (count($arrayFile)) {
            foreach ($arrayFile as $key => $file) {
                if (is_file($file)) {
                    $old = umask(0);
                    @chmod(dirname($file), 0775);
                    @chmod($file, 0775);
                    @umask($old);
                    @unlink($file);
                }
            }
        }
    }

    public static function getFileAssetsFirst() {
        $listDir = UtilityDirectory::scandir(Yii::getAlias('@application'));
        $application_dir = str_replace('\\', '/', Yii::getAlias('@application'));
        foreach ($listDir as $dir) {
            $linkAssetsCopy = $application_dir . '/' . $dir . '/assets/AppAsset.php';
            if (is_file($linkAssetsCopy)) {
                return [$dir,$linkAssetsCopy];
            }
        }
        return [false,false];
    }

    public static function getFileComponentsFirst() {
        $listDir = UtilityDirectory::scandir(Yii::getAlias('@application'));
        $application_dir = str_replace('\\', '/', Yii::getAlias('@application'));
        foreach ($listDir as $dir) {
            $linkDir = $application_dir . '/' . $dir . '/components';
            if (is_dir($linkDir) && ($arrayFile = UtilityDirectory::scandir($linkDir))) {
                    return [$dir,$linkDir . '/' . $arrayFile[0]];
            }
        }
        return [false,false];
    }

    public static function getFileModuleFirst() {
        $listDir = UtilityDirectory::scandir(Yii::getAlias('@application'));
        $application_dir = str_replace('\\', '/', Yii::getAlias('@application'));
        foreach ($listDir as $dir) {
            $linkDir = $application_dir . '/' . $dir .'/' . updateUpperFirstCharacter($dir).'Module.php';
            if (is_file($linkDir)) {
                return [$dir,$linkDir];
            }
        }
        return [false,false];
    }
    
    public static function copyAndReplaceDirectory($dir_old,$dir_new,$dir_old_begin = false,$dir_new_begin = false) {
        $link_old = APPLICATION_PATH .'/application/'.$dir_old;
        
        $link_new = APPLICATION_PATH .'/application/'.$dir_new;
        
        
        
        if(!$dir_new_begin) {
            $dir_new_begin = $dir_new;
        }
        if(!$dir_old_begin) {
            $dir_old_begin = $dir_old;
        }
        
        if($link_old == APPLICATION_PATH . '/application/' . $dir_old_begin . '/public') {
            if(!is_dir($link_new)) {
                self::createDir($link_new);
            }
            return false;
        }
        
        if(is_dir($link_old)) {
            if(!is_dir($link_new)) {
                self::createDir($link_new);
            }
            $list_dir_old = self::scandir($link_old);
            if($list_dir_old) {
                foreach($list_dir_old as $key => $value) {
                    if(strpos($value, '.') === false) {
                        self::copyAndReplaceDirectory($dir_old.'/'.$value, $dir_new.'/'.$value,$dir_old_begin,$dir_new_begin);
                    } else {
                        $search = [$dir_old_begin,updateUpperFirstCharacter($dir_old_begin)];
                        $replace = [$dir_new_begin,updateUpperFirstCharacter($dir_new_begin)];
                        $filename = $link_new .'/'. str_replace($search,$replace,$value);
                        if(!is_file($filename)) {
                            $content = UtilityFile::filegetcontent($link_old.'/'.$value);
                            $content = str_replace($search,$replace,$content);

                            UtilityFile::fileputcontents($filename, $content);
                        }
                    }
                }
            }
        }
    }

}
