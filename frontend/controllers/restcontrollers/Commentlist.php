<?php


namespace frontend\controllers\restcontrollers;

use common\core\action\GlobalAction;
use common\core\enums\StatusEnum;
use common\models\settings\CommentSearch;

class Commentlist extends GlobalAction {

    public function run() {
        $did = (int)$this->getParam('did');
        $table_name = $this->getParam('table_name');
        $sort = (int)$this->getParam('sort');
        $order = 'comment.id desc';
        switch($sort) {
            case 1: $order = 'comment.id'; break;
            case 2: $order = '`like` desc';  break;
        }
        return CommentSearch::find()->select([
            'comment.id',
            'title',
            'content',
            'name',
            'email',
            'count(IF(thank.status = 1, 1, NULL)) AS `like`',
            'count(IF(thank.status = 0, 1, NULL)) as dislike ',
        ])->where(['did' => $did,'table_name' => $table_name, 'comment.status' => StatusEnum::STATUS_ACTIVED])
        ->groupBy('comment.id')->orderBy($order)->join('LEFT JOIN', 'thank', 'comment.id = thank.comment_id')->asArray()->all();
    }

}
