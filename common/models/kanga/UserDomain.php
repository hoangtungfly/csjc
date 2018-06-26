<?php

namespace common\models\kanga;

use Yii;

/**
 * This is the model class for table "user_domain".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $created_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $modified_time
 * @property integer $domain_id
 * @property string $setting_header_label
 * @property string $setting_button_label
 * @property integer $kanga_html_template_id
 */
class UserDomain extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_domain';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'domain_id'], 'required'],
            [['user_id', 'created_time', 'created_by', 'modified_by', 'modified_time', 'domain_id', 'kanga_html_template_id'], 'integer'],
            [['setting_header_label'], 'string', 'max' => 50],
            [['setting_button_label'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'created_time' => 'Created Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'modified_time' => 'Modified Time',
            'domain_id' => 'Domain ID',
            'setting_header_label' => 'Setting Header Label',
            'setting_button_label' => 'Setting Button Label',
            'kanga_html_template_id' => 'Kanga Html Template ID',
        ];
    }
}
