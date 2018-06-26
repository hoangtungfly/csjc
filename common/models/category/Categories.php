<?php

namespace common\models\category;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property string $id
 * @property string $name
 * @property string $image
 * @property integer $status
 * @property integer $order
 * @property string $created_time
 * @property string $created_by
 * @property string $modified_time
 * @property string $modified_by
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property string $tags
 * @property integer $type
 * @property string $description
 * @property string $content
 * @property string $hyperlink
 * @property string $mainalias
 * @property integer $mainmenu
 * @property integer $mainmenu_odr
 * @property integer $footermenu
 * @property integer $footermenu_odr
 * @property integer $leftmenu
 * @property integer $leftmenu_odr
 * @property integer $rightmenu
 * @property integer $rightmenu_odr
 * @property integer $pid
 * @property string $category_id
 * @property string $cron_id
 * @property string $lang
 * @property string $alias
 * @property integer $home
 * @property integer $home_odr
 * @property integer $show_type
 * @property integer $slider
 * @property integer $footerlistmenu
 * @property integer $footerlistmenu_odr
 * @property string $color
 * @property integer $count
 * @property string $name_old
 * @property integer $xuhuong
 */
class Categories extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['status', 'order', 'created_time', 'created_by', 'modified_time', 'modified_by', 'type', 'mainmenu', 'mainmenu_odr', 'footermenu', 'footermenu_odr', 'leftmenu', 'leftmenu_odr', 'rightmenu', 'rightmenu_odr', 'pid', 'home', 'home_odr', 'show_type', 'slider', 'footerlistmenu', 'footerlistmenu_odr', 'count', 'xuhuong'], 'integer'],
            [['description', 'content'], 'string'],
            [['name'], 'string', 'max' => 500],
            [['image', 'meta_title', 'meta_keyword', 'meta_description', 'tags', 'hyperlink', 'mainalias', 'category_id', 'cron_id', 'alias', 'name_old'], 'string', 'max' => 255],
            [['lang', 'color'], 'string', 'max' => 20]
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
            'image' => 'Image',
            'status' => 'Status',
            'order' => 'Order',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'meta_title' => 'Meta Title',
            'meta_keyword' => 'Meta Keyword',
            'meta_description' => 'Meta Description',
            'tags' => 'Tags',
            'type' => 'Type',
            'description' => 'Description',
            'content' => 'Content',
            'hyperlink' => 'Hyperlink',
            'mainalias' => 'Mainalias',
            'mainmenu' => 'Mainmenu',
            'mainmenu_odr' => 'Mainmenu Odr',
            'footermenu' => 'Footermenu',
            'footermenu_odr' => 'Footermenu Odr',
            'leftmenu' => 'Leftmenu',
            'leftmenu_odr' => 'Leftmenu Odr',
            'rightmenu' => 'Rightmenu',
            'rightmenu_odr' => 'Rightmenu Odr',
            'pid' => 'Pid',
            'category_id' => 'Category ID',
            'cron_id' => 'Cron ID',
            'lang' => 'Lang',
            'alias' => 'Alias',
            'home' => 'Home',
            'home_odr' => 'Home Odr',
            'show_type' => 'Show Type',
            'slider' => 'Slider',
            'footerlistmenu' => 'Footerlistmenu',
            'footerlistmenu_odr' => 'Footerlistmenu Odr',
            'color' => 'Color',
            'count' => 'Count',
            'name_old' => 'Name Old',
            'xuhuong' => 'Xuhuong',
        ];
    }
}
