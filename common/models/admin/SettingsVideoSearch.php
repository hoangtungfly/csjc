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
        

        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'created_by' => $this->created_by,
            'created_time' => $this->created_time,
            'modified_by' => $this->modified_by,
            'modified_time' => $this->modified_time,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'table_name', $this->table_name])
                ->andFilterWhere(['like', 'link', $this->link])
                ->andFilterWhere(['like', 'base_url', $this->base_url]);
        return $dataProvider;
    }
}
