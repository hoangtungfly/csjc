<?php

namespace common\models\admin;

use common\core\cache\GlobalFileCache;
use common\core\enums\StatusEnum;
use common\models\admin\MenuAdmin;
use common\utilities\UtilityArray;
use common\utilities\UtilityHtmlFormat;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * MenuAdminSearch represents the model behind the search form about `common\models\admin\MenuAdmin`.
 */
class MenuAdminSearch extends MenuAdmin
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    /**
     * @return Parent
     */
    public function getParent() {
        return $this->hasOne(self::className(), ['id' => 'pid'])->from(self::tableName() . ' o');
    }
    public function search($query = false)
    {
        if(!$query)
            $query = self::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'pid' => $this->pid,
            'odr' => $this->odr,
            'created_time' => $this->created_time,
            'modified_time' => $this->modified_time,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'module', $this->module])
            ->andFilterWhere(['like', 'controller', $this->controller])
            ->andFilterWhere(['like', 'action', $this->action]);

        return $dataProvider;
    }
    
    public function beforeSave($insert) {
        if(!$this->pid) {
            $this->pid = 0;
        }
        return parent::beforeSave($insert);
    }
    
    public function linkMenu() {
        $this->link         = trim($this->link);
        $this->module       = trim($this->module);
        $this->controller   = trim($this->controller);
        $this->action       = trim($this->action);
        if($this->link != "") {
            if(strpos($this->link,'http') === false) {
                if(!preg_match('~^'.MAIN_ROUTE.'~', $this->link) && $this->link{0} == '/') {
                    $this->link = MAIN_ROUTE . $this->link;
                }
                $this->link = $this->createUrl($this->link);
            }
        } else if($this->link == "" && $this->module != "") {
            $this->link = $this->createUrl('/'.$this->module.'/'.$this->controller.'/'.$this->action,['menu_admin_id' => $this->id]);
        }
        return $this->link;
    }
    
    public function getLinkModule() {
        $module         = strtolower($this->module); 
        $module{0}      = strtoupper($module{0});
        return Yii::getAlias('@app').'/modules/' .strtolower($this->module) . '/' . $module . 'Module.php';
    }
    public function getLinkController() {
        $controller     = strtolower($this->controller); 
        $controller{0}  = strtoupper($controller{0});
        return Yii::getAlias('@app').'/modules/' . strtolower($this->module) . '/controllers/' . $controller . 'Controller.php';
    }
    
    public function deleteFunction($link, $actionjson) {
        $contentFile = @file_get_contents($link);
        if ($contentFile) {
            $contentFunction = UtilityHtmlFormat::getFunction($contentFile, $actionjson);
            if ($contentFunction) {
                $contentFile = str_replace($contentFunction, '', $contentFile);
                file_put_contents($link, $contentFile);
            }
        }
    }
    
    public function getLinkIndex() {
        return Yii::getAlias('@app').'/modules/' . strtolower($this->module) . '/views/' . strtolower($this->controller) . '/index.php';
    }
    
    public function getLinkCreate() {
        return Yii::getAlias('@app').'/modules/' . strtolower($this->module) . '/views/' . strtolower($this->controller) . '/create.php';
    }
    
    public function getLinkUpdate() {
        return Yii::getAlias('@app').'/modules/' . strtolower($this->module) . '/views/' . strtolower($this->controller) . '/update.php';
    }
    
    public function getLinkView() {
        return Yii::getAlias('@app').'/modules/' . strtolower($this->module) . '/views/' . strtolower($this->controller) . '/view.php';
    }
    
    public function getLinkForm() {
        return Yii::getAlias('@app').'/modules/' . strtolower($this->module) . '/views/' . strtolower($this->controller) . '/_form.php';
    }
    
    public static function getAllMenu($id = false) {
        $keyCache = self::getKeyFileCache('getallmenu'.user()->id);
        $cache = new GlobalFileCache();
        $app = $cache->get($keyCache);
        if (!$app) {
            $app = self::find()->where('status = :status',[
            ':status'   => StatusEnum::STATUS_ACTIVED,
        ])->orderBy('odr')->all();
//            foreach($app as $key => $item) {
//                $item->linkMenu();
//                $app[$key] = $item->attributes;
//            }
            $cache->set($keyCache, $app);
        }
        return $app;
    }
    
    public function afterSave($insert, $changedAttributes) {
        $this->deleteDefaultFileCacheMenuAdmin();
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeDelete() {
        $this->deleteDefaultFileCacheMenuAdmin();
        return parent::beforeDelete();
    }
    
    
    
    public function deleteDefaultFileCacheMenuAdmin() {
        $arrayKeyCache = array(
            'getallmenu*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }
}
