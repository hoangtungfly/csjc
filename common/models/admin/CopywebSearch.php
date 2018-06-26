<?php

namespace common\models\admin;

use common\utilities\SimpleHtmlDom;
use common\utilities\UtilityHtmlFormat;

class CopywebSearch extends Copyweb {

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

    /* Link file public */
    public $linkFilePublic;

    public $arraycss1 = array();
    /* Khởi tạo 4 propertiy tên thư mục, link copy, linkweb chuẩn, tên file cần ghi */

    /* Thêm đường dẫn http vào link */

    public function getLinkHTTP($value) {
        $linkweb = $this->linkweb;
        $array_web = explode('/',$this->linkweb);
        
        /* $kiểm tra xem link có http hay chưa? chưa có thì thêm vào */
        if (str_replace('http', '', $value) == $value) {
            if (preg_match("~^//~", $value)) {
                $linkweb = 'http:';
                if(preg_match('/https/', $this->linkweb)) {
                    $linkweb = 'https:';
                }
            } else {
                if (preg_match("~^/~", $value)) {
                    $domain = $array_web[0].'//'.$array_web[2];
                    $linkweb = $domain;
                } else {
                    unset($array_web[count($array_web)-1]);
                    $linkweb = implode('/',$array_web).'/';
                }
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
        if(!$name && isset($array[count($array) - 2])) {
            $name1 = $array[count($array) - 1];
            $chamhoi = preg_replace('/(.*)\?/','',$name1);
            $name = $array[count($array) - 2] . $chamhoi . '.jpg';
        }
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
        $arraycss = explode('/', $linkCss);
        $array = array();
        /* Phần tử đầu tiên là đường dẫn chuẩn của web */
        if (strpos($linkCss, 'http') === false) {
            if($linkCss{1}.$linkCss{2} == '//') {
                $array[] = 'http:';
            } else {
                $array[] = $linkweb;
            }
        }
        foreach ($arraycss as $key => $value) {
            $array[] = $value;
        }
        /* Xóa phần từ cuối cùng tên file của mảng */
        unset($array[count($array) - 1]);
        return $array;
    }

    /* save file ảnh hoặc font trong css */
    
    public function getLinkImgInContentCss($linkCss,$linkImg) {
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
                else {
                    var_dump('saveImageCss ' . $value . $linkFileImg . $nameFile);
                }
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
    public $countcss = 0;
    public function proccessContentCss($linkCss, $linknew = false) {
        /* lấy nội dung file css */
        $content = false;
        $name = pathinfo($linkCss,PATHINFO_BASENAME);
        $name = preg_replace('/\?(.*)/','',$name);
        if(!strpos($name,'.css')) {
            $name .= '.css';
        }
        if(is_file($this->linkFilePublic.'cssold/'.$name)) {
            $content = file_get_contents($this->linkFilePublic.'cssold/'.$name);
        }
        if(!$content && $linknew && is_file($linknew)) {
            $content = file_get_contents($linknew);
        }
        if (!$content) {
            $content = @filegetcontents($linkCss);
        }
        if (!$content) {
            return false;
        }
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
                    if(preg_match('/\.css$/',$value)) {
                        continue;
                    }
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
            $this->countcss++;
            foreach($match_all[2] as $link) {
                $linkAll = $this->getLinkImgInContentCss($linkCss, $link);
                $nameValue = $this->getFileNameCss($link);
                $content_new = $this->proccessContentCss($linkAll,$this->linkFileCss . $nameValue);
                if($content_new) {
                    file_put_contents($this->linkFileCss . $nameValue, $content_new);
                    $content = str_replace($link, $this->linkFileCss . $nameValue, $content);
                }
                
            }
        }
        return $content;
    }

    /* Xử lí nội dung trong file html css */

    public function proccessContentHtmlCss() {
        /* lấy nội dung file css */
        $content = $this->content_html;

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
                    
//                    $linkCron = $this->linkweb . $value;
                    $linkCron =  $this->getLinkHTTP($value);
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
        $this->content_html = $content;
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
            $content = filegetcontents($linkCron);
            if ($content)
                file_put_contents($linkSave, $content);
        }
    }

    public function saveJs() {
        
        $arrayjs = array();
        $arrayjs_attr = [];
        $listObj = $this->object_content->find('script');
        if($listObj && count($listObj)) {
            foreach($listObj as $obj) {
                if(isset($obj->attr['src'])) {
                    $arrayjs[] = $obj->attr['src'];
                }
            }
        }
        /* Mảng link file js của content */
        $arrayjsFrom = array();

        /* Mảng link file js của content khi save */
        $arrayjsTo = array();
        /* Xử lí từng file js */
        foreach ($arrayjs as $key => $linkJs) {
            /* Lấy đường dẫn thật của link file css xóa định dạng src="" ỏ src='' của file js */
            $linkJs = $this->getLinkTrue($linkJs);
            if ($linkJs) {
                /* Trường hợp link thật sự là file js */
                if (preg_match("/(google)|(addthis)/", $linkJs)) {
                    $arrayjs_attr[] = [
                        'old'   => $linkJs,
                        'new'   => $linkJs,
                    ];
                    continue;
                }
                /* tập hợp mảng cần link js trước khi cần thay đổi */
                $arrayjsFrom[] = $linkJs;

                /* Thêm đường dẫn http vào link js */
                $linkJs = $this->getLinkHTTP($linkJs);

                /* Lấy tên file js */
                $nameValue = $this->getFileName($linkJs);
                $nameValue = str_replace('-js', '.js', $nameValue);
                if(strlen($nameValue) > 30)
                    $nameValue = substr ($nameValue, strlen($nameValue) - 25);
                if(!preg_match('/\.js$/',$nameValue)) {
                    $nameValue .= '.js';
                }
                /* tập hợp mảng link js sau khi thay đổi */
                $nameValueNew = 'js/' . $nameValue;
                $arrayjsTo[] = $nameValueNew;
                $arrayjs_attr[] = [
                    'old'   => $linkJs,
                    'new'   => $nameValueNew,
                ];
                /* Kiểm tra xem file js này đã có trong máy chưa
                 * chưa có thì ghi vào file
                 */
                if (!is_file($this->linkFileJs . $nameValue))
                    $this->saveFile($this->linkFileJs . $nameValue, $linkJs);
            }
        }
        $this->arrayjs = json_encode($arrayjs_attr);
//        $this->save();
        /* Thay đổi đường dẫn file js trong nội dung file cần ghi */
        $this->content_html = $this->replaceFromTo($arrayjsFrom, $arrayjsTo, $this->content_html);
    }

    public function getFileNameCss($linkCss) {
        /* Lấy tên file css */
        $nameValue = $this->getFileName($linkCss);
        $nameValue = str_replace('-css', '.css', $nameValue);
        if(!strpos($nameValue, '.css')) {
            $nameValue .= '.css';
        }
        $name2 = preg_replace('/[^a-zA-Z0-9]+/','',$linkCss).'.css';
        if (in_array($nameValue, $this->arraycss1)) {
            $nameValue = $name2;
        }
        $this->arraycss1[] = $nameValue;
        return $nameValue;
    }

    public function saveCss() {
        $arraycss = array();
        $arraycss_attr = [];
        /* Tìm kiếm những đường link css có trong file html */
        $listObj = $this->object_content->find('link');
        $arraycss = [];
        if($listObj && count($listObj)) {
            foreach($listObj as $obj) {
                if(isset($obj->attr['rel']) && strtolower($obj->attr['rel']) == 'stylesheet') {
                    $arraycss[] = $obj->attr['href'];
                } else if (isset($obj->attr['rel']) && strtolower($obj->attr['rel']) == 'text/css') {
                    $arraycss[] = $obj->attr['href'];
                }
            }
        }
        if ($arraycss && count($arraycss)) {
            /* Mảng link file css của content */
            $arraycssFrom = array();
            /* Mảng link file css của content khi save */
            $arraycssTo = array();
            $this->arraycss = array();
            /* Xử lí từng file css */
            foreach ($arraycss as $key => $linkCss) {
                if ($linkCss) {
                    /* Lấy đường dẫn thật của link file css xóa định dạng href="" ỏ href='' của file css */
                    $linkCss = $this->getLinkTrue($linkCss);
                    /* Trường hợp link thật sự là file css */
                    /* tập hợp mảng cần link css trước khi cần thay đổi */
                    if (preg_match("/google/", $linkCss)) {
                        $arraycss_attr[] = [
                            'old'   => $linkCss,
                            'new'   => $linkCss,
                        ];
                        continue;
                    }
                    $arraycssFrom[] = $linkCss;

                    /* Thêm đường dẫn http vào link css */
                    $linkCss = $this->getLinkHTTP($linkCss);
                    $nameValue = $this->getFileNameCss($linkCss);
                    
                    /* tập hợp mảng link css sau khi thay đổi */
                    $nameValueNew = 'css/' . $nameValue;
                    $arraycssTo[] = $nameValueNew;
                    $arraycss_attr[] = [
                        'old'   => $linkCss,
                        'new'   => $nameValueNew,
                    ];
                    /* Kiểm tra xem file css này đã có trong máy chưa
                     * chưa có thì ghi vào file
                     */
                    if ($nameValue && !is_file($this->linkFileCss . $nameValue)) {
                        /* Xử lí nội dung trong file css   
                         * Lấy đường dẫn ảnh và font ghi vào thư mục trong máy
                         */
                        $content = $this->proccessContentCss($linkCss);
                        /* Ghi file css này */

                        if ($content) {
                            file_put_contents($this->linkFileCss . $nameValue, $content);
                        } else {
                            var_dump($linkCss, $nameValue);
                        }
                    }
                }
            }
            $this->arraycss = json_encode($arraycss_attr);
//            $this->save();
            /* Thay đổi đường dẫn file css trong nội dung file cần ghi */
            $this->content_html = $this->replaceFromTo($arraycssFrom, $arraycssTo, $this->content_html);
        }
    }
    
    public function replaceFromTo($arraycssFrom, $arraycssTo, $content) {
        $from = [];
        $to = [];
        if($arraycssFrom) {
            $arraycssFrom1 = $arraycssFrom;
            foreach($arraycssFrom as $key => $value) {
                $length = 0;
                $k = $key;
                foreach($arraycssFrom1 as $key2 => $value2) {
                    $length2 = strlen($value2);
                    if($length2 >= $length) {
                        $k = $key2;
                    }
                }
                unset($arraycssFrom1[$k]);
                $from[] = $arraycssFrom[$k];
                $to[] = $arraycssTo[$k];
            }
        }
        return str_replace($from, $to, $content);
    }

    public function saveImages($conma) {
        $arrayImages = array();
        $arrayImages_attr = [];
        /* Tìm kiếm những đường link ảnh có trong file html */
        if (preg_match_all('/<img[^~]+?('.$conma.'=)(("[^"]*")|(\'[^\']*\'))[^>]*>/', $this->content_html, $arrayImages)) {
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
//                    $linkImage = str_replace('/css/','/',$linkImage);
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
            $this->content_html = $this->replaceFromTo($arrayImagesFrom, $arrayImagesTo, $this->content_html);
        }
    }
    
    public function saveImagesset($conma) {
        $arrayImages = array();
        $arrayImages_attr = [];
        /* Tìm kiếm những đường link ảnh có trong file html */
        if (preg_match_all('/<img[^~]+?('.$conma.'=)(("[^"]*")|(\'[^\']*\'))[^>]*>/', $this->content_html, $arrayImages)) {
            /* mảng file ảnh trong nội dung */
            $arrayImagesMany = $arrayImages[2];
            /* mảng file ảnh trong nội dung trước khi thay đổi */
            $arrayImagesFrom = array();

            /* mảng file ảnh trong nội dung sau khi thay đổi */
            $arrayImagesTo = array();
            
            $arrayImages = [];
            foreach ($arrayImagesMany as $key => $linkImage) {
                $linkImage = str_replace(['"',"'"], ["",""], $linkImage);
                $arrayLinkImage = explode(',',$linkImage);
                foreach($arrayLinkImage as $ll) {
                    $ll = trim($ll);
                    $ll = preg_replace('/ [0-9]+(.*)$/','',$ll);
                    $arrayImages[] = trim($ll);
                }
            }
            

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
                    $linkImage = str_replace('/css/','/',$linkImage);
                    /* Lấy tên file ảnh */
                    $nameImage = $this->getFileName($linkImage);

                    /* Thêm vào mảng ảnh thay đổi */
                    $arrayImagesTo[] = 'images/' . $nameImage;
//                    $arrayImagesTo[] = $linkImage;
                    /* Kiểm tra xem ảnh có có chưa? chưa có thì ghi ảnh */
                    echo $linkImage.' ' . $this->linkFileImages . $nameImage . '<br>';
                    $this->saveFile($this->linkFileImages . $nameImage, $linkImage);
                }
            }
            /* Thay đổi đường dẫn ảnh trong file html mới */
            $this->content_html = $this->replaceFromTo($arrayImagesFrom, $arrayImagesTo, $this->content_html);
        }
    }
    
    public $object_content = false;

    public function copyweb() {
        /* Link thư mục chữa trong application */
        $this->linkFilePublic = APPLICATION_PATH .'/application/' . $this->directory . '/public/';
        $this->linkFileCss = $this->linkFilePublic . 'css/';
        $this->linkFileImages = $this->linkFilePublic . 'images/';
        $this->linkFileJs = $this->linkFilePublic . 'js/';
        $this->linkFileImg = $this->linkFilePublic . 'img/';
        $this->linkFileFont = $this->linkFilePublic . 'font/';
        rmkdir($this->linkFilePublic);
        rmkdir($this->linkFileCss);
        rmkdir($this->linkFileImages);
        rmkdir($this->linkFileJs);
        rmkdir($this->linkFileImg);
        rmkdir($this->linkFileFont);
        /* Kiểm tra xem đã có thư mục web chưa? Chưa có thì tạo. */
        /* load file */
        /* Save file */
        $this->object_content = SimpleHtmlDom::str_get_html($this->content_html);
        if(!$this->object_content) {
            return false;
        }
        /* Save css */
        $this->saveCss();
        $this->saveJs();

        /* get all image */
        $this->saveImages('src');
        $this->saveImages('data-src');
        $this->saveImages('data-image');
        $this->saveImagesset('srcset');

        $this->proccessContentHtmlCss();
//        $this->saveImages('data-original');
//        $this->saveImages('data-src');
        /* Ghi file HTML */
        $this->content_html = $this->optimazeOutputHtml($this->content_html);
        file_put_contents($this->linkFilePublic . $this->filename, $this->content_html);
    }

    public function getArrayJs($content) {
        $arrayjs = array();
        if (preg_match_all('/<script(.*)(src="[^"]*"|src=\'[^\']*\')[^>]*>/', $content, $arrayjs)) {
            /* Mảng tất cả link file js */
            $arrayjs = $arrayjs[2];
            foreach ($arrayjs as $key => $value) {
                $arrayjs[$key] = preg_replace("/(src=('|\"))|(('|\")$)/", "", $value);
            }
            return $arrayjs;
        }
        return false;
    }

    public function getArrayJsHeader() {
        return $this->getArrayJs(substr($this->content_html, 0, strpos($this->content_html, "</head>")));
    }

    public function getArrayJsFooter() {
        return $this->getArrayJs(substr($this->content_html, strpos($this->content_html, "</head>") + 5, strlen($this->content_html)));
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
            preg_match_all('/<link.*?(\.css)[^>]+>/', $html, $arraycss);
            preg_match_all('/<script([^©]+?(<\/script>)+)/i', $html, $arrayjs);
            $this->arrayCopyLinkJs = UtilityHtmlFormat::getLinkJsByContent($html);
            $this->arrayCopyLinkCss = UtilityHtmlFormat::getLinkCssByContent($html);
            $html = preg_replace('/(<link.*?(\.css)[^>]+>)|<script([^©]+?(<\/script>)+)/', '', $html);
            $htmlCss = '';
            $htmljs = '';
            if (isset($arraycss[0]) && count($arraycss[0])) {
                foreach ($arraycss[0] as $css) {
                    $htmlCss .= $css . "\n";
                }
            }
            if (isset($arrayjs[0]) && count($arrayjs[0])) {
                foreach ($arrayjs[0] as $js) {
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
