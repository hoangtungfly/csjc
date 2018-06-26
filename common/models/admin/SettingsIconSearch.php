<?php

namespace common\models\admin;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\admin\SettingsIcon;

/**
 * SettingsIconSearch represents the model behind the search form about `common\models\admin\SettingsIcon`.
 */
class SettingsIconSearch extends SettingsIcon
{
    public function search($query)
    {
        if(!$query)
            $query = SettingsIcon::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'created_time' => $this->created_time,
            'modified_time' => $this->modified_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
