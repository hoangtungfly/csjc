<?php

namespace common\models\product;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $content
 * @property string $category_id
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $created_time
 * @property integer $modified_time
 * @property double $price
 * @property double $price_old
 * @property integer $status
 * @property integer $hot
 * @property string $image
 * @property string $images
 * @property string $alias
 * @property string $tags
 * @property integer $count
 * @property string $cron_id
 * @property integer $category_id1
 * @property integer $category_id2
 * @property integer $category_id3
 * @property integer $category_id4
 * @property string $code
 * @property string $size
 * @property integer $home
 * @property double $price_org
 * @property string $mainalias
 * @property string $image2
 * @property string $color
 * @property integer $manufacturer
 * @property integer $status_product
 * @property integer $new
 */
class Product extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id'], 'required'],
            [['description', 'content', 'images'], 'string'],
            [['created_by', 'modified_by', 'created_time', 'modified_time', 'status', 'hot', 'count', 'category_id1', 'category_id2', 'category_id3', 'category_id4', 'home', 'manufacturer', 'status_product', 'new'], 'integer'],
            [['price', 'price_old', 'price_org'], 'number'],
            [['name', 'category_id', 'meta_title', 'meta_keyword', 'meta_description', 'image', 'alias', 'tags', 'cron_id', 'size', 'mainalias', 'image2', 'color'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 20]
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
            'description' => 'Description',
            'content' => 'Content',
            'category_id' => 'Category ID',
            'meta_title' => 'Meta Title',
            'meta_keyword' => 'Meta Keyword',
            'meta_description' => 'Meta Description',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'price' => 'Price',
            'price_old' => 'Price Old',
            'status' => 'Status',
            'hot' => 'Hot',
            'image' => 'Image',
            'images' => 'Images',
            'alias' => 'Alias',
            'tags' => 'Tags',
            'count' => 'Count',
            'cron_id' => 'Cron ID',
            'category_id1' => 'Category Id1',
            'category_id2' => 'Category Id2',
            'category_id3' => 'Category Id3',
            'category_id4' => 'Category Id4',
            'code' => 'Code',
            'size' => 'Size',
            'home' => 'Home',
            'price_org' => 'Price Org',
            'mainalias' => 'Mainalias',
            'image2' => 'Image2',
            'color' => 'Color',
            'manufacturer' => 'Manufacturer',
            'status_product' => 'Status Product',
            'new' => 'New',
        ];
    }
}
