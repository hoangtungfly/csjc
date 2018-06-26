<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\widgets\kanga;

use Yii;
use common\models\kanga\Question;
use common\models\kanga\Answer;
use common\models\kanga\LinkAnswer;
use common\models\kanga\KangaCategory;

class CommonnFunctions
{

    public static function checkSyntax($text)
    {
        $result = array();
        $url = \Yii::$app->params['url_check'];
        $url = $url . "?term=" . urlencode($text);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($output, true);
        return ($data['result']);
    }

    public static function updateMongoDb($question_id)
    {        
        $url = \Yii::$app->params['url_insert'];
        $url = $url . "?question_id=" . $question_id;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($output, true);
        //return ($data['result']);
    }

    public static function removeDocument($question_id, $answer_id)
    {
        $url = \Yii::$app->params['url_remove'];
        $url = $url . "?question_id=" . $question_id . "&answer_id=" . $answer_id;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($output, true);
        //return ($data['result']);
    }

    public static function updateHistory($history)
    {
        $qObj = new Question();
        $qObj->content = $history->question_content;
        $qObj->category_id = $history->category_id;
        $qObj->save();
        
        $answer = new Answer();
        $answer->content = $history->answer_content;
        $answer->category_id = $history->category_id;
        $answer->save();
        
        $linkObj = new LinkAnswer();
        $linkObj->question_id = $qObj->id;
        $linkObj->answer_id = $answer->id;
        $linkObj->updateMongoDb = true;
        $linkObj->save();

    }

    public static function updateQuestion($question_id)
    {
        $link_q_a = LinkAnswer::findOne(['question_id' => $question_id]);
        if ($link_q_a) {
            $url = \Yii::$app->params['url_update'];
            $url = $url . "?question_id=" . $question_id;

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $output = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($output, true);
        }
    }
}
