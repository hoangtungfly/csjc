<?php

namespace common\models\settings;

use common\utilities\UtilityHtmlFormat;
use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property integer $count
 * @property integer $created_time
 * @property integer $created_by
 */
class TagsSearch extends Tags {

    public static function updateTags() {
        $tags = app()->db->createCommand('select group_concat(tags) as tags from news')->queryScalar();
        $arrayTags = explode(',', $tags);
        $arrayTags = array_flip($arrayTags);
        $arrayTagNew = [];
        foreach ($arrayTags as $tag => $key) {
            $tag = trim($tag);
            if ($tag != "") {
                $arrayTagNew[] = [$tag, UtilityHtmlFormat::stripUnicode($tag), time(), !user()->isGuest ? user()->id : 0];
            }
        }
        if (count($arrayTagNew)) {
            app()->db->createCommand()->batchInsert('tags', ['name', 'alias', 'created_time', 'created_by'], $arrayTagNew)->execute();
        }
    }
    
    public static function getListAll($limit) {
        return self::getArrayByObject(self::find()->limit($limit)->orderBy('id desc')->asArray()->all());
    }

}
