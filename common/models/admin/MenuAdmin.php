<?php

namespace common\models\admin;

use Yii;

/**
 * This is the model class for table "settings_menu_admin".
 *
 * @property integer $id
 * @property integer $pid
 * @property string $name
 * @property string $link
 * @property string $icon
 * @property integer $odr
 * @property string $created_time
 * @property string $modified_time
 * @property integer $created_by
 * @property integer $modified_by
 * @property integer $status
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property integer $table_id
 * @property integer $onclick
 * @property integer $add
 * @property integer $delete
 * @property integer $copy
 * @property integer $view
 * @property integer $onclickedit
 * @property integer $edit
 * @property integer $onclickadd
 * @property integer $multi_add
 */
class MenuAdmin extends \common\core\dbConnection\GlobalActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings_menu_admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'odr', 'created_time', 'modified_time', 'created_by', 'modified_by', 'status', 'table_id', 'onclick', 'add', 'delete', 'copy', 'view', 'onclickedit', 'edit', 'onclickadd', 'multi_add'], 'integer'],
            [['name'], 'required'],
            [['name', 'link'], 'string', 'max' => 255],
            [['icon', 'module', 'controller', 'action'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'name' => 'Name',
            'link' => 'Link',
            'icon' => 'Icon',
            'odr' => 'Odr',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'status' => 'Status',
            'module' => 'Module',
            'controller' => 'Controller',
            'action' => 'Action',
            'table_id' => 'Table ID',
            'onclick' => 'Onclick',
            'add' => 'Add',
            'delete' => 'Delete',
            'copy' => 'Copy',
            'view' => 'View',
            'onclickedit' => 'Onclickedit',
            'edit' => 'Edit',
            'onclickadd' => 'Onclickadd',
            'multi_add' => 'Multi Add',
        ];
    }
}
