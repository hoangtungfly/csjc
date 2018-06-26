<?php

namespace common\models\location;

use Yii;

/**
 * This is the model class for table "location".
 *
 * @property integer $id
 * @property string $address
 * @property string $coordination
 * @property string $phone
 * @property string $fax
 * @property string $map_marker_title
 * @property string $map_marker_content
 * @property integer $created_by
 * @property integer $created_time
 * @property integer $modified_by
 * @property integer $modified_time
 */
class Location extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address'], 'required'],
            [['created_by', 'created_time', 'modified_by', 'modified_time'], 'integer'],
            [['address'], 'string', 'max' => 255],
            [['coordination'], 'string', 'max' => 50],
            [['phone', 'fax', 'map_marker_title', 'map_marker_content'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'coordination' => 'Coordination',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'map_marker_title' => 'Map Marker Title',
            'map_marker_content' => 'Map Marker Content',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'modified_by' => 'Modified By',
            'modified_time' => 'Modified Time',
        ];
    }
}
