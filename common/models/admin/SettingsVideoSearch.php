<?php

namespace common\models\admin;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class SettingsVideoSearch extends SettingsVideo {

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

//        $query->andFilterWhere([
//            'table_id' => $this->table_id,
//            'created_time' => $this->created_time,
//            'modified_time' => $this->modified_time,
//            'created_by' => $this->created_by,
//            'modified_by' => $this->modified_by,
//            'status' => $this->status,
//            'checkview' => $this->checkview,
//            'checksearch' => $this->checksearch,
//            'beginimport' => $this->beginimport,
//            'columncheck' => $this->columncheck,
//            'columnaction' => $this->columnaction,
//            'columnid' => $this->columnid,
//        ]);
//
//        $query->andFilterWhere(['like', 'name', $this->name])
//                ->andFilterWhere(['like', 'table_name', $this->table_name])
//                ->andFilterWhere(['like', 'condition', $this->condition])
//                ->andFilterWhere(['like', 'orderby', $this->orderby])
//                ->andFilterWhere(['like', 'attrsearch', $this->attrsearch])
//                ->andFilterWhere(['like', 'attrarange', $this->attrarange])
//                ->andFilterWhere(['like', 'attrchoice', $this->attrchoice])
//                ->andFilterWhere(['like', 'join', $this->join])
//                ->andFilterWhere(['like', 'excel', $this->excel]);

        return $dataProvider;
    }
}
