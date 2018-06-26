<?php

namespace common\models\common;

use common\core\enums\StatusEnum;

class AdvertiseSearch extends Advertise {
    public function afterSave($insert, $changedAttributes) {
        $this->deleteDefaultFileCacheDefault();
        parent::afterSave($insert, $changedAttributes);
    }
    public function deleteDefaultFileCacheDefault() {
        $arrayKeyCache = array(
            'getSlider*',
        );
        $this->deleteCacheFile($arrayKeyCache);
    }
    public function beforeDelete() {
        $this->deleteDefaultFileCacheDefault();
        return parent::beforeDelete();
    }
    public static function getSlider() {
        return self::getArrayByObject(self::find()->where([
            'status' => StatusEnum::STATUS_ACTIVED,
            'type'  => 1,
        ])->asArray()->all());
    }
}
