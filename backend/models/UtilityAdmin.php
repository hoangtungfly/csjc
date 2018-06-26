<?php

namespace backend\models;

use common\utilities\UtilityArray;
use yii\helpers\Html;


class UtilityAdmin {
    
    public static function getHtmlRadio($data,$model,$attribute, $onclick = '') {
        $html = '';
        foreach($data as $value => $label){
            $html .= '<div class="col-sm-4"><label>';
            $html .= Html::radio($attribute . '_radio',$model->$attribute == $value ? true : false, [
                'class' => 'ace '. ($model->$attribute == $value ? $attribute . '_radio' : ''),
                'value' => $model->$attribute,
                'onclick' => '$(this).closest(".setting_radio").find(".setting_radio_input").val($(this).val());'.$onclick,
            ]);
            $html .= '<span class="lbl"> '.$label.'</span>';
            $html .= '</label>';
            $html .= '</div>';
        }
        return $html;
    }
    
    public static function getHtmlCheckboxBig($list_checkboxbig,$model,$attribute) {
        $html = '';
        foreach($list_checkboxbig as $id=>$name1){
            $html.= '<div class="checkboxbig'.((UtilityArray::searchArray(explode(',',$model->$attribute), $id)) ? ' active' : '').'" data-id="'.$id.'">';
            $html.= $name1;
            $html.= '</div>';
        }
        return $html;
    }
    
}