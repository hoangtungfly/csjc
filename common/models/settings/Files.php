<?php

namespace common\models\settings;

use common\core\dbConnection\GlobalActiveRecord;
use common\lib\UploadLib;
use Yii;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string $name
 * @property string $link
 * @property string $baseUrl
 * @property string $table_name
 * @property integer $did
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 */
class Files extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['did', 'created_time', 'modified_time', 'created_by', 'modified_by'], 'integer'],
            [['name', 'link', 'baseUrl', 'table_name'], 'string', 'max' => 255]
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
            'link' => 'Link',
            'baseUrl' => 'Base Url',
            'did' => 'Did',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'table_name'    => 'Table name',
        ];
    }
    
    public static function upload($file, $tmp, $id = 0) {
        $up = new UploadLib($file);
        $up->setPath(array($tmp));
        $up->uploadFile();
        $response = $up->getResponse(true);
        //Check upload data
        if ($response && $up->getStatus() == '200') {
            $images = new Files();
            $images->did = (int)$id;
            $images->table_name = $tmp;
            $images->baseUrl = $response['baseUrl'];
            $images->name = $response['name'];
            $images->link = $response['baseUrl'] . $response['name'];
            $images->save(false);
            $attributes = $images->attributes;
            $attributes['code'] = 200;
            return $attributes;
        }
    }
    
    public static function deleteFileById($deleteIdStr) {
        if($deleteIdStr != "") {
            $arrayid = explode(',',$deleteIdStr);
            $result = array();
            foreach($arrayid as $key => $value) {
                $value = (int)$value;
                if($value) {
                    $result[] = $value;
                }
            }
            self::deleteAll(['id' => $result]);
        }
    }
    
    
    public static function deleteFileByName($deleteNameStr) {
        if(trim($deleteNameStr) != "") {
            $arrayid = explode(',',trim($deleteNameStr));
            $result = array();
            foreach($arrayid as $key => $value) {
                $result[] = $value;
            }
            self::deleteAll(['link' => $result]);
        }
    }
    /**
     * Add image by id
     */
    public static function addFileById($imageid,$model) {
        $imageid = trim($imageid);
        if($imageid != "") {
            $array = explode(',',$imageid);
            $primaryKey = $model->getKey();
            $did = $model->$primaryKey;
            foreach($array as $key => $id) {
                $id = (int)$id;
                if($id) {
                    $modelImage = self::findOne($id);
                    if($modelImage) {
                        $modelImage->did = $did;
                        $modelImage->save(false);
                    }
                }
            }
            $model->save(false);
        }
    }
}
