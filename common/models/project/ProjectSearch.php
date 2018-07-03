<?php

namespace common\models\project;

use common\core\cache\GlobalFileCache;
use common\core\enums\ProjectEnum;
use common\core\enums\StatusEnum;
use common\utilities\UtilityFile;
use common\utilities\UtilityHtmlFormat;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use const APPLICATION_PATH;

class ProjectSearch extends Project {

    /**
     * @return ActiveQuery
     */
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($query) {

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->andFilterWhere([
            'id' => $this->id,
            'created_time' => $this->created_time,
            'modified_time' => $this->modified_time,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'range_date' => $this->range_date,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'estimation_budget', $this->estimation_budget])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'content', $this->content])
                ->andFilterWhere(['like', 'meta_title', $this->meta_title])
                ->andFilterWhere(['like', 'meta_description', $this->meta_description])
                ->andFilterWhere(['like', 'meta_keyword', $this->meta_keyword])
                ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
    
    public static function getListProjectByLimit($limit = 10) {
        $keyCache = self::getKeyFileCache('getListProjectByLimit');
        $cache = new GlobalFileCache();
        $list = $cache->get($keyCache);
        if(!$list) {
            $list = self::getArrayByObject(self::find()->select(ProjectEnum::SELECT)->where([
                'status' => StatusEnum::STATUS_ACTIVED,
            ])->limit($limit)->orderBy(['id' => SORT_DESC])->asArray()->all());
            $cache->set($keyCache,$list);
        }
        
        return $list;
    }
    
    public function createUrl($route = '', $params = array()) {
        return parent::createUrl('project', ['alias' => $this->alias,'id' => $this->id]);
    }
    
    public function beforeSave($insert) {
        if(!$this->alias && $this->name) {
            $this->alias = UtilityHtmlFormat::parseToAlias($this->name);
        }
        return parent::beforeSave($insert);
    }
    
    public function deleteDefaultFileCacheDefault() {
        UtilityFile::deleteFile([
            APPLICATION_PATH . '/cache/file/footer.php',
            APPLICATION_PATH . '/cache/file/right.php',
        ]);
        $arrayKeyCache = array(
            'getListProjectByLimit*',
            'getListByCategoryid*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }
    
    public static function getListByCategoryid($level = 1,$limit = 10, $offset = 0, $not_in = false,$w_h = false) {
        $query = self::find()->select(ProjectEnum::SELECT);
        if($not_in) {
            $query->where('`id` not in ('.implode(',',$not_in).') ');
        }
        $result = $query->limit($limit)->offset($offset)->orderBy(['id' => SORT_DESC])->asArray()->all();
        return self::getArrayByObject($result,false,$w_h);
    }
}
