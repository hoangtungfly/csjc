<?php

namespace common\models\store;

use Yii;

class StoreToApacheKylinSearch extends StoreToApacheKylin {
    public static function getDb() {
        return app()->get('db2');
    }
}
