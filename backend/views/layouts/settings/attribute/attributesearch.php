<?php

use yii\helpers\Html;
use yii\helpers\Url;

if ($modelTable->attrsearch != '') {
    echo '<div class="fl">';
    $array = explode('\\', $model->className());
    $className = $array[count($array) - 1];
    $route = '';
    $activeAll = isset($_GET['all']) || !isset($_GET[$className]) ? 'active' : '';
    echo Html::a('All', $this->createUrl($route, [
                'menu_admin_id' => $this->context->menu_admin_id,
            ]), [
//        'data-id'   => 'all',
        'class' => 'btn index-header setting_attsearch ' . $activeAll,
    ]);
    $arraySearch = json_decode($modelTable->attrsearch);
    foreach ($arraySearch as $key => $item) {
        if (isset($item->attribute) && $item->attribute != "") {
            $attribute = $item->attribute;
            $title = $item->title ? $item->title : $item->attribute;

            $getname = $className . '[' . $attribute . ']';

            $vl = $item->value;

            $active = '';

            if (isset($_GET[$className][$attribute]) && $_GET[$className][$attribute] == $vl) {
                $active = 'active';
            }
            echo Html::a($title, $this->createUrl($route, [
                        $getname => $vl,
                        'menu_admin_id' => $this->context->menu_admin_id,
                    ]), [
                'class' => 'btn index-header setting_attsearch ' . $active,
            ]);
        }
    }
    echo '</div>';
}