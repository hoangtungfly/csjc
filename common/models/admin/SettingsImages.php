<?php

namespace common\models\admin;

use common\core\dbConnection\GlobalActiveRecord;
use common\lib\UploadLib;
use common\utilities\UtilityFile;
use common\utilities\UtilityHtmlFormat;

/**
 * This is the model class for table "settings_images".
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
 * @property string $image_thumb
 * @property string $table_name
 */
class SettingsImages extends GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['did', 'created_time', 'modified_time', 'created_by', 'modified_by'], 'integer'],
            [['name', 'link', 'baseurl', 'image_thumb', 'table_name'], 'string', 'max' => 255]
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
            'baseurl' => 'Baseurl',
            'did' => 'Did',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'image_thumb' => 'Image Thumb',
            'table_name' => 'Table Name',
        ];
    }

    public static function upload($file, $tmp, $id = 0) {
        $up = new UploadLib($file);
        $up->setPath(array($tmp, 'main'));
        $up->setResize(UploadLib::getResizeParamFromYiiParams($tmp));
        $up->uploadImage();
        $response = $up->getResponse(true);
        //Check upload data
        if ($response && $up->getStatus() == '200') {
            $images = new SettingsImages();
            $images->did = (int) $id;
            $images->table_name = $tmp;
            $images->baseurl = $response['baseUrl'];
            $images->name = $response['name'];
            $images->link = $response['baseUrl'] . $response['name'];
            $images->image_thumb = isset($resize[0]['file']) ? $resize[0]['file'] : null;
            $images->save(false);
            $attributes = [
                'id' => $images->id,
                'baseUrl' => $images->baseurl,
                'name' => $images->name,
                'link' => $images->link,
                'code' => 200,
            ];
            return $attributes;
        }
    }

    public static function deleteImageById($deleteIdStr) {
        if ($deleteIdStr != "") {
            $arrayid = explode(',', $deleteIdStr);
            $result = array();
            foreach ($arrayid as $key => $value) {
                $value = (int) $value;
                if ($value) {
                    $result[] = $value;
                }
            }
            self::deleteAll(['id' => $result]);
        }
    }

    public static function deleteImageByName($deleteNameStr) {
        if (trim($deleteNameStr) != "") {
            $arrayid = explode(',', trim($deleteNameStr));
            $result = array();
            foreach ($arrayid as $key => $value) {
                $result[] = $value;
            }
            self::deleteAll(['link' => $result]);
        }
    }

    public static function addImageById($imageid, $model) {
        $imageid = trim($imageid);
        if ($imageid != "") {
            $array = explode(',', $imageid);
            $primaryKey = $model->getKey();
            $did = $model->$primaryKey;
            foreach ($array as $key => $id) {
                $id = (int) $id;
                if ($id) {
                    $modelImage = self::findOne($id);
                    if ($modelImage) {
                        $modelImage->did = $did;
                        $modelImage->save(false);
                    }
                }
            }
            $model->save(false);
        }
    }

    public static function updateImageByLink($link, $table_name, $host = false, $attributes = false) {
        if (!preg_match('/http|(^D\:)/', $link)) {
            $link = UtilityHtmlFormat::replaceUrl($host . $link);
        }
        if(!$link)
            return false;
        $link = preg_replace('/(\.ashx)|(\?(.*))/','',$link);
        $up = new UploadLib(false);
        $up->setPath(array($table_name, 'main'));
        $name = false;
        if(isset($attributes) && isset($attributes['name']) && isset($attributes['changeimage']) && $attributes['changeimage']) {
            $name = $attributes['name'];
        }
        $up->setResize(UploadLib::getResizeParamFromYiiParams($table_name));
        $up->uploadImage($link, $name);
        $response = $up->getResponse(true);
        //Check upload data
        if ($response && $up->getStatus() == '200') {
            $images = new SettingsImages();
            $images->did = 0;
            $images->table_name = $table_name;
            $images->baseurl = $response['baseUrl'];
            $images->name = $response['name'];
            $images->link = $response['baseUrl'] . $response['name'];
            $images->image_thumb = isset($resize[0]['file']) ? $resize[0]['file'] : null;
            $images->save(false);
            $attributes = [
                'id' => $images->id,
                'baseUrl' => $images->baseurl,
                'name' => $images->name,
                'link' => $images->link,
                'code' => 200,
            ];
            return $attributes;
        }
    }
    
    public static function optimizeImage($link) {
        if(!$link) return false;
        if($link{0} == '/')  {
            $link = HTTP_HOST . $link;
        }
        if(str_replace(HTTP_HOST,'',$link) == $link) {
            return false;
        }
        $link_compresse = $link;
        $link_compresse = 'https://img.gs/zsztpvdrbc/full/'.$link;
        $content = filegetcontents($link_compresse);
        if($content) {
            $link_save = str_replace(HTTP_HOST, APPLICATION_PATH, $link);
            UtilityFile::fileputcontents($link_save, $content);
        }
    }
}
