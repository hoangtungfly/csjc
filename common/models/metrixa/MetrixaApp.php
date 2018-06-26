<?php

namespace common\models\metrixa;

use Yii;

/**
 * This is the model class for table "metrixa_app".
 *
 * @property integer $id
 * @property string $name
 * @property string $version
 * @property string $content
 * @property integer $status
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property string $link_window
 * @property integer $download_count
 * @property integer $odr
 * @property string $link_linux
 */
class MetrixaApp extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'metrixa_app';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'version', 'content'], 'required'],
            [['content'], 'string'],
            [['status', 'created_time', 'created_by', 'modified_time', 'modified_by', 'download_count', 'odr'], 'integer'],
            [['name', 'version', 'link_window', 'link_linux'], 'string', 'max' => 255]
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
            'version' => 'Version',
            'content' => 'Content',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'link_window' => 'Link Window',
            'download_count' => 'Download Count',
            'odr' => 'Odr',
            'link_linux' => 'Link Linux',
        ];
    }
}
