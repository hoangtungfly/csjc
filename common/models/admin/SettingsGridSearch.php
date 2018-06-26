<?php

namespace common\models\admin;

use common\core\cache\GlobalFileCache;
use common\models\admin\SettingsGrid;
use common\utilities\UtilityFile;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SettingsGridSearch represents the model behind the search form about `common\models\admin\SettingsGrid`.
 */
class SettingsGridSearch extends SettingsGrid {

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($query) {
        if (!$query)
            $query = SettingsGrid::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'grid_id' => $this->grid_id,
            'enablesorting' => $this->enablesorting,
            'table_id' => $this->table_id,
            'created_time' => $this->created_time,
            'modified_time' => $this->modified_time,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'mapping_id' => $this->mapping_id,
            'choice' => $this->choice,
            'status' => $this->status,
            'odr' => $this->odr,
            'update' => $this->update,
        ]);

        $query->andFilterWhere(['like', 'attribute', $this->attribute])
                ->andFilterWhere(['like', 'label', $this->label])
                ->andFilterWhere(['like', 'headeroptions', $this->headeroptions])
                ->andFilterWhere(['like', 'value', $this->value])
                ->andFilterWhere(['like', 'filter', $this->filter])
                ->andFilterWhere(['like', 'format', $this->format])
                ->andFilterWhere(['like', 'link', $this->link])
                ->andFilterWhere(['like', 'template', $this->template])
                ->andFilterWhere(['like', 'countsql', $this->countsql])
                ->andFilterWhere(['like', 'contentoptions', $this->contentoptions])
                ->andFilterWhere(['like', 'sortlinkoptions', $this->sortlinkoptions]);
        return $dataProvider;
    }

    public static $list_grid_table;

    public static function listGridByTable($table_id) {
        if (!isset(self::$list_grid_table[$table_id])) {
            $key = self::getKeyFileCache('getgridbytable_' . $table_id);
            $cache = new GlobalFileCache();
            self::$list_grid_table[$table_id] = $cache->get($key);
            if (!self::$list_grid_table[$table_id]) {
                self::$list_grid_table[$table_id] = self::find()->where('table_id = :table_id', [':table_id' => $table_id])->orderBy('odr')->all();
                $cache->set($key, self::$list_grid_table[$table_id]);
            }
        }
        return self::$list_grid_table[$table_id];
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
            'getgridbytable_' . $this->table_id,
        );
        $list = MenuAdminSearch::find()->where(['table_id' => $this->table_id])->all();
        /*@var $item MenuAdminSearch */
        foreach ($list as $item) {
            UtilityFile::deleteFile(LINK_PUBLIC_ADMIN_PARTIAL . $item->module . '/' . $item->controller . '/' . $item->action . '.html');
            $item->deleteFunction($item->getLinkController(),'indexjson');
            $item->deleteFunction($item->getLinkCreate(),'createjson');
            $item->deleteFunction($item->getLinkUpdate(),'updatejson');
        }
        $this->deleteCacheFile($arrayKeyCache);
    }
    
}
