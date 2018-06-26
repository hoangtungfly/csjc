<?php

namespace common\models\admin;

use common\core\cache\GlobalFileCache;
use common\models\admin\SettingsField;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SettingsFieldSearch represents the model behind the search form about `common\models\settings\SettingsField`.
 */
class SettingsFieldSearch extends SettingsField
{

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SettingsField::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'field_id' => $this->field_id,
            'form_id' => $this->form_id,
            'mapping_id' => $this->mapping_id,
            'required' => $this->required,
            'table_id' => $this->table_id,
            'created_time' => $this->created_time,
            'created_by' => $this->created_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'field_name', $this->field_name])
            ->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'field_type', $this->field_type])
            ->andFilterWhere(['like', 'field_options', $this->field_options])
            ->andFilterWhere(['like', 'cid', $this->cid]);

        return $dataProvider;
    }
    
    public static $list_field_table;
    
    public static function listFieldByTable($table_id, $multi_add = 0) {
        if(!isset(self::$list_field_table[$table_id])) {
            $key = self::getKeyFileCache('getfieldbytable_'.$table_id.'_'. $multi_add);
            $cache = new GlobalFileCache();
            self::$list_field_table[$table_id] = $cache->get($key);
            if (!self::$list_field_table[$table_id]) {
                self::$list_field_table[$table_id] = self::find()->where(['table_id' => $table_id,'multi_add' => $multi_add])->all();
                $cache->set($key, self::$list_field_table[$table_id]);
            }
        }
        return self::$list_field_table[$table_id];
    }
    
    public static function listFieldByForm($form_id) {
        $key = self::getKeyFileCache('getfieldbyform_'.$form_id);
        $cache = new GlobalFileCache();
        $app = $cache->get($key);
        if (!$app) {
            $app = self::find()->where('form_id = :form_id', array(':form_id' => $form_id))->all();
            $cache->set($key, $app);
        }
        return $app;
    }
    
    public function afterSave($insert, $changedAttributes) {
        $this->deleteDefaultFileCacheDefault();
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeDelete() {
        $this->deleteDefaultFileCacheDefault();
        return parent::beforeDelete();
    }
    
    public function deleteDefaultFileCacheDefault() {
        $arrayKeyCache = array(
            'getfieldbytable_' . $this->table_id.'*',
            'getfieldbyform_' . $this->form_id.'*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }
    
    public static function AddAttributeName($listField, &$tb) {
        if($listField && is_array($listField) && count($listField)) {
            foreach($listField as $key => $item) {
                $tb[$item->field_name] = $item->field_name;
            }
        }
    }
    
    public static function getAttributesByListField($listField) {
        $result = [];
        if($listField) {
            foreach($listField as $key => $item) {
                $result[] = $item->field_name;
            }
        }
        return $result;
    }
}
