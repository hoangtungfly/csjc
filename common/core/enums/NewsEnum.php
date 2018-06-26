<?php
namespace common\core\enums;
use common\core\enums\base\GlobalEnumBase;

class NewsEnum extends GlobalEnumBase{
    const SELECT = 'id,name,alias,description,image,news.category_id,news.created_time,category_id1,count';
}
