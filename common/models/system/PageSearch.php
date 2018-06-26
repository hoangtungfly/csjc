<?php

namespace common\models\system;

use common\core\enums\StatusEnum;
use common\models\system\Page;
use yii\data\ActiveDataProvider;

/**
 * PageSearch represents the model behind the search form about `common\models\system\Page`.
 */
class PageSearch extends Page
{
    public function search($query)
    {
        if(!$query)
            $query = Page::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'order' => $this->order,
            'display_header' => $this->display_header,
            'display_footer' => $this->display_footer,
            'created_by' => $this->created_by,
            'created_time' => $this->created_time,
            'modified_by' => $this->modified_by,
            'modified_time' => $this->modified_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'page_title', $this->page_title])
            ->andFilterWhere(['like', 'page_class', $this->page_class])
            ->andFilterWhere(['like', 'href', $this->href])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
    
    public static function getFooters(){
        $result = [];
        $footers = self::find()->where(['display_footer' => StatusEnum::STATUS_ACTIVED])->limit(10)->all();
        if($footers){
            foreach($footers as $footer) {
                $result[] = [
                    'name' => $footer->name,
                    'page_title' => $footer->page_title,
                    'href' => $footer->href,
                ];
            }
        }
        return $result;
    }
}
