<?php

if ($data) {
    foreach ($data as $key => $item) {
        if(isset($item['type']) && $item['type']) {
            echo $this->render('block/block_' . $item['type'], ['item' => $item, 'datas' => $data,'key' => $key,'model' => $model]);
        }
    }
}