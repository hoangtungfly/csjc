<?php
$is_home_page = isset($this->context->alias) && ($this->context->alias == 'trang-chu' || $this->context->alias == 'home');
if ($data) {
    foreach ($data as $key => $item) {
        if(isset($item['type']) && $item['type']) {
            if($is_home_page && $key == 2) {
                echo '<div class="container-fluid section_2">';
            }
            echo $this->render('block/block_' . $item['type'], ['item' => $item, 'datas' => $data,'key' => $key,'model' => $model]);
            if($is_home_page && $key == 5) {
                echo '</div>';
            }
        }
    }
}