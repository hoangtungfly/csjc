<?php

namespace common\models\order;

use yii\db\ActiveQuery;

class OrderSearch extends Order {

    
    public function rules() {
        $result = parent::rules();
        $result[] = [['shipping_email'], 'email','message' => \Yii::t('message','Bạn phải nhập đúng định dạng email')];
//        $result[] = [['captcha'], 'captcha', 'on' => 'frontend'];
//        $result[] = [['captcha'], 'required', 'on' => 'frontend'];
        return $result;
    }
    /**
     * @return ActiveQuery
     */
    public function getOrderProducts() {
        return $this->hasMany(OrderProductSearch::className(), ['order_id' => 'id']);
    }
    
    public function beforeDelete() {
        OrderProductSearch::find()->where(['order_id' => $this->id])->all();
        return parent::beforeDelete();
    }

}
