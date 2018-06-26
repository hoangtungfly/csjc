<?php

namespace common\models\system;

use Yii;

/**
 * This is the model class for table "page".
 *
 * @property integer $id
 * @property string $name
 * @property string $page_title
 * @property string $page_class
 * @property string $href
 * @property integer $order
 * @property string $description
 * @property string $image
 * @property integer $display_header
 * @property integer $display_footer
 * @property integer $created_by
 * @property integer $created_time
 * @property integer $modified_by
 * @property integer $modified_time
 */
class Page extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order', 'display_header', 'display_footer', 'created_by', 'created_time', 'modified_by', 'modified_time'], 'integer'],
            [['description'], 'string'],
            [['name', 'image'], 'string', 'max' => 255],
            [['page_title', 'page_class', 'href'], 'string', 'max' => 100]
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
            'page_title' => 'Page Title',
            'page_class' => 'Page Class',
            'href' => 'Href',
            'order' => 'Order',
            'description' => 'Description',
            'image' => 'Image',
            'display_header' => 'Display Header',
            'display_footer' => 'Display Footer',
            'created_by' => 'Created By',
            'created_time' => 'Created Time',
            'modified_by' => 'Modified By',
            'modified_time' => 'Modified Time',
        ];
    }
}
