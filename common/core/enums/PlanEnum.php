<?php

namespace common\core\enums;

use common\core\enums\base\GlobalEnumBase;

class PlanEnum extends GlobalEnumBase {
   const DEFAULT_PLAN_1 = 2;
   const SESSION_PLAN_ID = 'session_plan_id';
   const USER_COUNT_TOTAL = 10000000;
   const FACEBOOK_COUNT_TOTAL = 10000000;
   
   
   const SEARCH_ENGINE_FACEBOOK = 1;
   
   const PLAN_BASIC = 1;
   const PLAN_ADVANCE = 2;
   const PLAN_AGENCY = 3;
   const PLAN_ENTERPRISE = 4;
   
   public static function cancelPlanLabel() {
       return [
           StatusEnum::STATUS_DEACTIVED => 'Active',
            StatusEnum::STATUS_ACTIVED => 'Cancelled',
       ];
   }
   
}