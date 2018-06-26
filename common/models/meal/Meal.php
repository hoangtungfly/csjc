<?php

namespace common\models\meal;

use Yii;

/**
 * This is the model class for table "meal".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $description
 * @property string $day_from
 * @property string $day_to
 * @property string $time_from
 * @property string $time_to
 * @property integer $time_distance
 * @property integer $created_by
 * @property integer $created_time
 * @property integer $modified_by
 * @property integer $modified_time
 *
 * @property TblBooking[] $tblBookings
 */
class Meal extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'meal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'time_from', 'time_to'], 'required'],
            [['time_from', 'time_to'], 'safe'],
            [['time_distance', 'created_by', 'created_time', 'modified_by', 'modified_time'], 'integer'],
            [['title'], 'string', 'max' => 30],
            [['content', 'description'], 'string', 'max' => 255],
            [['day_from', 'day_to'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'description' => 'Description',
            'day_from' => 'Day From',
            'day_to' => 'Day To',
            'time_from' => 'Time From',
            'time_to' => 'Time To',
            'time_distance' => 'Time Distance',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'modified_by' => 'Modified By',
            'modified_time' => 'Modified Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblBookings()
    {
        return $this->hasMany(TblBooking::className(), ['meal_id' => 'id']);
    }
}
