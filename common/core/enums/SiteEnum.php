<?php
namespace common\core\enums;
use common\core\enums\base\GlobalEnumBase;

class SiteEnum extends GlobalEnumBase{
    const TYPE_ACTION_CREATE = 'create';
    const TYPE_ACTION_UPDATE = 'update';
    const TYPE_ACTION_DELETE = 'delete';
    
    const ERROR_CREATE_EXIST = 0;
    const ERROR_CREATE_NOT_COMPANY = -1;
    
    const ERROR_UPDATE_EXIST = 0;
    const ERROR_UPDATE_NOT_EXIST = -1;
}
