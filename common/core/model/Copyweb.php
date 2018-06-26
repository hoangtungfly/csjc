<?php

namespace common\core\model;

use common\utilities\UtilityHtmlFormat;
use Yii;

class Copyweb {
    /* Tên đường dẫn ghi file */

    public $nameDirtory;

    /* Link web chuẩn cần copy */
    public $linkweb;

    /* Link thư mục file html cần ghi */
    public $linkFileHtml;

    /* Link thư mục file css cần ghi */
    public $linkFileCss;

    /* Link thư mục file ảnh cần ghi */
    public $linkFileImages;

    /* Link thư mục file js cần ghi */
    public $linkFileJs;

    /* Link thư mục file js cần ghi */
    public $linkFileImg;

    /* Link thư mục file font js cần ghi */
    public $linkFileFont;

    /* Link thư mục để web */
    public $dirHtml;

    /* Link web copy */
    public $linkCopy;

    /* Tên file html ghi vào */
    public $nameFile;

    /* Nội dung file HTML */
    public $contentHTML;

    /* Link file public */
    public $linkFilePublic;

    /* Mảng link css */
    public $arrayCss;
    public $arrayCss1 = array();

    /* Khởi tạo 4 propertiy tên thư mục, link copy, linkweb chuẩn, tên file cần ghi */

    public function __construct($nameDirtory, $linkCopy, $linkweb, $nameFile) {
        $this->nameDirtory = $nameDirtory;
        $this->linkweb = $linkweb;
        $this->linkCopy = $linkCopy;
        $this->nameFile = $nameFile;
        /* Link thư mục chữa trong application */
//        $this->dirHtml = str_replace("\\", "/", __DIR__.'/html/');
        $this->dirHtml = Yii::getAlias('@application') . '/';
        $this->linkFileHtml = $this->dirHtml . $this->nameDirtory . '/';
        $this->linkFilePublic = $this->linkFileHtml . 'public/';
        
        $this->linkFileCss = $this->linkFilePublic . 'css/';
        $this->linkFileImages = $this->linkFilePublic . 'images/';
        $this->linkFileJs = $this->linkFilePublic . 'js/';
        $this->linkFileImg = $this->linkFilePublic . 'img/';
        $this->linkFileFont = $this->linkFilePublic . 'font/';
        rmkdir($this->dirHtml);
        rmkdir($this->linkFileHtml);
        rmkdir($this->linkFilePublic);
        rmkdir($this->linkFileCss);
        rmkdir($this->linkFileImages);
        rmkdir($this->linkFileJs);
        rmkdir($this->linkFileImg);
        rmkdir($this->linkFileFont);
    }

    /* Thêm đường dẫn http vào link */

    public function getLinkHTTP($value) {
        $linkweb = $this->linkweb;
        /* $kiểm tra xem link có http hay chưa? chưa có thì thêm vào */
        if (str_replace('http', '', $value) == $value) {
            if (preg_match("~^//~", $value)) {
                $linkweb = 'http:';
                if(preg_match('/https/', $this->linkweb)) {
                    $linkweb = 'https:';
                }
            } else if (preg_match("~^/~", $value)) {
                $linkweb = preg_replace("/\/$/", "", $this->linkweb);
            }
            $value = $linkweb . $value;
        }
        return $value;
    }

    /* Lấy tên file */

    public function getFileName($value) {
        $array = explode('/', $value);
        $name = $array[count($array) - 1];
        /* Xóa ?#& sau tên cuối */
        $name = explode("?", $name);
        $name = $name[0];

        $name = explode("#", $name);
        $name = $name[0];

        $name = explode("&", $name);
        $name = $name[0];
        return $name;
    }

    /* mảng từng thư mục của đường dẫn file */

    public function arrayLinkCss($linkCss) {
        /* Lấy đường dẫn web chuẩn */
        $linkweb = $this->linkweb;

        /* Kiểm tra xem có dấu / ở cuối không nếu chưa có thì thêm vào */
        if (!preg_match("/\/$/", $linkweb))
            $linkweb .= '/';

        /* Bỏ đường dẫn chuẩn của link file đi */
        $linkCss = $this->trimParam($linkCss);
        $linkCss = str_replace($linkweb, "", $linkCss);

        /* Tách ra đường dẫn thành từng phần tử */
        $arrayCss = explode('/', $linkCss);
        $array = array();
        /* Phần tử đầu tiên là đường dẫn chuẩn của web */
        if (strpos($linkCss, 'http') === false) {
            if($linkCss{1}.$linkCss{2} == '//') {
                $array[] = 'http:';
            } else {
                $array[] = $linkweb;
            }
        }
        foreach ($arrayCss as $key => $value) {
            $array[] = $value;
        }
        /* Xóa phần từ cuối cùng tên file của mảng */
        unset($array[count($array) - 1]);
        return $array;
    }

    /* save file ảnh hoặc font trong css */
    
    public function getLinkImgInContentCss($linkcss,$linkImg) {
        if (preg_match("/http:|https:/", $linkImg)) {
            /* Link ảnh có http */
            return $linkImg;
        } else {
            /* Lấy mảng link file css */
            $arrayLinkCss = $this->arrayLinkCss($linkCss);
            $count = count($arrayLinkCss);
            /* Đếm xem lùi lại thư mục mấy lần */
            $countSub = substr_count($linkImg, "../");

            for ($i = 1; $i <= $countSub; $i++) {
                unset($arrayLinkCss[$count - $i]);
            }

            /* lấy link file ảnh */
            if ($linkImg{0} == '/' && $linkImg{0} . $linkImg{1} != '//') {
                $link = $this->getLinkHTTP($linkImg);
            } else {
                $link = implode('/', $arrayLinkCss) . '/' . str_replace("../", "", $linkImg);
            }
            return $link;
        }
        
    }

    public function saveImageCss($value, $linkCss, $linkFileImg) {
        $nameFile = $this->getFileName($value);
        /* Kiểm tra xem link ảnh có http hay chưa */

        if (preg_match("/http:|https:/", $value)) {
            /* Link ảnh có http */
            if (!is_file($linkFileImg . $nameFile)) {

                /* save file ảnh có http */
                $c = @filegetcontents($value);
                if ($c)
                    file_put_contents($linkFileImg . $nameFile, $c);
            }
        }
        else {
            /* Link ảnh chưa có http */

            /* Lấy mảng link file css */
            $arrayLinkCss = $this->arrayLinkCss($linkCss);
            $count = count($arrayLinkCss);

            /* Đếm xem lùi lại thư mục mấy lần */
            $countSub = substr_count($value, "../");

            for ($i = 1; $i <= $countSub; $i++) {
                unset($arrayLinkCss[$count - $i]);
            }

            /* lấy link file ảnh */
            if ($value{0} == '/' && $value{0} . $value{1} != '//') {
                $link = $this->getLinkHTTP($value);
            } else {
                $link = implode('/', $arrayLinkCss) . '/' . str_replace("../", "", $value);
            }

            /* save file ảnh chưa có http */
            $linkFile = $linkFileImg . $nameFile;
            if (!is_file($linkFile)) {
                $c = @filegetcontents($link);
                if ($c)
                    @file_put_contents($linkFileImg . $nameFile, $c);
            }
        }
    }

    /* Xử lí nội dung trong file css */

    public function proccessContentCss($linkCss) {
        /* lấy nội dung file css */
        $content = @filegetcontents($linkCss);
        if (!$content)
            return false;
        $arrayImage = array();
        /* kiểm tra có link trong file css hay không?
         * Nếu có thì sẽ save file ảnh trong css vào thư mục img
         */
        if (preg_match_all("/url\([^)]*\)/", $content, $arrayImage)) {

            /* Mảng file có trong file css */
            $arrayImage = $arrayImage[0];

            /* Mảng trước khi thay đổi */
            $arrayImageFrom = array();

            /* Mảng sau khi thay đổi */
            $arrayImageTo = array();

            /* vòng lặp để save từng ảnh vào trong file img
             * Thay đổi link file ảnh cho đúng với file css mới
             */
            foreach ($arrayImage as $key => $value) {
                /* Kiếm tra xem link ảnh có http hay không và kiểm tra xem link file ảnh này có ở trong web không */
                if (preg_match("/http:|https:/", $value) && str_replace($this->linkweb, "", $value) == $value) {
                    
                } else {
                    /* Khi link ảnh ở trong web */

                    /* Xóa định dạng url(""),url(''),url() */
                    $value = preg_replace("/url\(|\)$/", "", $value);
                    $value = preg_replace("/^\"|\"$|^'|'$/", "", $value);

                    /* lấy ảnh cho vào mảng ảnh trước */
                    $arrayImageFrom[] = $value;

                    /* lấy ảnh cho vào mảng ảnh sau */
                    $nameFile = $this->getFileName($value);

                    /* Kiểm tra xem file là ảnh
                     * Nếu file không là ảnh thì là file font chữ
                     */
                    if (preg_match("/(.png|.gif|.jpg)/", $nameFile)) {
                        $arrayImageTo[] = '../img/' . $nameFile;
                        $this->saveImageCss($value, $linkCss, $this->linkFileImg);
                    } else {
                        $arrayImageTo[] = '../font/' . $nameFile;
                        $this->saveImageCss($value, $linkCss, $this->linkFileFont);
                    }
                }
            }

            /* Thay đổi đường dẫn ảnh hoặc font trong nội dung file css */
            $content = $this->replaceFromTo($arrayImageFrom, $arrayImageTo, $content);
        }
        if(preg_match_all("/@import[^']*?('([^']*)')/",$content,$match_all) && isset($match_all[2]) && count($match_all[2])) {
            foreach($match_all[2] as $link) {
                $linkAll = $this->getLinkImgInContentCss($linkcss, $link);
                $nameValue = $this->getFileNameCss($link);
                $content_new = $this->proccessContentCss($linkAll);
                if($content_new) {
                    file_put_contents($this->linkFileCss . $nameValue, $content_new);
                    $content = $this->replaceFromTo($link, $this->linkFileCss . $nameValue, $content);
                }
                
            }
        }
        return $content;
    }

    /* Xử lí nội dung trong file html css */

    public function proccessContentHtmlCss() {
        /* lấy nội dung file css */
        $content = $this->contentHTML;

        if (!$content)
            return false;
        $arrayImage = array();
        /* kiểm tra có link trong file css hay không?
         * Nếu có thì sẽ save file ảnh trong css vào thư mục img
         */
        if (preg_match_all("/url\([^)]*\)/", $content, $arrayImage)) {
            /* Mảng file có trong file css */
            $arrayImage = $arrayImage[0];

            /* Mảng trước khi thay đổi */
            $arrayImageFrom = array();

            /* Mảng sau khi thay đổi */
            $arrayImageTo = array();

            /* vòng lặp để save từng ảnh vào trong file img
             * Thay đổi link file ảnh cho đúng với file css mới
             */
            foreach ($arrayImage as $key => $value) {
                /* Kiếm tra xem link ảnh có http hay không và kiểm tra xem link file ảnh này có ở trong web không */
                if (preg_match("/http:|https:/", $value) && str_replace($this->linkweb, "", $value) == $value) {
                    
                } else {
                    /* Khi link ảnh ở trong web */
                    /* Xóa định dạng url(""),url(''),url() */
                    $value = preg_replace("/url\(|\)$/", "", $value);
                    $value = preg_replace("/^\"|\"$|^'|'$/", "", $value);

                    /* lấy ảnh cho vào mảng ảnh trước */
                    $arrayImageFrom[] = $value;

                    /* lấy ảnh cho vào mảng ảnh sau */
                    $nameFile = $this->getFileName($value);
                    /* Kiểm tra xem file là ảnh
                     * Nếu file không là ảnh thì là file font chữ
                     */

                    $linkCron = $this->linkweb . $value;
                    if (preg_match("/(.png|.gif|.jpg)/", $nameFile)) {
                        $arrayImageTo[] = 'images/' . $nameFile;
                        $linkSave = $this->linkFileImages . $nameFile;
                        $this->saveFile($linkSave, $linkCron);
                    } else {
                        $arrayImageTo[] = 'font/' . $nameFile;
                        $linkSave = $this->linkFileFont . $nameFile;
                        $this->saveFile($linkSave, $linkCron);
                    }
                }
            }

            /* Thay đổi đường dẫn ảnh hoặc font trong nội dung file css */
            $content = $this->replaceFromTo($arrayImageFrom, $arrayImageTo, $content);
        }
        $this->contentHTML = $content;
    }

    /* Lấy đường dẫn thật của link file xóa định dạng src="", src='', href="", href='' của file js */

    public function getLinkTrue($value) {
        return preg_replace('/(^href=")|(^href=\')|(^src=")|(^src=\')|"$|\'$/', '', $value);
    }

    /* Xóa biến của link từ sau dấu ? */

    public function trimParam($value) {
        return preg_replace('/\?(.*)/', '', $value);
    }

    /* Save file */

    public function saveFile($linkSave, $linkCron) {
        if (!is_file($linkSave) && strpos($linkSave, '.')) {
            $content = @filegetcontents($linkCron);
            if ($content)
                file_put_contents($linkSave, $content);
        }
    }

    public function saveJs() {
        $arrayJs = array();
        /* Tìm kiếm những đường link js có trong file html */
        if (preg_match_all('/<script(.*)(src="[^"]*"|src=\'[^\']*\')[^>]*>/', $this->contentHTML, $arrayJs)) {
            /* Mảng tất cả link file js */
            $arrayJs = $arrayJs[2];
            /* Mảng link file js của content */
            $arrayJsFrom = array();

            /* Mảng link file js của content khi save */
            $arrayJsTo = array();

            /* Xử lí từng file js */
            foreach ($arrayJs as $key => $linkJs) {
                /* Lấy đường dẫn thật của link file css xóa định dạng src="" ỏ src='' của file js */
                $linkJs = $this->getLinkTrue($linkJs);
                var_dump($linkJs);
                /* Kiểm tra xem nếu không phải là file js thì xóa key này trong mảng js */
                if (!preg_match('/.js/', $linkJs)) {
                    unset($arrayJs[$key]);
                } else {
                    /* Trường hợp link thật sự là file js */
                    if (preg_match("/(google)|(addthis)/", $linkJs))
                        continue;
                    /* tập hợp mảng cần link js trước khi cần thay đổi */
                    $arrayJsFrom[] = $linkJs;

                    /* Thêm đường dẫn http vào link js */
                    $linkJs = $this->getLinkHTTP($linkJs);

                    /* Lấy tên file js */
                    $nameValue = $this->getFileName($linkJs);
                    $nameValue = str_replace('-js', '.js', $nameValue);
                    /* tập hợp mảng link js sau khi thay đổi */
                    $arrayJsTo[] = 'js/' . $nameValue;

                    /* Kiểm tra xem file js này đã có trong máy chưa
                     * chưa có thì ghi vào file
                     */
                    if (!is_file($this->linkFileJs . $nameValue))
                        $this->saveFile($this->linkFileJs . $nameValue, $linkJs);
                }
            }
            /* Thay đổi đường dẫn file js trong nội dung file cần ghi */
            $this->contentHTML = $this->replaceFromTo($arrayJsFrom, $arrayJsTo, $this->contentHTML);
        }
    }
    
    public function getFileNameCss($linkCss) {
        /* Lấy tên file css */
        $nameValue = $this->getFileName($linkCss);
        $nameValue = str_replace('-css', '.css', $nameValue);
        $name2 = UtilityHtmlFormat::stripUnicode(str_replace([$this->linkweb,'/','.css'],'',preg_replace('/\?(.*)/','',$linkCss)), '').'.css';
        if (in_array($nameValue, $this->arrayCss1)) {
            $nameValue = $name2;
        }
        $this->arrayCss1[] = $nameValue;
        return $nameValue;
    }

    public function saveCss() {
        $arrayCss = array();
        /* Tìm kiếm những đường link css có trong file html */
        if (preg_match_all('/<link .*?(href=)((\'([^\']+)\')|("([^"]+)"))[^>]*>/', $this->contentHTML, $arrayCss)) {
//            var_dump($arrayCss);die();
            /* Mảng tất cả link file css */
            $arrayCss = $arrayCss[2];

            /* Mảng link file css của content */
            $arrayCssFrom = array();

            /* Mảng link file css của content khi save */
            $arrayCssTo = array();
            $this->arrayCss = array();
            /* Xử lí từng file css */
            foreach ($arrayCss as $key => $linkCss) {
                $linkCss = str_replace(["'",'"'], '', $linkCss);
                if ($linkCss && strpos($linkCss, '.')) {
                    /* Lấy đường dẫn thật của link file css xóa định dạng href="" ỏ href='' của file css */
                    $linkCss = $this->getLinkTrue($linkCss);
                    var_dump($linkCss);
                    /* Kiểm tra xem nếu không phải là file css thì xóa key này trong mảng css */
                    if (!preg_match('/\.css/', $linkCss)) {
                        if (preg_match('/\.(png|jpg|gif|ico)$/', $linkCss)) {
                            $linkImage = $linkCss;
                            /* tập hợp mảng cần link ảnh trước khi cần thay đổi */
                            $arrayCssFrom[] = $linkImage;

                            /* Kiểm tra xem có domain cho link chưa? Chưa có thì thêm vào */
                            $linkImage = $this->getLinkHTTP($linkImage);

                            /* Lấy tên file ảnh */
                            $nameImage = $this->getFileName($linkImage);
                            /* Thêm vào mảng ảnh thay đổi */
                            $arrayCssTo[] = 'img/' . $nameImage;

                            /* Kiểm tra xem ảnh có có chưa? chưa có thì ghi ảnh */
                            if (!is_file($this->linkFileImg . $nameImage))
                                $this->saveFile($this->linkFileImg . $nameImage, $linkImage);
                        }
                        else {
                            unset($arrayCss[$key]);
                        }
                    } else {
                        /* Trường hợp link thật sự là file css */
                        /* tập hợp mảng cần link css trước khi cần thay đổi */
                        if (preg_match("/google/", $linkCss))
                            continue;
                        $arrayCssFrom[] = $linkCss;

                        /* Thêm đường dẫn http vào link css */
                        $linkCss = $this->getLinkHTTP($linkCss);
                        $nameValue = $this->getFileNameCss($linkCss);
                        /* tập hợp mảng link css sau khi thay đổi */
                        $arrayCssTo[] = 'css/' . $nameValue;

                        /* Kiểm tra xem file css này đã có trong máy chưa
                         * chưa có thì ghi vào file
                         */
                        if (!is_file($this->linkFileCss . $nameValue)) {
                            /* Xử lí nội dung trong file css   
                             * Lấy đường dẫn ảnh và font ghi vào thư mục trong máy
                             */
                            $content = $this->proccessContentCss($linkCss);
                            /* Ghi file css này */
                            if ($content)
                                file_put_contents($this->linkFileCss . $nameValue, $content);
                        }
                    }
                }
            }
            $this->arrayCss = $arrayCssTo;
            /* Thay đổi đường dẫn file css trong nội dung file cần ghi */
            
            $this->contentHTML = $this->replaceFromTo($arrayCssFrom, $arrayCssTo, $this->contentHTML);
        }
    }
    
    public function replaceFromTo($arrayCssFrom, $arrayCssTo, $content) {
        $from = [];
        $to = [];
        if($arrayCssFrom) {
            $arrayCssFrom1 = $arrayCssFrom;
            foreach($arrayCssFrom as $key => $value) {
                $length = 0;
                $k = $key;
                foreach($arrayCssFrom1 as $key2 => $value2) {
                    $length2 = strlen($value2);
                    if($length2 >= $length) {
                        $k = $key2;
                    }
                }
                unset($arrayCssFrom1[$k]);
                $from[] = $arrayCssFrom[$k];
                $to[] = $arrayCssTo[$k];
            }
        }
        return str_replace($from, $to, $content);
    }

    public function saveImages($conma) {
        $arrayImages = array();
        /* Tìm kiếm những đường link ảnh có trong file html */
        if (preg_match_all('/<img[^~]+?('.$conma.'=)(("[^"]*")|(\'[^\']*\'))[^>]*>/', $this->contentHTML, $arrayImages)) {
            /* mảng file ảnh trong nội dung */
            $arrayImages = $arrayImages[2];
            /* mảng file ảnh trong nội dung trước khi thay đổi */
            $arrayImagesFrom = array();

            /* mảng file ảnh trong nội dung sau khi thay đổi */
            $arrayImagesTo = array();

            foreach ($arrayImages as $key => $linkImage) {
                /* mảng file ảnh trong nội dung sau khi thay đổi */
                $linkImage = trim($this->getLinkTrue($linkImage));

                if ($linkImage == "") {
                    unset($arrayImages[$key]);
                } else {
                    $linkImage = str_replace(['"',"'"], ["",""], $linkImage);
                    /* tập hợp mảng cần link ảnh trước khi cần thay đổi */
                    $arrayImagesFrom[] = $linkImage;

                    /* Kiểm tra xem có domain cho link chưa? Chưa có thì thêm vào */
                    $linkImage = $this->getLinkHTTP($linkImage);

                    /* Lấy tên file ảnh */
                    $nameImage = $this->getFileName($linkImage);

                    /* Thêm vào mảng ảnh thay đổi */
                    $arrayImagesTo[] = 'images/' . $nameImage;
//                    $arrayImagesTo[] = $linkImage;
                    /* Kiểm tra xem ảnh có có chưa? chưa có thì ghi ảnh */
                    $this->saveFile($this->linkFileImages . $nameImage, $linkImage);
                }
            }
            /* Thay đổi đường dẫn ảnh trong file html mới */
            $this->contentHTML = $this->replaceFromTo($arrayImagesFrom, $arrayImagesTo, $this->contentHTML);
        }
    }

    public function copyweb($html = false) {

        /* Kiểm tra xem đã có thư mục web chưa? Chưa có thì tạo. */
        /* load file */
        $this->contentHTML = $html ? $html : filegetcontents($this->linkCopy);
        if(!$html && preg_match('/.css/',$this->linkCopy)) {
            $this->proccessContentCss($this->linkCopy);
        } else {
            /* Save file */
            $this->saveJs();

            /* Save css */
            $this->saveCss();
            /* get all image */
//            $this->saveImages('src');

            $this->proccessContentHtmlCss();
    //        $this->saveImages('data-original');
    //        $this->saveImages('data-src');
            /* Ghi file HTML */
            $this->contentHTML = $this->optimazeOutputHtml($this->contentHTML);
            file_put_contents($this->linkFilePublic . $this->nameFile, $this->contentHTML);
        }
    }

    public function getArrayJs($content) {
        $arrayJs = array();
        if (preg_match_all('/<script(.*)(src="[^"]*"|src=\'[^\']*\')[^>]*>/', $content, $arrayJs)) {
            /* Mảng tất cả link file js */
            $arrayJs = $arrayJs[2];
            foreach ($arrayJs as $key => $value) {
                $arrayJs[$key] = preg_replace("/(src=('|\"))|(('|\")$)/", "", $value);
            }
            return $arrayJs;
        }
        return false;
    }

    public function getArrayJsHeader() {
        return $this->getArrayJs(substr($this->contentHTML, 0, strpos($this->contentHTML, "</head>")));
    }

    public function getArrayJsFooter() {
        return $this->getArrayJs(substr($this->contentHTML, strpos($this->contentHTML, "</head>") + 5, strlen($this->contentHTML)));
    }
    
    public $arrayCopyLinkJs = [];
    public $arrayCopyLinkCss = [];
    
    public function optimazeOutputHtml($html) {
        if (preg_match('/<\/body>/', $html)) {
            $html = str_replace(array('<script type="text/javascript"><!--', '//--></script>'), array('<script type="text/javascript">', '</script>'), $html);
            $html = preg_replace('/<\!--([^\]]+?(-->)+)/i', '', $html);
            preg_match_all('/<\!--([^©]+?(-->)+)/i', $html, $arrayComment);
            $arraySearch = array();
            $arrayReplace = array();
            if (isset($arrayComment[0]) && count($arrayComment[0])) {
                foreach ($arrayComment[0] as $k => $comment) {
                    $replace = '©©©©' . $k;
                    $arraySearch[] = $comment;
                    $arrayReplace[] = $replace;
                }
                $html = str_replace($arraySearch, $arrayReplace, $html);
            }
            preg_match_all('/<link.*?(\.css)[^>]+>/', $html, $arrayCss);
            preg_match_all('/<script([^©]+?(<\/script>)+)/i', $html, $arrayJs);
            $this->arrayCopyLinkJs = UtilityHtmlFormat::getLinkJsByContent($html);
            $this->arrayCopyLinkCss = UtilityHtmlFormat::getLinkCssByContent($html);
            $html = preg_replace('/(<link.*?(\.css)[^>]+>)|<script([^©]+?(<\/script>)+)/', '', $html);
            $htmlCss = '';
            $htmljs = '';
            if (isset($arrayCss[0]) && count($arrayCss[0])) {
                foreach ($arrayCss[0] as $css) {
                    $htmlCss .= $css . "\n";
                }
            }
            if (isset($arrayJs[0]) && count($arrayJs[0])) {
                foreach ($arrayJs[0] as $js) {
                    $htmljs .= $js . "\n";
                }
            }

            $arrayReplace[] = '</body>';
            $arraySearch[] = $htmlCss . $htmljs . '</body>';
            $html = str_replace($arrayReplace, $arraySearch, $html);
        }
        return $html;
    }

}

?>