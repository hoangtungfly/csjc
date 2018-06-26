<?php

namespace common\models\admin;

use common\core\cache\GlobalFileCache;
use common\models\admin\SettingsForm;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SettingsFormSearch represents the model behind the search form about `common\models\admin\SettingsForm`.
 */
class SettingsFormSearch extends SettingsForm
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
        $query = SettingsForm::find();

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
            'form_id' => $this->form_id,
            'table_id' => $this->table_id,
            'created_time' => $this->created_time,
            'modified_time' => $this->modified_time,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'status' => $this->status,
            'hidden' => $this->hidden,
            'line' => $this->line,
            'odr' => $this->odr,
        ]);

        $query->andFilterWhere(['like', 'form_name', $this->form_name])
            ->andFilterWhere(['like', 'form_description', $this->form_description])
            ->andFilterWhere(['like', 'fields', $this->fields]);

        return $dataProvider;
    }
    
    
    public static $list_form_table;
    
    public static function listFormByTable($table_id, $multi_add = 0) {
        if(!isset(self::$list_form_table[$table_id])) {
            $key = self::getKeyFileCache('getformbytable_'.$table_id.'_'.$multi_add);
            $cache = new GlobalFileCache();
            self::$list_form_table[$table_id] = $cache->get($key);
            if (!self::$list_form_table[$table_id]) {
                self::$list_form_table[$table_id] = self::find()->where(['table_id' => $table_id, 'multi_add' => $multi_add])->orderBy('odr')->all();
                $cache->set($key, self::$list_form_table[$table_id]);
            }
        }
        return self::$list_form_table[$table_id];
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
            'getformbytable_' . $this->table_id.'*',
            'getfieldbytable_' . $this->table_id.'*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }
    
    public static function insertFormByTableAndMultiadd($table_id,$multi_add = 0) {
        $modelForm = new SettingsFormSearch;
        $modelForm->table_id = $table_id;
        $modelForm->form_name = 'unname';
        $modelForm->multi_add = $multi_add;
        $modelForm->save(false);
        return $modelForm;
    }
}
