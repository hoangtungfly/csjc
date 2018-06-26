<?php

namespace common\lib\upload;

use common\utilities\UtilityHtmlFormat;

define('CHECK_IMAGICK', class_exists('Imagick'));

function getFileNameNew($UploadDirectory, $FileName) {
    $extension = pathinfo($FileName, PATHINFO_EXTENSION);
    $name = stripUnicode(pathinfo($FileName, PATHINFO_FILENAME));
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

function stripUnicode($str, $doi = '-') {
    $str = trim($str);
    $arrayPregReplace = [
        'à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|A|ầ|à' => 'a',
        'è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|E' => 'e',
        'ì|í|ị|ỉ|ĩ|Ì|Í|Ị|Ỉ|Ĩ|I' => 'i',
        'ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|O' => 'o',
        'ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|U' => 'u',
        'ỳ|ý|ỵ|ỷ|ỹ|Ỳ|Ý|Ỵ|Ỷ|Ỹ|Y' => 'y',
        'đ|Đ|D' => 'd',
        '[^a-zA-Z0-9 ]+' => ' ',
        '(\s)+' => ' ',
    ];
    foreach ($arrayPregReplace as $key => $value) {
        $str = preg_replace("/{$key}/", $value, $str);
    }
    $str = trim(strtolower(str_replace(' ', $doi, trim($str))));
    return $str;
}

class UpFile {

    public static function up($options) {
        $array_match_options = array(
            'upload_type' => 'setUploadType',
            'create_thumb' => 'setCreateThumb',
            'extension' => 'SetValidExtensions',
            'max_size' => 'SetMaximumFileSize',
            'max_width' => 'SetMaximumWidth',
            'max_height' => 'SetMaximumHeight',
            'path' => 'setDirUpload'
        );
        $FILE = ['tmp_name' => $options['tmp_name']];

        $post = [
            'options' => json_decode($options['options'], true)
        ];

        $FILE['tmp_name']['name'] = $options['name'];
        $upload = new Uploadhandel($FILE['tmp_name']);
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
        if (is_array($post['options']['resize']) && $upload->getResizeModel() && strtolower(pathinfo($FILE['tmp_name']['name'], PATHINFO_EXTENSION)) != 'gif') {
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
                return self::setResponse(200, array(
                            'name' => $upload->GetFileName(),
                            'original_name' => $upload->getOriginalName(),
                            'ext' => $upload->getFileExtension(),
                            'imagesize' => $upload->GetImageSize(),
                            'size' => $upload->getFileSize(),
                            'baseUrl' => str_replace(APPLICATION_PATH, '', $upload->GetUploadDirectory()),
                            'resize' => $resizeResult
                ));
            } else {
                return self::setResponse(200, array(
                            'name' => $upload->GetFileName(),
                            'size' => $upload->getFileSize(),
                            'original_name' => $upload->getOriginalName(),
                            'ext' => $upload->getFileExtension(),
                            'baseUrl' => str_replace(APPLICATION_PATH, '', $upload->GetUploadDirectory()),
                ));
            }
        } else {
            return self::setResponse(403, array(
                        'name' => $upload->GetFileName(),
                        'error' => $upload->getErrors()
            ));
        }
    }

    public static function compressed($post) {
        $tableName = str_replace('/', '', $post['table_name']);
        if (is_array($post['tmp'])) {
            if ($post['tmp'][0] == 0) {
                $post['tmp'] = 'h' . $post['tmp'][1];
            } else if ($post['tmp'][1] == 0) {
                $post['tmp'] = 'w' . $post['tmp'][0];
            } else {
                $post['tmp'] = $post['tmp'][0] . 'x' . $post['tmp'][1];
            }
        }
        $tmp = str_replace('/', '', $post['tmp']);
        $filename = str_replace('/', '', $post['filename']);
        $linkResize = APPLICATION_PATH . '/images/' . $tableName . '/' . $tmp . '/' . $filename;
        $linkMain = APPLICATION_PATH . '/images/' . $tableName . '/main/' . $filename;
        if (is_file($linkMain)) {
            if (CHECK_IMAGICK) {
                $upload = new ResizeImagick();
            } else {
                $upload = new Resize();
            }
            $upload->setImageFile($linkMain);
            if (strpos($tmp, 'w') === 0) {
                $width = (int) str_replace('w', '', $tmp);
                $height = 0;
            } else if (strpos($tmp, 'h') === 0) {
                $height = (int) str_replace('h', '', $tmp);
                $width = 0;
            } else {
                list($width, $height) = explode('x', $tmp);
            }
            if(!$upload->oriWidth || !$upload->oriHeight) {
                return false;
            }
            if ($width == 0 && $height) {
                $width = (int) ($height * $upload->oriWidth / $upload->oriHeight);
            }
            $upload->width = $width;

            if ($height == 0 && $upload->width) {
                $height = (int) ($upload->width * $upload->oriHeight / $upload->oriWidth);
            }
            $upload->height = $height;

            if (!$height && !$width) {
                $upload->width = $upload->oriWidth;
                $upload->height = $upload->oriHeight;
            }

            $upload->startResize();
            $upload->save($linkResize);
        }
    }

    public static function link($post) {
        $tableName = str_replace('/', '', $post['table_name']);
        if (isset($post['link']) && $post['link'] != "") {
            $replace_name = isset($post['replace_name']) ? $post['replace_name'] : '';
            $link = trim($post['link']);
            $link = 'https://img.gs/zsztpvdrbc/full/'. $link;
//            $link = str_replace(' ', '%20', $link);
            $content = filegetcontents($link);
//            echo $link;die();
            if ($content) {
                $original_name = pathinfo($link, PATHINFO_BASENAME);
                if ($replace_name) {
                    $a = explode('.', $original_name);
                    $a[0] = $replace_name;
                    $original_name = implode('.', $a);
                }
                $UploadDirectory = APPLICATION_PATH . '/images/' . $tableName . '/main/';
                $filename = getFileNameNew($UploadDirectory, $original_name);

                $link = 'images/' . $tableName . '/main/' . $filename;
                $linkMain = APPLICATION_PATH . '/images/' . $tableName . '/main/' . $filename;
                rmkdir(APPLICATION_PATH . '/images/' . $tableName . '/main/');
                if (file_put_contents($linkMain, $content)) {
                    $resizeResult = array();
                    if (isset($post['resize']) && $post['resize'] != "") {
                        $resize = (array) json_decode($post['resize']);
                        foreach ($resize as $key => $a) {
                            self::compressed([
                                'table_name' => $tableName,
                                'tmp' => $a,
                                'filename' => $filename,
                            ]);
                        }
                    }
                    return self::setResponse(200, [
                                'link' => '/' . $link,
                                'name' => $filename,
                                'original_name' => $original_name,
                                'ext' => pathinfo($filename, PATHINFO_EXTENSION),
                                'baseUrl' => '/images/' . $tableName . '/main/',
                                'resize' => $resizeResult,
                    ]);
                }
            }
        }
    }

    public static function setResponse($code, $data) {
        $result = json_encode(array('status' => $code, 'response' => $data));
        return $result;
    }

}
