<?php

namespace common\utilities;

use DOMDocument;
use yii\helpers\Html;

class UtilityHtmlFormat {

    public static function stripUnicode($str, $doi = '-') {
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

    public static function stripSymbol($str) {
        return str_replace(array("\r\n", "\n", "\r", "-", "+", "/", "<", ">", ",", "!", ":", ".", "?", "|", "#", "&", "%", "^", "*", ")", "(", "_", "{", "}", "[", "]"), " ", $str);
    }

    public static function parseToAlias($str) {
        $noMark = strtolower(UtilityHtmlFormat::stripUnicode($str));
        $noMark = preg_replace('/[^a-z0-9\s]+/i', '', $noMark);
        $noMark = preg_replace('/\s+/', ' ', $noMark);
        $alias = str_replace(" ", "-", trim(strtolower($noMark)));
        return $alias;
    }

    public static function string_cut($string, $length) {
        $des = strip_tags($string);
        $des = str_replace('  ', ' ', $des);
        $sub_fix = substr($des, $length - 1, $length + 31);
        $pos = UtilityHtmlFormat::find_str_position(" ", $sub_fix);
        $pos += $length;
        $des = substr($des, 0, $pos);
        return $des;
    }

    public static function find_str_position($find, $string) {
        $pos = strpos($string, $find);
        return $pos;
    }

    public static function sub_string($str, $len, $allowable_tags = '', $skip_line = false, $more = '...', $encode = 'utf-8') {
        $allowable_tags = '<br><br/><p>';
        /* Loại bo ca the khong hop le */
        $str = trim(strip_tags($str, $allowable_tags));
        /* Loai bo style cua cac the con lai chuyen the p thanh the br */
        $str = preg_replace('#<\/?p[^>]*>#', '<br/>', $str); // echo $str;die;
        //$str = preg_replace('#<\/?p\s*\w*>#','<br/>',$str);
        /* bỏ các dấu xuống dòng liên tiếp */
        $str = preg_replace('#(<br[^>]*>\s*){2,}#', '<br/>', $str); // bo cac dau xuong dong lien tiep
        /* Bỏ các thẻ rỗng */
        /* bo cac khoang trang canh nhau */
//        $str = preg_replace('/(\s)+/', ' ', $str);

        /* return ngay neu chuỗi đã hợp lệ */
        if ($str == "" || $str == NULL || mb_strlen($str, $encode) <= $len) {
            return $str;
        }
        /* Cắt chuoi theo độ dài đã được yêu cầu */
        $str = mb_substr($str, 0, $len, $encode);
        /* De phong cắt phải giữa thẻ  br */
        $pos = mb_strripos($str, "<", 0, $encode);
        if (($len - $pos) < 5)
            $str = mb_substr($str, 0, $pos, $encode);
        /* nếu cắt phải giữa một từ thì bỏ cả từ đó đi luôn (chỉ thao tác với từ hợp lệ) */
        if ($str != "") {
            $pos = mb_strripos($str, " ", 0, $encode);
            if ($pos !== false && $pos > ($len - 8)) {
                $str = mb_substr($str, 0, $pos + 1, $encode);
            }
            $str .= $more;
        }
        return $str;
    }

    // Thay the cac Url trong noi dung thanh the a
    public static function addAtag($content) {
        $expression1 = "%[^(http:\/\/)(ftp:\/\/)(https:\/\/)](www\.){1,1}(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&\%@!\-\/]))?%";
        $expression = "%((ftp|http|https):\/\/){1,1}(www\.)?(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&\%@!\-\/]))?%";
        $content = preg_replace($expression, " <a href=\"$0\" target=\"_blank\">$0</a> ", $content);
        $content = preg_replace($expression1, " <a href=\"http://$3\" target=\"_blank\">$0</a> ", $content);
        return $content;
    }

    public static function sub_strip($str, $len, $allowable_tags = '', $skip_line = false, $more = '...', $encode = 'utf-8') {
        $allowable_tags = '<br><br/><p>';
        /* Loại bo ca the khong hop le */
        $str = trim(strip_tags($str, $allowable_tags));

        /* Loai bo style cua cac the con lai chuyen the p thanh the br */
        $str = preg_replace('#<\/?p[^>]*>#', '<br/>', $str); // echo $str;die;
        //$str = preg_replace('#<\/?p\s*\w*>#','<br/>',$str);
        /* bỏ các dấu xuống dòng liên tiếp */
        $str = preg_replace('#(<br[^>]*>\s*){2,}#', '<br/>', $str); // bo cac dau xuong dong lien tiep
        /* Bỏ các thẻ rỗng */
        /* bo cac khoang trang canh nhau */
//        $str = preg_replace('#\s+#', ' ', $str);

        /* return ngay neu chuỗi đã hợp lệ */
        if ($str == "" || $str == NULL || mb_strlen($str, $encode) <= $len) {
            return $str;
        }
        /* Cắt chuoi theo độ dài đã được yêu cầu */
        $str = mb_substr($str, 0, $len, $encode);
        /* De phong cắt phải giữa thẻ  br */
        $pos = mb_strripos($str, "<", 0, $encode);
        if (($len - $pos) < 5)
            $str = mb_substr($str, 0, $pos, $encode);
        /* nếu cắt phải giữa một từ thì bỏ cả từ đó đi luôn (chỉ thao tác với từ hợp lệ) */
        if ($str != "") {
            $pos = mb_strripos($str, " ", 0, $encode);
            if ($pos !== false && $pos > ($len - 8)) {
                $str = mb_substr($str, 0, $pos + 1, $encode);
            }
            $str .= $more;
        }
        //var_dump($str);die;
        return $str;
    }

    /**
     * Get substring
     * @param $str String to be cutted
     * @param $len Length of substring
     * @param $more TRUE or FALSE Add ... or not
     * @return substring with specified length and 
     */
    public static function cut_string($str, $len, $more) {
        if ($str == "" || $str == NULL)
            return $str;
        if (is_array($str))
            return $str;
        $str = trim($str);
        if (strlen($str) <= $len)
            return $str;
        $str = substr($str, 0, $len);
        if ($str != "") {
            if (!substr_count($str, " ")) {
                if ($more)
                    $str .= " ...";
                return $str;
            }
            //while (strlen($str) && ($str["strlen($str)-1"] != " ")) {
            while (strlen($str) && (substr($str, -1) != " ")) {
                $str = substr($str, 0, -1);
            }
            $str = substr($str, 0, -1);
            if ($more) {
                $str .= " ...";
                return $str;
            }
        }
        return $str . "...";
    }

    /**
     * 
     * @param string $str
     * @param int $len
     * @param string $charset
     * @param string $more
     * @return string
     */
    public static function short_desc($str, $len, $charset = 'UTF-8', $more = '...') {
        $str = strip_tags($str);
        $str = html_entity_decode($str, ENT_QUOTES, $charset);
        if (strlen($str) <= $len) {
            return Html::encode($str);
        }
        if (mb_strlen($str, $charset) > $len) {
            $arr = explode(' ', $str);
            $str = mb_substr($str, 0, $len, $charset);
            $arrRes = explode(' ', $str);
            $last = $arr[count($arrRes) - 1];
            unset($arr);
            if (strcasecmp($arrRes[count($arrRes) - 1], $last)) {
                if (count($arrRes) > 1) {
                    unset($arrRes[count($arrRes) - 1]);
                }
            }
            return Html::encode(implode(' ', $arrRes) . $more);
        }
        return Html::encode($str . $more);
    }

    /**
     * This function extracts the non-tags string and returns a correctly formatted string
     * It can handle all html entities e.g. &amp;, &quot;, etc..
     *
     * @param string $s
     * @param integer $srt
     * @param integer $len
     * @param bool/integer	Strict if this is defined, then the last word will be complete. If this is set to 2 then the last sentence will be completed.
     * @param string A string to suffix the value, only if it has been chopped.
     */
    public static function html_substr($s, $srt, $len = NULL, $strict = false, $suffix = NULL) {
        if (is_null($len)) {
            $len = strlen($s);
        }

        $f = 'static $strlen=0; 
			if ( $strlen >= ' . $len . ' ) { return "><"; } 
			$html_str = html_entity_decode( $a[1] );
			$subsrt   = max(0, (' . $srt . '-$strlen));
			$sublen = ' . ( empty($strict) ? '(' . $len . '-$strlen)' : 'max(@strpos( $html_str, "' . ($strict === 2 ? '.' : ' ') . '", (' . $len . ' - $strlen + $subsrt - 1 )), ' . $len . ' - $strlen)' ) . ';
			$new_str = substr( $html_str, $subsrt,$sublen); 
			$strlen += $new_str_len = strlen( $new_str );
			$suffix = ' . (!empty($suffix) ? '($new_str_len===$sublen?"' . $suffix . '":"")' : '""' ) . ';
			return ">" . htmlentities($new_str, ENT_QUOTES, "UTF-8") . "$suffix<";';

        $str = preg_replace(
                array("#<[^/][^>]+>(?R)*</[^>]+>#", "#(<(b|h)r\s?/?>){2,}$#is"), "", trim(
                        rtrim(
                                ltrim(
                                        preg_replace_callback(
                                                "#>([^<]+)<#", create_function('$a', $f), ">$s<"
                                        ), ">"
                                ), "<"
                        )
                )
        );
        return str_replace("&amp;#39;", "'", $str);
    }

    /**
     * remove style, script tag from html
     */
    public static function html_remove_script(&$text) {
        $ns = preg_replace('/(<script[^>]*>.+?<\/script>|<style[^>]*>.+?<\/style>)/s', '', $text);
        $text = $ns;
        return $ns;
    }

    /**
     * get string in first tag html
     * @param string 
     * @param tag
     */
    public static function get_string_in_first_tag($text, $tag) {
        $matches = array();
        if (preg_match("/(<" . $tag . "[^>]*>.+?<\/$tag>)/", $text, $matches)) {
            if ($matches) {
                $resutl = array_shift($matches);
                return strip_tags($resutl);
            }
        }
        return '';
    }

    /**
     * 
     * @param type $str
     * @param type $len
     * @param type $charset
     * @param type $more
     */
    public static function removeStringBetweenFile($str, $len, $charset = 'UTF-8', $more = '...') {
        if (strlen($str) <= $len) {
            return $str;
        }
        $last_string = mb_substr($str, -5);
        $first_string = self::short_desc($str, $len, $charset, $more);
        return $first_string . $last_string;
    }

    /**
     * format number
     * 
     * @param value
     */
    public static function numberFormat($value, $f = 0) {
        if(!$value) return '';
        $value = preg_replace('/[^0-9,\.]+/', '', $value);
        if (strpos($value, '.') !== false && !$f) {
            $a = explode('.', $value);
            $f = strlen($a[1]);
        } else {
            $value = (int)$value;
        }
        return $value ? number_format($value, $f,".",".") : '';
    }

    public static function numberFormatPrice($value, $f = 0) {
        $val = self::numberFormat($value, $f);
        if ($val) {
            return CURRENCY_CODE.$val;
        }
        return '';
    }

    public static function numberFloat($value, $f = 2) {
        if(!$value) $value = 0;
        return number_format($value, $f);
    }

    public static function numberFloatPrice($value, $f = 2) {
        $val = self::numberFloat($value, $f);
//        if ($val) {
            return CURRENCY_CODE . $val;
//        }
//        return '';
    }

    public static function numberFloatPriceCode($value, $f = 0) {
        $val = self::numberFloat($value, $f);
        if ($val) {
            return CURRENCY_CODE . $val;
        }
        return '';
    }
    
    public static function numberFormatDisplayed($value,$f = 0,$symbol = CURRENCY_SYMBOL) {
        $value = $value ? $value : 0;
        $value = self::numberFormat($value, $f);
        return CURRENCY_CODE . $value .' '.$symbol;
    }

    /**
     * format number
     * 
     * @param value
     */
    public static function price($value) {
        return $value ? CURRENCY_DISPLAYED . ' ' . number_format($value) : '';
    }

    /**
     * 
     * @param type $unname
     * @param type $arrayName
     * @return boolean
     */
    public static function testStrInArray($unname, $arrayName) {
        $rs = false;
        if (count($arrayName) > 0) {
            foreach ($arrayName as $key => $value) {
                if ($unname == $value) {
                    $rs = true;
                    break;
                }
            }
        }

        return $rs;
    }

    /**
     * 
     * @param type $arrayStepName
     * @param type $stepname
     * @param type $att
     * @return type
     */
    public static function getNameInArray($arrayStepName, $stepname, $att) {
        $unname = trim($stepname);
        $arrayName = array();
        foreach ($arrayStepName as $key => $item) {
            if (preg_match("/" . trim($stepname) . "/", $item->$att)) {
                $arrayName[] = $item->$att;
            }
        }
        if (count($arrayName) > 0) {
            $dem = 1;
            while (self::TestStrInArray($unname, $arrayName) == true) {
                $unname = $stepname . '(' . $dem . ')';
                $dem++;
            }
        }
        return trim($unname);
    }

    /**
     * price format
     */
    public static function priceFormat($value) {
        return number_format($value, 2, ".", ".");
    }

    public static function removeElementsByTagName($tagName, $document) {
        $nodeList = $document->getElementsByTagName($tagName);
        for ($nodeIdx = $nodeList->length; --$nodeIdx >= 0;) {
            $node = $nodeList->item($nodeIdx);
            $node->parentNode->removeChild($node);
        }
    }

    public static function stripTag($text = '', $tags = []) {
        if ($text == '')
            return $text;
        return $text;
        $tags = $tags ? $tags : ['script', 'style', 'link'];
        $doc = new DOMDocument();
        $doc->loadHTML($text);
        foreach ($tags as $tag) {
            self::removeElementsByTagName($tag, $doc);
        }
        return $doc->saveHTML();
    }

    public static function deleteSpace($str) {
        return trim(preg_replace('/(\s)+/', ' ', $str));
    }

    public static function nameCopy($str) {
        $result = 1;
        $array = array();
        $str = trim($str);
        if (preg_match("/ - copy[0-9]*$/", $str, $array)) {
            $result = (int) str_replace(" - copy", "", $array[0]);
            $result++;
        }
        return $result;
    }

    /* Kiểm tra một xâu có là số nguyên không? */

    public static function isInteger($input) {
        return(ctype_digit(strval($input)));
    }

    /* replace mảng các key định dạng [$key] với
     * 2 biến, biến 1 là mảng biến 2 là xâu
     * mảng key replace thành mảng value
     */

    /**
     * 
     * @param array $array
     * @param string $str
     * @param array $conmaArray
     * @return type
     */
    public static function replaceArray($array, $str, $alias = '', $conmaArray = ['[', ']']) {
        $array1 = array();
        $array2 = array();
        if (is_array($conmaArray) && count($conmaArray) == 2) {
            $conma1 = $conmaArray[0];
            $conma2 = $conmaArray[1];
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    if (isset($value[0])) {
                        $str = self::replaceArrayTemplate($value, $str, $key, $conmaArray);
                    } else {
                        $str = self::replaceArray($value, $str, $key, $conmaArray);
                    }
                } else {
                    if ($alias != "") {
                        $array1[] = $conma1 . $alias . '.' . $key . $conma2;
                    } else {
                        $array1[] = $conma1 . $key . $conma2;
                    }

                    $array2[] = $value;
                }
            }
            return str_replace($array1, $array2, $str);
        } else {
            return $str;
        }
    }

    /**
     * 
     * @param array $arrayReplace
     * @param string $template
     */
    public static function replaceArraymany($arrayReplace, $template, $alias, $comma = array('[', ']')) {
        if (is_array($arrayReplace) && count($arrayReplace) > 0) {

            $str = '';
            $flag = false;
            foreach ($arrayReplace as $key => $array) {
                if (is_array($array)) {
                    if (count($array)) {
                        $str .= self::replaceArray($array, $template, $alias, $comma);
                    }
                } else if ($key == 0) {
                    $flag = true;
                    break;
                }
            }

            if ($flag) {
                return self::replaceArray($arrayReplace, $template, $comma);
            } else {
                return $str;
            }
        } else {
            return $template;
        }
    }

    /**
     * 
     * @param array $arrayReplace
     * @param string $content
     */
    public static function replaceArrayTemplate($arrayReplace, $content, $tagBlock, $comma = array('[', ']')) {
        $block_start_delim = '\\<\\!--';
        $block_end_delim = '--\\>';

        $block_start_word = 'BEGIN\\:';
        $block_end_word = 'END\\:';
        preg_match_all("/{$block_start_delim}{$block_start_word}{$tagBlock}{$block_end_delim}([^Æ]+?({$block_start_delim}{$block_end_word}{$tagBlock}{$block_end_delim})+)/", $content, $a);
        if (isset($a[1][0]) && ($html = $a[1][0])) {
            $html2 = self::replaceArraymany($arrayReplace, $html, $tagBlock, $comma);
            return str_replace($html, $html2, $content);
        }
        return $content;
    }

    public static function replaceTemplate($content, $array_replace, $comma = array('[', ']')) {
        $comma1 = $comma[0];
        $comma2 = $comma[1];
        if (is_array($array_replace) && count($array_replace)) {
            $array_replace['url']   = HTTP_HOST;
            $arraySearch = array();
            $arrayReplace = array();
            $arrayReplaceArray = array();
            foreach ($array_replace as $key => $value) {
                if (is_array($value)) {
                    if (isset($value[0])) {
                        $arrayReplaceArray[$key] = $value;
                    } else {
                        $content = self::replaceArray($value, $content, '', $comma);
                    }
                } else {
                    $arraySearch[] = $comma1 . $key . $comma2;
                    $arrayReplace[] = $value;
                }
            }
            if (count($arrayReplace) && count($arraySearch))
                $content = str_replace($arraySearch, $arrayReplace, $content);
            if (count($arrayReplaceArray)) {
                foreach ($arrayReplaceArray as $key => $value) {
                    $content = self::replaceArrayTemplate($value, $content, $key, $comma);
                }
            }
        }
        $content = str_replace(array('http://http://', 'http://https://'), array('http://', 'http://'), $content);
        preg_match_all('/(src="[^"]+")|(src=\'[^\']+\')/', $content, $arrayReplaceImg);
        if (isset($arrayReplaceImg[0]) && count($arrayReplaceImg[0])) {
            $arraySearch = array();
            $arrayReplace = array();
            foreach ($arrayReplaceImg[0] as $src) {
                if (!preg_match('/http/',$src)) {
                    $srcNew = str_replace(array('src="', "src='"), array('src="' . HTTP_HOST_PUBLIC, "src='" . HTTP_HOST_PUBLIC), $src);
                    $arraySearch[] = $src;
                    $srcNew = str_replace('://', '123abc', $srcNew);
                    $srcNew = str_replace('//', '/', $srcNew);
                    $srcNew = str_replace('123abc', '://', $srcNew);
                    $arrayReplace[] = $srcNew;
                }
            }
            $content = str_replace($arraySearch, $arrayReplace, $content);
        }
        return $content;
    }

    public static function striUnicodeAlias($str, $character) {
        $str = trim($str);
        $str = self::stripUnicode($str);
        $str = preg_replace("/!|\$|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|_|’|\“|\”|“|”|̉|́|–|-|\?|\|/", "", $str);
        $str = preg_replace('/(\s)+/', ' ', $str);
        $str = str_replace(" ", $character, trim($str));
        $str = preg_replace("/($character)+/", $character, $str);
        return strtolower($str);
    }

    public static function beatyKeywordSearch($keyword) {
        return preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $keyword);
    }

    public static function getBeginCharaterToUpperKey($str) {
        $item = '';
        $str = trim(preg_replace('/(\s)+/', ' ', $str));
        if ($str != "") {
            $array = explode(' ', $str);
            foreach ($array as $v) {
                $item .= strtoupper($v{0});
            }
        }
        return $item;
    }

    public static function html_substr_nice($str, $start, $length) {
        $abc = str_replace(['<br />', '<br>'], 'bbbbbbb1111', UtilityHtmlFormat::html_substr($str, $start, $length));
        $str = strip_tags($abc);
        $str = str_replace('bbbbbbb1111', '<br />', $str);
        return self::sub_string($str, $length);
    }

    /**
     * convert int to padded string
     * @param int $value
     * @param int $number
     * @param int $type
     * @return int with str_pad
     * @author HaNH<hanguyenhai@orenj.com>
     * @since version 1.0 05/10/2014
     */
    public static function strpadIntFormat($value = 0, $number = 6, $type = STR_PAD_LEFT) {
        $value = (int) $value;
        return str_pad($value, $number, '0', $type);
    }

    public static function convertStringToIp($str) {
        $str = preg_replace("/(\D)+/", "", $str);
        $count = strlen($str);
        $result = '';
        for ($i = 0; $i < $count; $i++) {
            $result .= $str{$i};
            if (($i + 1) % 3 == 0 && $i != $count - 1) {

                $result .= '.';
            }
        }
        return $result;
    }

    /**
     * PHP_EOL to p tag
     * @param string $string
     * @param boolean $line_breaks
     * @param boolean $xml
     * @return string
     */
    public static function nl2p($string, $line_breaks = true, $xml = true) {

        $string = str_replace(array('<p>', '</p>', '<br>', '<br />'), '', $string);

        // It is conceivable that people might still want single line-breaks
        // without breaking into a new paragraph.
        if ($line_breaks == true)
            return '<p>' . preg_replace(array("/([\n]{2,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", '$1<br' . ($xml == true ? ' /' : '') . '>$2'), trim($string)) . '</p>';
        else
            return '<p>' . preg_replace(
                            array("/([\n]{2,})/i", "/([\r\n]{3,})/i", "/([^>])\n([^<])/i"), array("</p>\n<p>", "</p>\n<p>", '$1<br' . ($xml == true ? ' /' : '') . '>$2'), trim($string)) . '</p>';
    }

    public static function className($table) {
        $a = explode('_', $table);
        $b = '';
        foreach ($a as $key => $value) {
            $value{0} = strtoupper($value{0});
            $b .= $value;
        }
        return $b;
    }

    public static function getFunction($contentFile, $action) {
        $action = strtolower($action);
        $action{0} = strtoupper($action{0}); // 
        $array = [];
        $result = false;
        if (preg_match("/public(\s)+function(\s)+action{$action}(\s)*\((.*)\)[^\{]*\{/", $contentFile, $array)) {
            $startLine = $array[0];
            $arrayExplode = explode($startLine, $contentFile);
            $arrayExplode = explode('}', $arrayExplode[1]);
            $result = $startLine;
            $count = 1;
            foreach ($arrayExplode as $key => $value) {
                $array = [];
                if (preg_match_all('/\{/', $value, $array)) {
                    $count += count($array[0]);
                }
                $count--;
                $result .= $value . '}';
                if (!$count)
                    break;
            }
        }
        return $result;
    }

    public static function insertStringToContentByPosition($content, $string, $position) {
        return substr($content, 0, $position) . $string . substr($content, $position, strlen($content));
    }

    /**
     * Replaces all but the last for digits with x's in the given credit card number
     * @param int|string $cc The credit card number to mask
     * @return string The masked credit card number
     */
    public static function maskCreditCard($cc) {
        // Get the cc Length
        $cc_length = strlen($cc);
        // Replace all characters of credit card except the last four and dashes
        for ($i = 0; $i < $cc_length - 4; $i++) {
            if ($cc[$i] == '-') {
                continue;
            }
            $cc[$i] = 'X';
        }
        // Return the masked Credit Card #
        return $cc;
    }

    /**
     * Add dashes to a credit card number.
     * @param int|string $cc The credit card number to format with dashes.
     * @return string The credit card with dashes.
     */
    public static function formatCreditCard($cc) {
        // Clean out extra data that might be in the cc
        $cc = str_replace(array('-', ' '), '', $cc);
        // Get the CC Length
        $cc_length = strlen($cc);
        // Initialize the new credit card to contian the last four digits
        $newCreditCard = substr($cc, -4);
        // Walk backwards through the credit card number and add a dash after every fourth digit
        for ($i = $cc_length - 5; $i >= 0; $i--) {
            // If on the fourth character add a dash
            if ((($i + 1) - $cc_length) % 4 == 0) {
                $newCreditCard = '-' . $newCreditCard;
            }
            // Add the current character to the new credit card
            $newCreditCard = $cc[$i] . $newCreditCard;
        }
        // Return the formatted credit card number
        return $newCreditCard;
    }

    public static function replaceUrl($link) {
        $link = str_replace('://', '12a#$', $link);
        $link = str_replace('//', '/', $link);
        $link = str_replace('12a#$', '://', $link);
        return $link;
    }

    public static function optimazeOutputHtml($html, $optimaze = true) {
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
                    if ($optimaze) {
                        $js = preg_replace('/\/\*([^©]+?(\*\/)+)|[^:"\']\/\/(.*)/', "", $js);
                    }
                    $htmljs .= $js . "\n";
                }
            }
            if ($optimaze) {
                $arrayReplace[] = "\r\n";
                $arraySearch[] = '';

                $arrayReplace[] = "\n";
                $arraySearch[] = '';

                $arrayReplace[] = "\t";
                $arraySearch[] = '';

                $arrayReplace[] = "\r";
                $arraySearch[] = '';
            }

            $arrayReplace[] = '</body>';
            $arraySearch[] = $htmlCss . $htmljs . '</body>';
            $html = str_replace($arrayReplace, $arraySearch, $html);

            if ($optimaze) {
                $html = preg_replace("/(\s)+/i", ' ', $html);
            }
        }
        return $html;
    }

    public function optimazeHtml($html) {
        $arrayReplace = [];
        $arraySearch = [];
        $arrayReplace[] = "\r\n";
        $arraySearch[] = '';

        $arrayReplace[] = "\n";
        $arraySearch[] = '';

        $arrayReplace[] = "\t";
        $arraySearch[] = '';

        $arrayReplace[] = "\r";
        $arraySearch[] = '';
        $html = str_replace($arrayReplace, $arraySearch, $html);
        return $html;
    }

    public static function getLinkCssByContent($content) {
        preg_match_all('/<link.*href="([^"]+)"[^>]*>/', $content, $array);
        $arrayCss = [];
        if ($array && count($array)) {
            foreach ($array[0] as $key => $html) {
                if (strpos(strtolower($html), 'stylesheet') || strpos(strtolower($html), 'text/css')) {
                    $arrayCss[] = $array[1][$key];
                }
            }
        }

        preg_match_all("/<link.*href='([^']+)'[^>]*>/", $content, $array);
        if ($array && count($array)) {
            foreach ($array[0] as $key => $html) {
                if (strpos(strtolower($html), 'stylesheet') || strpos(strtolower($html), 'text/css')) {
                    $arrayCss[] = $array[1][$key];
                }
            }
        }
        return $arrayCss;
    }

    public static function getLinkJsByContent($content) {
        preg_match_all('/<script.*src="([^"]+)"[^>]*>/', $content, $array);
        $arrayJs = [];
        if ($array && count($array)) {
            foreach ($array[0] as $key => $html) {
                $arrayJs[] = $array[1][$key];
            }
        }

        preg_match_all("/<script.*src='([^']+)'[^>]*>/", $content, $array);
        if ($array && count($array)) {
            foreach ($array[0] as $key => $html) {
                $arrayJs[] = $array[1][$key];
            }
        }
        return $arrayJs;
    }
    
    public static function getStrEndSource($str) {
        if($str != "") {
            $array = explode('/',$str);
            return $array[count($array) - 1];
        }
        return '';
    }
    
    
    
    public static function getFunctionControllerApp($contentFile, $name) {
        $name = strtolower(trim($name));
        if(!$name) return false;
        $name{0} = strtoupper($name{0}); // 
        $array = [];
        $result = false;
        if (preg_match("/controllers\.controller\(('|\"){$name}Controller[^~]+?{/", $contentFile, $array)) {
            $startLine = $array[0];
            $arrayExplode = explode($startLine, $contentFile);
            $arrayExplode = explode(']);', $arrayExplode[1]);
            $result = $startLine;
            $count = 1;
            foreach ($arrayExplode as $key => $value) {
                $array = [];
                if (preg_match_all('/\]\);/', $value, $array)) {
                    $count += count($array[0]);
                }
                $count--;
                $result .= $value . ']);';
                if (!$count)
                    break;
            }
        }
        return $result;
    }
    public static function getFunctionDirectiveApp($contentFile, $name) {
        $name = strtolower(trim($name));
        if(!$name) return false;
        $array = [];
        $result = false;
        if (preg_match("/app\.directive\('{$name}Ang[^~]+?{/", $contentFile, $array)) {
            $startLine = $array[0];
            $arrayExplode = explode($startLine, $contentFile);
            $arrayExplode = explode(']);', $arrayExplode[1]);
            $result = $startLine;
            $count = 1;
            foreach ($arrayExplode as $key => $value) {
                $array = [];
                if (preg_match_all('/\]\);/', $value, $array)) {
                    $count += count($array[0]);
                }
                $count--;
                $result .= $value . ']);';
                if (!$count)
                    break;
            }
        }
        return $result;
    }
    
    public static function getContentBody($content) {
        if(preg_match('/<body[^~]*?>/',$content,$array)) {
            $start = $array[0];
            $array = explode($start,$content);
            if(isset($array[1])) {
                $a = explode('</body>',$array[1]);
                $a = explode("<link ",$a[0]);
                return $a[0];
            }
        }
        return false;
    }
    
    public static function getStatementStore($store_name,$params) {
        $pr = [];
        foreach($params as $key => $value) {
            $pr[] = is_string($value) ? '"'.$value.'"' : $value;
        }
        return str_replace('{params}',implode(',',$pr),"CALL ".$store_name."({params});");
    }
    
    public static function replace_full_select($content) {
        preg_match_all('/(SELECT|select)([^~]+?(FROM|from))[ ]+/', $content, $matches);
        $content_new = $content;
//        if (isset($matches[2])) {
//            $length = count($matches[2]) - 1;
//            for ($key = $length; $key >= 0; $key--) {
//                $value = $matches[2][$key];
//                $a = explode($matches[0][$key], $content);
//                if ($a[1]{0} == '(') {
//                    $a[1] = preg_replace('/^\(/','',$a[1]);
//                    $str_replace = preg_replace('/(FROM|from)[^~]+/', '', $a[1]);
//                    $value = trim(preg_replace('/(FROM|from)[ ]*$/', '', $value));
//                    $array_select_attr_get = UtilityArray::strtolower(UtilityArray::getAttrSelectTrue($value));
//                    $array_sellect_attr_replace = UtilityArray::strtolower(UtilityArray::getAttrSelectReplaceTrue($str_replace));
////                    echo $value;
////                    var_dump($array_select_attr_get);
////                    echo $str_replace;
////                    var_dump($array_sellect_attr_replace);
//                    $search = $matches[0][$key];
//                    foreach ($array_select_attr_get as $vl1) {
//                        if (!in_array($vl1, $array_sellect_attr_replace)) {
////                            var_dump($vl1);
//                            $str_replace_new = str_replace(['SELECT', 'select'], 'SELECT ' . ' ' . $vl1 . ',', $str_replace);
//                            $content_new = str_replace($str_replace, $str_replace_new, $content_new);
////                        echo $content_new;die();
//                        }
//                    }
//                }
//            }
//        }
        $content_new = trim($content_new);
        if ($content_new{strlen($content_new) - 1} == ')') {
            $content_new .= ' as a';
        }
        return $content_new;
    }

    public static function get_statement_select_from_procedure($content, $params) {
        $params_new = [];
        foreach ($params as $key => $value) {
            $params_new[str_replace(':', '', $key)] = is_string($value) ? "'" . $value . "'" : $value;
        }
        UtilityArray::sortKeysDescGOOD($params_new);
        $params = $params_new;
        $array_select = [];
        $content = preg_replace('/-- .*/', '', $content);
        $content = preg_replace('/SET[ ]+[a-zA-Z0-9_]+[ ]+=[^~]+?(;)/', '', $content);
        $content = preg_replace('/(INSERT[ ]+INTO|insert[ ]+into)[^~]+?(;)/', '', $content);
        $content = preg_replace('/(DROP|drop)[ ]+(TEMPORARY|temporary)[^~]+?;/', '', $content);
        $content = preg_replace('/(CREATE|create)[ ]+(TEMPORARY|temporary)[^~]+?;/', '', $content);
        $content = preg_replace('/(SET|set)[ ]+\@[a-zA-Z0-9_]+[ ]*\:\=[^~]+?;/', '', $content);
        $content = preg_replace('/(SET|set)[ ]+\@[a-zA-Z0-9_]+[ ]*\=[^~]+?;/', '', $content);
        $content = preg_replace('/(SET|set)[ ]+[a-zA-Z0-9_]+[ ]*\=[^~]+?;/', '', $content);
        $content = preg_replace('/(DECLARE|declare)[ ]+[a-zA-Z0-9_]+[^~]+?;/', '', $content);
        $content = preg_replace('/(SELECT|select)[ ]+([a-zA-Z0-9_]+\.|)[a-zA-Z0-9 \(\),]+[^~]+?(INTO|into)[^~]+?;/', '', $content);
        $content = preg_replace('/(PREPARE|prepare)[ ]+(STMT|stmt)[^~]+?;/', '', $content);
        $content = preg_replace('/(EXECUTE|excute)[ ]+(STMT|stmt)[^~]+?;/', '', $content);
        $content = preg_replace('/(DEALLOCATE|deallocate)/', '', $content);
        $content = preg_match_all('/(SELECT|select)[^~]+?;/', $content, $matches);


        $params_search = array_keys($params_new);
        $params_replace = array_values($params_new);

        foreach ($matches[0] as $value) {
            $array_select[] = str_replace($params_search, $params_replace, $value);
//            echo $value."\n";
        }
        return $array_select;
    }

    public static function get_statement_insert_from_procedure($content, $params) {
        $params_new = [];
        foreach ($params as $key => $value) {
            $params_new[str_replace(':', '', $key)] = is_string($value) ? "'" . $value . "'" : $value;
        }
        UtilityArray::sortKeysDescGOOD($params_new);
        $params = $params_new;
        
        $array_replace = [];
        preg_match_all('/(INSERT[ ]+INTO|insert[ ]+into)[ ]+([a-zA-Z0-9_]+)[ ]*\(([^\)]+)\)(\r\n|\r|\n| )+?([^;]+;)/', $content, $matches);
        foreach($matches[2] as $key => $vl) {
            $array_replace[$vl] = '('.preg_replace('/;$/','',trim($matches[5][$key])).')';
        }
        UtilityArray::sortKeysDescGOOD($array_replace);
        return $array_replace;
    }

    public static function replaceApacheKylin($content) {
        
    }
    
    public static function encodeUrl($url) {
        return str_replace(' ','%20',$url);
    }
    
    public static function replaceDiffrentNumber($str) {
        return preg_replace('/[^0-9]+/','',$str);
    }

}
