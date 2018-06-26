<?php

namespace common\models\store;

use Yii;

/**
 * This is the model class for table "kylin_adword_type".
 *
 * @property integer $id
 * @property string $v_fact_table
 * @property string $v_fact_table_click
 * @property string $v_fact_table_goal
 * @property string $v_fact_table_conversion
 * @property string $v_dimensions_in
 * @property string $v_lookup_inner_table
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property integer $status
 * @property integer $search_engine
 */
class KylinAdwordTypeSearch extends KylinAdwordType {
    public static function getDb() {
        return app()->get('db2');
    }
    
}
