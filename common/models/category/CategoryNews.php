<?php

namespace common\models\category;

use Yii;

/**
 * This is the model class for table "category_news".
 *
 * @property integer $category_id
 * @property integer $news_id
 * @property integer $created_by
 * @property integer $created_time
 */
class CategoryNews extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'news_id'], 'required'],
            [['category_id', 'news_id', 'created_by', 'created_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'news_id' => 'News ID',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
        ];
    }
}
