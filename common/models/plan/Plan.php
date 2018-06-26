<?php

namespace common\models\plan;

use Yii;

/**
 * This is the model class for table "plan".
 *
 * @property integer $id
 * @property string $name
 * @property double $price
 * @property integer $user_count
 * @property integer $facebook_count
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property integer $status
 * @property string $content
 * @property integer $odr
 * @property integer $isonlinesupport
 * @property integer $isphonesupport
 * @property integer $isdedicated
 * @property string $braintree_plan
 */
class Plan extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['price'], 'number'],
            [['user_count', 'facebook_count', 'created_time', 'created_by', 'modified_time', 'modified_by', 'status', 'odr', 'isonlinesupport', 'isphonesupport', 'isdedicated'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['braintree_plan'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'user_count' => 'User Count',
            'facebook_count' => 'Facebook Count',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'status' => 'Status',
            'content' => 'Content',
            'odr' => 'Odr',
            'isonlinesupport' => 'Isonlinesupport',
            'isphonesupport' => 'Isphonesupport',
            'isdedicated' => 'Isdedicated',
            'braintree_plan' => 'Braintree Plan',
        ];
    }
}
