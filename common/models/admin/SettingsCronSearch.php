<?php

namespace common\models\admin;

use common\core\enums\CronEnum;
use common\models\category\CategoriesSearch;
use common\models\product\ProductSearch;
use common\utilities\SimpleHtmlDom;
use common\utilities\UtilityDateTime;
use common\utilities\UtilityFile;
use common\utilities\UtilityHtmlFormat;

class SettingsCronSearch extends SettingsCron {
    public $cron_image = '';
    public function changeimage(&$value) {
        if($this->name == 'http://v.yupoo.com/') {
            $nameOld = pathinfo($value,PATHINFO_BASENAME);
            $a = explode('.',$nameOld);
            $a[0] = 'big';
            $nameNew = implode('.',$a);
            $value = str_replace($nameOld,$nameNew,$value);
        }
    }
    
    public function getValueCron($type, $object_value, $attributes) {
        if (!isset($object_value[0]))
            return false;
        $result = '';
        switch ($type) {
            case CronEnum::TYPE_IMAGE:
                $key = 0;
                do {
                    if($object_value[$key]->tag == 'meta') {
                        if(isset($object_value[$key]->attr['content'])) {
                            $value = $object_value[$key]->attr['content'];
                        } else {
                            $value = '';
                        }
                    } else {
                        if(isset($object_value[$key]->attr['data-zoom-image'])) {
                            $value = $object_value[$key]->attr['data-zoom-image'];
                        } else {
                            $value = '';
                        }
                        if(isset($object_value[$key]->attr['data-src'])) {
                            $value = $object_value[$key]->attr['data-src'];
                        } else {
                            $value = '';
                        }
                        if(!$value) {
                            if(isset($object_value[$key]->src)) {
                                $value = $object_value[$key]->src;
                            } else {
                                $value = '';
                            }
                        }
                    }
                    $key++;
                } while(preg_match('/(share-fb\.gif)|(share-gg\.gif)/',$value) && isset($object_value[$key]));
                
                if (!$value)
                    return false;
                $this->changeimage($value);
                $array = SettingsImages::updateImageByLink($value, $this->table_name, $this->name, $attributes);
                if (isset($array['name'])) {
                    $result = $array['name'];
                    $this->cron_image = $result;
                }
                break;
            case CronEnum::TYPE_IMAGES:
                $result = array();
                foreach ($object_value as $item) {
                    $value = $item->src;
                    $this->changeimage($value);
                    $array = SettingsImages::updateImageByLink($value, $this->table_name, $this->name, $attributes);
                    if (isset($array['name'])) {
                        $result[] = [
                            'name' => isset($array['name']) ? $array['name'] : '',
                            'baseUrl' => isset($array['baseUrl']) ? $array['baseUrl'] : '',
                            'id' => isset($array['id']) ? $array['id'] : '',
                            'id' => isset($array['odr']) ? $array['odr'] : '',
                        ];
                    }
                    
                }
                $result = json_encode($result);
                break;
            case CronEnum::TYPE_DATETIME_PHAPLUAT:
                $value = $object_value[0]->innertext;
                $a = explode(' - ', $value);
                $result = isset($a[1]) ? UtilityDateTime::getIntFromDate($a[1],'d/m/Y H:i') : 0;
                break;
            case CronEnum::TYPE_PRICE:
                $value = $object_value[0]->innertext;
                $result = (int)preg_replace('/[^0-9]+/','',$value);
                break;
            case CronEnum::TYPE_DATETIME_DANTRI:
                $value = $object_value[0]->innertext;
                $a = explode(' ', $value);
                $result = UtilityDateTime::getIntFromDate($a[2]);
                break;
            case CronEnum::TYPE_CONTENT:
                $result = $object_value[0]->innertext;
                switch($this->name) {
                    case 'http://www.24h.com.vn/':
                        $result = preg_replace('/<span id="shareImage-[0-9]+"[^~]+?(class="news-image")/','<img class="news-image"',$result);
                        $result = preg_replace('/<script[^~]+?(<\/script>)/','',$result);
                        break;
                    case 'http://eva.vn/':
                        $result = preg_replace('/<span id="shareImage-[0-9]+"[^~]+?(class="news-image")/','<img class="news-image"',$result);
                        $result = preg_replace('/<script[^~]+?(<\/script>)/','',$result);
                        $result = preg_replace('/<div itemprop="logo"[^~]+?(<\/div>)/','',$result);
                        $result = preg_replace('/<div itemprop="image"[^~]+?(<\/div>)/','',$result);
                        $result = preg_replace('/<div class="baiviet-bailienquan pink-box-bg-light[^~]+?(<\/div>)/','',$result);
                        $result = preg_replace('/<div class="bailienquan-trangtrong[^~]+?(<\/div>)/','',$result);
                        $result = preg_replace('/<div class="baiviet-bailienquan baiviet-bailienquan-bottom[^~]+?(<\/div>)/','',$result);                        break;
                    case 'http://thethaovanhoa.vn/':
                        $result = preg_replace('/<ul class="boxrelation">[^~]+?(<\/ul>)/','',$result);
                        $result = preg_replace('/<p>\(Thethaovanhoa\.vn\)[^~]+?(<\/p>)/','',$result);
                        break;
                    case 'http://phapluatxahoi.vn/':
                        $result = preg_replace('/<p class="news-content-excerpt">[^~]+?(<\/p>)/','',$result);
                        break;
                    case 'http://baodatviet.vn/':
                        $result = preg_replace('/<div class="bar-left_th">[^~]+?(<\/div>)/','',$result);
                        $result = preg_replace('/<h1 class="title">[^~]+?(<\/h1>)/','',$result);
                        $result = preg_replace('/<h2 class="lead">[^~]+?(<\/h2>)/','',$result);
                        $result = preg_replace('/<ul class="ul_relate">[^~]+?(<\/ul>)/','',$result);
                        break;
                    case 'http://www.nhandan.com.vn/':
                        $result = preg_replace('/<tr><td><div class="fontM ndtitle"><h3>(.*)<\/h3><\/div><\/td><\/tr>/','',$result);
                        $result = preg_replace('/<tr><td><div class="icon_date_top"[^~]+?(<\/tr>)/','',$result);
                        break;
                    case 'http://nguyentandung.org/':
                        $result = preg_replace("/<p>\(<a class='link_cat'[^~]+?(<\/p>)/",'',$result);
                        $result = preg_replace('/<p><span id="more-[^~]+?(<\/p>)/','',$result);
                        break;
                    case 'http://www.suckhoegiadinh.com.vn/':
                        $result = preg_replace("/<\!--Lead-->[^~]+?(<\!-- End Lead-->)/",'',$result);
                        $result = preg_replace("/<\!---Related-->[^~]+?(<\!---End related--->)/",'',$result);
                        $result = preg_replace("/<div style=\"text-align:center\">[^~]+?(<\/div>)/",'',$result);
                        $result = preg_replace("/<\!--Begin SocialButton--->[^~]+?(<\!--End SocialButton-->)/",'',$result);
                        $result = preg_replace("/<\!--Begin Tag-->[^~]+?(<\!--End Tag-->)/",'',$result);
                        $result = preg_replace("/<\!--Begin BinhLuan-->[^~]+?(<\!--End BinhLuan-->)/",'',$result);
                        $result = preg_replace("/<\!--Begin TIN LIÊN QUAN-->[^~]+?(<\!--End TinLienQuan-->)/",'',$result);
                        $result = preg_replace("/<\!--Begin TIN KHÁC-->[^~]+?(<\!--End TinMoi-->)/",'',$result);
                        $result = preg_replace("/<\!--Begin TinPhanTrang-->[^~]+?(<\!--End TinPhanTrang-->)/",'',$result);
                        break;
                    case 'http://www.doisongphapluat.com/':
                        $result = preg_replace("/^<p style=\"text-align: justify;\"><strong>[^~]+?(<\/strong><\/p>)/",'',$result);
                        $result = preg_replace('/<script[^~]+?(<\/script>)/','',$result);
                        $result = str_replace('<p style="text-align: justify;"><strong>Xem thêm video:</strong></p>','',$result);
                        break;
                    case 'http://www.vinasme.com.vn/':
                        $result = preg_replace("/<h2 class=\"lead\"[^~]+?(<\/h2>)/",'',$result);
                        $result = preg_replace('/<p class="sapo"[^~]+?(<\/p>)/','',$result);
                        break;
                }
                $arraySearch = [];
                $arrayReplace = [];
                $result = str_replace('data-src=','src=',$result);
                preg_match_all('/src="[^"]+"|src=\'[^\']+\'/', $result, $matches);
                if (isset($matches[0])) {
                    foreach ($matches[0] as $value) {
                        $value1 = preg_replace('/src=|"|\'/', "", $value);
                        if($value1 != "") {
                            $array = SettingsImages::updateImageByLink($value1, $this->table_name, $this->name);
                            if (isset($array['name'])) {
                                $arraySearch[] = $value1;
                                $arrayReplace[] = $this->modelCron->getimage([], $array['name']);
                            }
                        }
                    }
                }
                $result = str_replace($arraySearch, $arrayReplace, $result);
                preg_match('/src="[^"]+"|src=\'[^\']+\'/', $result, $matches);
                if(!$this->cron_image && isset($matches[0])) {
                    $link = preg_replace('/src=|"|\'/', "", $matches[0]);
                    $array = SettingsImages::updateImageByLink($link, $this->table_name, $this->name);
                    if (isset($array['name'])) {
                        $this->cron_image = $array['name'];
                    }
                }
                break;
            case CronEnum::TYPE_TAG:
                $result = array();
                foreach ($object_value as $item) {
                    $value = isset($item->attr['title']) && $item->attr['title'] ? $item->attr['title'] : strip_tags($item->innertext);
                    if($value != "Xem thêm chủ đề:") {
                        $result[] = html_entity_decode(trim(strip_tags($value)));
                    }
                }
                $result = implode(',',$result);
                break;
            case CronEnum::TYPE_TEXT:
            default :
                $value = ($object_value[0]->tag == 'meta') ? (isset($object_value[0]->attr['content']) ? $object_value[0]->attr['content'] : '') : (isset($object_value[0]->innertext) ? $object_value[0]->innertext : '');
                $result = html_entity_decode(trim(strip_tags($value)));
                if($this->name == 'http://www.24h.com.vn/') {
                    $result = preg_replace('/^(Video: )/','',$result);
                }
                break;
        }
        return $result;
    }

    public function log($text, $type = false) {
        if ($type) {
            $text = '<span style="color:red;">' . $text . '</span>';
        }
        $this->content_log .= $text . "\r\n";
        UtilityFile::fileputcontents('log/log_cron.txt', $this->content_log);
    }
    
    public $category_name = '';
    public $category_name_old = '';
    public $cout_product = 0;

    public function cronAll() {
        $link_cron_out = (array) json_decode($this->link_cron_out);
        set_time_limit(1000000);
        foreach ($link_cron_out as $key => $item) {
            $item = (array) $item;
            $linkCron = $item['link'];
            if ($linkCron != "") {
                $breakcrumb = [];
                if ($item['category_id']) {
                    $listBreakcrumb = CategoriesSearch::breakcrumb($item['category_id']);
                    if ($listBreakcrumb) {
                        foreach ($listBreakcrumb as $key => $item1) {
                            $breakcrumb[] = $item1->id;
                            $this->category_name = $item1->name;
                            $this->category_name_old = $item1->name_old;
                        }
                    } else {
                        continue;
                    }
                }
                if (count($breakcrumb))
                    $breakcrumb = implode(',', $breakcrumb);
                $this->page_format = str_replace('?', "\\?",$this->page_format);
                if ($this->page_format != "" && preg_match("~{$this->page_format}~", $linkCron, $matches)) {
                    $search = $matches[0];
                    $page = $matches[1];
                    for ($i = $page; $i >= 1; $i--) {
                        $replace = str_replace($page, $i, $search);
                        $this->cronAllProcess(str_replace($search, $replace, $linkCron), $breakcrumb);
                    }
                } else {
                    $this->cronAllProcess($linkCron, $breakcrumb);
                }
            }
        }
        $this->save(false);
    }

    public function cronAllProcess($linkCron, $breakcrumb) {
        $link = APPLICATION_PATH . '/application/dienlanh/views/temp/' . str_replace($this->name,'',$linkCron).'.html';
        if(is_file($link)) {
            $object_content_file = SimpleHtmlDom::str_get_html(file_get_contents($link));
        } else {
            $object_content_file = SimpleHtmlDom::file_get_html($linkCron);
        }
        if ($object_content_file) {
            $list_object_tag_out = $object_content_file->find($this->tag_out);
            if (is_array($list_object_tag_out) && count($list_object_tag_out)) {
                $count = count($list_object_tag_out);
                for ($i = $count - 1; $i >= 0; $i--) {
                    $object_tag_out = $list_object_tag_out[$i];
                    $link = $object_tag_out->attr['href'];
                    if (!preg_match('/^http/', $link)) {
                        $link = UtilityHtmlFormat::replaceUrl($this->name . $link);
                    }
                    $this->cronone($link, $breakcrumb);
                }
            }
        }
    }

    public function getAttrIn() {
        if (!$this->attr_in_cron) {
            $attr_in = (array) json_decode($this->attr_in);
            foreach ($attr_in as $key => $value) {
                $attr_in[$key] = (array) $value;
            }
            $this->attr_in_cron = $attr_in;
        }
    }

    public $modelCron;
    public $attr_in_cron;
    
    public function changename(&$attributes, $model = false) {
        if($this->name == 'http://v.yupoo.com/') {
            $this->cout_product = ProductSearch::find()->count() + 1;
            $code = 'h';
            if(strlen($this->cout_product) < 5) {
                $code .= str_repeat("0", 5 - strlen($this->cout_product)). $this->cout_product;
            }
            $attributes['code'] = $code;
            $attributes['name'] = 'Giày da nam ' . $this->category_name_old.' - ' . $attributes['code'];
            $attributes['changeimage'] = true;
            if($model) {
                $model->setAttributes($attributes);
                $model->save(false);
            }
        }
    }

    public function cronone($linkCron, $breakcrumb) {
        $cron_id = $linkCron;
        $class = $this->class_name;
        if (!$this->modelCron) {
            $this->modelCron = new $class();
        }
        $this->cron_image = '';
        $cr_id = pathinfo($cron_id,PATHINFO_BASENAME);
        $model = $class::find()->where(['cron_id' => $cron_id])->one();
        if (!$model) {
            $object_content_file = SimpleHtmlDom::file_get_html($linkCron);
            if ($object_content_file) {
                $model = new $class();
                $attributes = ['changeimage' => true];
                if ($model->hasAttribute('cron_id')) {
                    $attributes['cron_id'] = $cron_id;
                }
                if ($model->hasAttribute('category_id')) {
                    $attributes['category_id'] = $breakcrumb;
                }
                $this->changename($attributes, $model);
                $this->getAttrIn();
                foreach ($this->attr_in_cron as $item) {
                    if (isset($item['tag']) && $item['tag'] != "" && isset($item['attribute']) && $item['attribute'] != "") {
                        $attribute = preg_replace('/`[a-z0-9A-Z-_]+`\./', '', $item['attribute']);
                        $type = isset($item['type']) ? $item['type'] : '';
                        $tag = trim($item['tag']);
                        $object_content_tag = $this->findTag($object_content_file, $tag);
                        if(!$object_content_tag && $type == CronEnum::TYPE_CONTENT) {
                            return false;
                        }
                        $attributes[$attribute] = $this->getValueCron($type, $object_content_tag, $attributes);
                    }
                }
                if(isset($attributes['image']) && !$attributes['image'] && $this->cron_image) {
                    $attributes['image'] = $this->cron_image;
                    $attributes['status'] = 1;
                }
                $model->setAttributes($attributes);
                $this->conditionsave($model);
                $model->save(false);
            }
        }
    }
    
    public static function download($model) {
        $linkCron = $model->cron_id;
        if(!$linkCron) return false;
        $a = explode("/",$linkCron);
        if(count($a) < 3) {
            return false;
        }
        $link_host = $a[0].'/'.$a[1].'/'.$a[2].'/';
        $modelCron = self::findOne(['name' => $link_host]);
        if(!$modelCron) {
            return false;
        }
        $modelCron->cron_image = '';
        $object_content_file = SimpleHtmlDom::file_get_html($linkCron);
        if ($object_content_file) {
            $attributes = [];
            $modelCron->getAttrIn();
            foreach ($modelCron->attr_in_cron as $item) {
                if (isset($item['tag']) && $item['tag'] != "" && isset($item['attribute']) && $item['attribute'] != "") {
                    $attribute = preg_replace('/`[a-z0-9A-Z-_]+`\./', '', $item['attribute']);
                    $type = isset($item['type']) ? $item['type'] : '';
                    $tag = trim($item['tag']);
                    $object_content_tag = $modelCron->findTag($object_content_file, $tag);
                    if (!$object_content_tag && $type == CronEnum::TYPE_CONTENT) {
                        $attributes['status'] = 0;
                    }
                    $attributes[$attribute] = $modelCron->getValueCron($type, $object_content_tag, $attributes);
                    if ($type == CronEnum::TYPE_IMAGE && $attributes[$attribute] == '') {
                        $attributes['status'] = 0;
                    }
                }
            }
            if(isset($attributes['image']) && !$attributes['image'] && $modelCron->cron_image) {
                $attributes['image'] = $modelCron->cron_image;
                $attributes['status'] = 1;
            }
            $model->setAttributes($attributes);
            $model->save(false);
        }
    }

    public function findTag($object_content_file, $tag) {
        $list = array();
        $result = array();
        $valueFromTo = false;
        if (preg_match('/\|/', $tag)) {
            $a = explode('|', $tag);
            $tag = $a[0];
            $valueFromTo = $a[1];
        }
        if (preg_match('/\[/', $tag)) {
            $array = explode('[', $tag);
            $tag0 = $array[0];

            $arrayTag1 = explode('=', $array[1]);
            $tag1 = $arrayTag1[0];

            $arrayTag2 = explode('"', $arrayTag1[1]);
            $tag2 = $arrayTag2[1];

            $listObject = $object_content_file->find($tag0);
            foreach ($listObject as $object) {
                if (isset($object->attr[$tag1]) && $object->attr[$tag1] != "" && $object->attr[$tag1] == $tag2) {
                    $list[] = $object;
                }
            }
        } else {
            $list = $object_content_file->find($tag);
        }
        $result = $list;
        if ($valueFromTo) {
            $a = explode('->', $valueFromTo);
            $from = (int) $a[0];
            $to = isset($a[1]) ? $a[1] : false;
            if ($list) {
                if (!$to) {
                    if (isset($list[$from])) {
                        $result[0] = $list[$from];
                    } else {
                        $result = false;
                    }
                } else {
                    foreach ($list as $key => $item) {
                        if ($key >= $from && $key <= $to) {
                            $result[] = $item;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function cronMenu() {
        $link_cron_out = (array) json_decode($this->link_cron_out);
        $class = $this->class_name;
        foreach ($link_cron_out as $key => $item) {
            $item = (array) $item;
            $pid = (int) $item['category_id'];
            set_time_limit(10000);
            $object_content_file = SimpleHtmlDom::str_get_html($item['html']);
            if ($object_content_file) {
                $object_tag_parent_out = $object_content_file->find($this->tag_parent_out);
                if ($object_tag_parent_out) {
                    foreach ($object_tag_parent_out as $item) {
                        $model = new $class();
                        $itemArray = $item->find('a');
                        if (is_array($itemArray) && count($itemArray)) {
                            $a = $itemArray[0];
                            $cron_id = trim($a->href);
                            $name = isset($a->attr['title']) ? trim($a->attr['title']) : trim(strip_tags($a->innertext));
                            $name = html_entity_decode($name);
                            if ($cron_id != "" && $cron_id != "/" && $name != "") {
                                if (!($model = $class::findOne(['name' => $name]))) {
                                    $model = new $class();
                                    $model->name = $name;
                                    $model->cron_id = $cron_id;
                                    $model->pid = $pid;
                                    $this->conditionsave($model);
                                    $model->save();
                                }
                                $this->cronMenuChild($item, $model->id, $class);
                            }
                        }
                    }
                }
            }
        }
        $this->save();
    }

    public function cronMenuChild($object, $pid, $class) {
        if ($this->tag_out != "") {
            $object_tag_parent_out = $object->find($this->tag_out);
            if ($object_tag_parent_out) {
                foreach ($object_tag_parent_out as $item) {
                    $model = new $class();
                    if(isset($item->attr['href']) && $item->attr['href']) {
                        $a = $item;
                        $cron_id = trim($a->href);
                        $name = isset($a->attr['title']) ? trim($a->attr['title']) : trim(strip_tags($a->innertext));
                        $name = html_entity_decode($name);
                        if ($cron_id != "" && $cron_id != "/" && $name != "") {
                            if (!($model = $class::findOne(['name' => $name]))) {
                                $model = new $class();
                                $model->name = $name;
                                $model->cron_id = $cron_id;
                                $model->pid = $pid;
                                $this->conditionsave($model);
                                $model->save();
                            }
                        }
                    } else {
                        $itemArray = $item->find('a');
                        if (is_array($itemArray) && count($itemArray)) {
                            $a = $itemArray[0];
                            $cron_id = trim($a->href);
                            $name = isset($a->attr['title']) ? trim($a->attr['title']) : trim(strip_tags($a->innertext));
                            $name = html_entity_decode($name);
                            if ($cron_id != "" && $cron_id != "/" && $name != "") {
                                if (!($model = $class::findOne(['name' => $name]))) {
                                    $model = new $class();
                                    $model->name = $name;
                                    $model->cron_id = $cron_id;
                                    $model->pid = $pid;
                                    $this->conditionsave($model);
                                    $model->save();
                                }
                                $this->cronMenuChild($item, $model->id, $class);
                            }
                        }
                    }
                }
            }
        }
    }
    
    public $condition_save_cron;

    public function conditionsave($model) {
        if (!$this->condition_save_cron) {
            $this->condition_save_cron = (array) json_decode($this->condition_save);
        }
        if (count($this->condition_save_cron)) {
            foreach ($this->condition_save_cron as $key => $value) {
                $model->$key = $value;
            }
        }
    }
    

}
