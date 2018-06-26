<?php

namespace common\models\admin;

use common\models\admin\SysLanguage;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SysLanguageSearch represents the model behind the search form about `common\models\admin\SysLanguage`.
 */
class SysLanguageSearch extends SysLanguage
{
    public function search($query)
    {
        if(!$query)
            $query = SysLanguage::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'language_id' => $this->language_id,
            'created_time' => $this->created_time,
            'created_by' => $this->created_by,
            'modified_time' => $this->modified_time,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'language_key', $this->language_key])
            ->andFilterWhere(['like', 'language_vi', $this->language_vi])
            ->andFilterWhere(['like', 'language_en', $this->language_en])
            ->andFilterWhere(['like', 'language_name', $this->language_name]);

        return $dataProvider;
    }
    
    public function afterSave($insert, $changedAttributes) {
        $link = \Yii::getAlias('@common').'/messages/';
        $list = self::find()->where('language_name = :language_name',[':language_name' => $this->language_name])->all();
        $array = ['en','vi'];
        foreach($array as $key => $value) {
            $contentEn = $this->getContentFile($list, $value);
            $linkDir = $link.$value.'/';
            if(!is_dir($linkDir)) mkdir($linkDir);
            file_put_contents($linkDir.$this->language_name, $contentEn);
        }
        return parent::afterSave($insert, $changedAttributes);
    }
    
    public function getContentFile($list,$attribute) {
        $str = '';
        if($list) {
            $attribute = 'language_'.$attribute;
            $str .= "<?php\n\n";
            $str .= "return [\n";
            foreach($list as $key => $item) {
                $str .= "\t'".$item->language_key."' => '".str_replace("'","\\'",$item->$attribute)."',\n";
            }
            $str .= "];\n";
        }
        return $str;
    }
}
