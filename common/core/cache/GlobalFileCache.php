<?php

namespace common\core\cache;

use yii\caching\FileCache;
use yii\helpers\FileHelper;

class GlobalFileCache extends FileCache {

    public $cachePath = '@cache/data';
    public $cacheFileSuffix = '.bin';
    public $directoryLevel = 1;

    public function buildKey($key) {
        return $this->keyPrefix . $key;
    }
    
    protected function getCacheFile($key) {
        return $this->cachePath . DIRECTORY_SEPARATOR . $key . $this->cacheFileSuffix;
    }

    protected function setValue($key, $value, $duration) {
        $directory = preg_replace('~/(.*)~','',$key);
        if(isset(app()->params['cache_file_disabled'][$directory])) {
            return false;
        }
        $cacheFile = $this->getCacheFile($key);
        if ($this->directoryLevel > 0) {
            @FileHelper::createDirectory(dirname($cacheFile), $this->dirMode, true);
        }
        if (@file_put_contents($cacheFile, $value, LOCK_EX) !== false) {
            if ($this->fileMode !== null) {
                @chmod($cacheFile, $this->fileMode);
            }
            if ($duration <= 0) {
                $duration = 31536000; // 1 year
            }

            return @touch($cacheFile, $duration + time());
        } else {
            return false;
        }
    }

    protected function deleteValue($key) {
        $cacheFile = $this->getCacheFile($key);
        $files = glob($cacheFile);
        $flag = false;
        if ($files) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    $flag = @unlink($file);
                }
            }
        }
        return $flag;
    }

}
