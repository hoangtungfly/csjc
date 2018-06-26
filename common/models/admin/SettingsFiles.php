<?php

namespace common\models\admin;

use common\core\dbConnection\GlobalActiveRecord;
use common\lib\UploadLib;
use common\utilities\UtilityHtmlFormat;

/**
 * This is the model class for table "settings_files".
 *
 * @property integer $id
 * @property string $name
 * @property string $link
 * @property string $baseurl
 * @property integer $did
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property string $table_name
 */
class SettingsFiles extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['did', 'created_time', 'modified_time', 'created_by', 'modified_by'], 'integer'],
            [['name', 'link', 'baseurl', 'table_name'], 'string', 'max' => 255]
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
            'baseurl' => 'Base Url',
            'did' => 'Did',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'table_name' => 'Table Name',
        ];
    }
    
    public static function upload($file, $tmp, $id = 0) {
        $up = new UploadLib($file);
        $up->setPath(array($tmp, 'main'));
        $up->uploadFile();
        $response = $up->getResponse(true);
        //Check upload data
        if ($response && $up->getStatus() == '200') {
            $files = new SettingsFiles();
            $files->did = (int)$id;
            $files->table_name = $tmp;
            $files->baseurl = $response['baseUrl'];
            $files->name = $response['name'];
            $files->link = $response['baseUrl'] . $response['name'];
            $files->save(false);
            $attributes = $files->attributes;
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
    
    public static function updateFileByLink($link, $table_name, $host = false, $id = 0) {
        if (!preg_match('/http/', $link)) {
            $link = UtilityHtmlFormat::replaceUrl($host . $link);
        }
        $up = new UploadLib(false);
        $up->setPath(array($table_name, 'main'));
        $up->uploadFile($link);
        $response = $up->getResponse(true);
        //Check upload data
        if ($response && $up->getStatus() == '200') {
            $files = new SettingsFiles();
            $files->did = (int)$id;
            $files->table_name = $table_name;
            $files->baseurl = $response['baseurl'];
            $files->name = $response['name'];
            $files->link = $response['baseurl'] . $response['name'];
            $files->save(false);
            $attributes = $files->attributes;
            $attributes['code'] = 200;
            return $attributes;
        }
    }
}
