<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "advertise".
 *
 * @property integer $id
 * @property string $category_name
 * @property string $name
 * @property string $description
 * @property string $image
 * @property integer $status
 * @property integer $odr
 * @property integer $type
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property string $hyperlink
 */
class Advertise extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advertise';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['status', 'odr', 'type', 'created_time', 'created_by', 'modified_time', 'modified_by'], 'integer'],
            [['category_name', 'name', 'image', 'hyperlink'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_name' => 'Category Name',
            'name' => 'Name',
            'description' => 'Description',
            'image' => 'Image',
            'status' => 'Status',
            'odr' => 'Odr',
            'type' => 'Type',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'hyperlink' => 'Hyperlink',
        ];
    }
}
