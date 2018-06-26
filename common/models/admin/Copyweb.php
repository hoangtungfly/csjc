<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "copyweb".
 *
 * @property integer $id
 * @property string $linkweb
 * @property string $content_html
 * @property string $arraycss
 * @property string $arrayjs
 * @property string $arraylink
 * @property integer $status
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_time
 * @property integer $modified_by
 * @property string $directory
 * @property string $filename
 * @property string $arrayfont
 * @property string $arrayimage
 * @property string $arrayimg
 * @property string $content_html_final
 */
class Copyweb extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'copyweb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content_html', 'arraycss', 'arrayjs', 'arraylink', 'arrayfont', 'arrayimage', 'arrayimg', 'content_html_final'], 'string'],
            [['status', 'created_time', 'created_by', 'modified_time', 'modified_by'], 'integer'],
            [['linkweb', 'directory', 'filename'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'linkweb' => 'Linkweb',
            'content_html' => 'Content Html',
            'arraycss' => 'Arraycss',
            'arrayjs' => 'Arrayjs',
            'arraylink' => 'Arraylink',
            'status' => 'Status',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_time' => 'Modified Time',
            'modified_by' => 'Modified By',
            'directory' => 'Directory',
            'filename' => 'Filename',
            'arrayfont' => 'Arrayfont',
            'arrayimage' => 'Arrayimage',
            'arrayimg' => 'Arrayimg',
            'content_html_final' => 'Content Html Final',
        ];
    }
}
