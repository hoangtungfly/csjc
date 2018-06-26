<?php
namespace common\core\model;

use common\utilities\UtilityUrl;

class LinkPagerAngular {
    public static function run($total,$limit = 20, $link, $range = 2, $attr = 'page') {
        $get = r()->get();
        $page = 1;
        if(isset($get[$attr])) {
            $page = $get[$attr];
        }
        if($page < 1)
            $page = 1;
        
        $totalPage = ceil($total / $limit);
        
        $range_start_other = 0;
        
        $start = $page - $range;
        if($start < 1) {
            $range_start_other = 1 - $start;
            $start = 1;
        }
        
        $end = $page + $range + $range_start_other;
        if($end > $totalPage) {
            $range_end_other = $end - $totalPage;
            $end = $totalPage;
            $start -= $range_end_other;
            if($start < 1) $start = 1;
        }
        
        $result = [];
        if($start > 1) {
            unset($get[$attr]);
            $result[] = [
                'id'    => 1,
                'name'  => '<<',
                'link_main'  => UtilityUrl::createUrl($link,$get),
            ];
        }
        
        for($i = $start; $i <= $end; $i++) {
            $get[$attr] = $i;
            if($i == 1)
                unset($get[$attr]);
            $item = [
                'id'    => $i,
                'name'  => $i,
                'link_main'  => UtilityUrl::createUrl($link,$get),
            ];
            if($i == $page)
                $item['active'] = 1;
            $result[] = $item;
        }
        if($end < $totalPage) {
            $get[$attr] = $totalPage;
            $result[] = [
                'id'    => $totalPage,
                'name'  => '>>',
                'link_main'  => UtilityUrl::createUrl($link,$get),
            ];
        }
        return $result;
        
    }
}