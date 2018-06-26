<?php

namespace common\models\store;

use Yii;

class KylinAdwordParentTypeSearch extends KylinAdwordParentType {
    public static function getDb() {
        return app()->get('db2');
    }
}