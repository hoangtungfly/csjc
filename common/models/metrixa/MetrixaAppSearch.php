<?php

namespace common\models\metrixa;

class MetrixaAppSearch extends MetrixaApp {
    public function update($runValidation = true, $attributeNames = null) {
        $this->deleteDefaultFileCache();
        parent::update($runValidation, $attributeNames);
    }
}
