<?php

namespace common\models\system;

use common\core\dbConnection\GlobalActiveRecord;
use common\core\enums\SystemTokenEnum;
/**
 * This is the model class for table "sys_token".
 *
 * @property string $token_key
 * @property string $data
 * @property integer $object_type
 * @property integer $object_id
 * @property integer $expiration
 * @property integer $created_time
 * @property integer $status
 */
class SystemToken extends GlobalActiveRecord {
    /* type token */

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'system_token';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['token_key', 'object_type', 'object_id', 'expiration'], 'required'],
            [['object_type', 'object_id', 'expiration', 'created_time', 'status'], 'integer'],
            [['token_key'], 'string', 'max' => 100],
            [['data'], 'string', 'max' => 255],
            [['object_type', 'object_id'], 'unique', 'targetAttribute' => ['object_type', 'object_id'], 'message' => 'The combination of Object Type and Object ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'token_key' => 'Token Key',
            'data' => 'Data',
            'object_type' => 'Object Type',
            'object_id' => 'Object ID',
            'expiration' => 'Expiration',
            'created_time' => 'Created Time',
            'status' => 'Status',
        ];
    }


}
