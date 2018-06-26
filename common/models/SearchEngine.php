<?php

/**
 * search engine for system
 * 
 * @author Tu Nguyen Anh
 */

namespace common\models;

use common\models\employer\EmployerProfile;
use PDO;
use Yii;
use yii\helpers\Html;

class SearchEngine {

    /**
     * store object for singeleton design pattern
     * 
     * @var SearchEngine 
     */
    protected static $instance;

    /**
     * lotsop staff string
     */
    const LOTSOP_STAFF_LABEL = 'Lotsop admin';

    /**
     * Get instance of this class by static method
     * 
     * @return SearchEngine
     */
    public static function instance() {
        if (!self::$instance) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        return self::$instance;
    }

    /**
     * @var some key search
     */
    public $key_search;
    public $object_id;
    public $object_type;
    public $result = array();
    public $limit = 10;
    public $offset = 0;

    /**
     * process keyword for search
     * 
     * @param string keyword
     * @return array array(
     *  keyword=>
     *  sub_keyword=>
     * )
     */
    public function processKeyword($keyword) {

        $keyword = trim(self::makePureTexts($keyword));
        if ($keyword == '') {
            return false;
        }
        // make string to set score
        $this->key_search = '+' . str_replace(' ', '* +', $keyword) . '*';
        return array(
            'keyword' => $this->key_search,
            'sub_keyword' => '"%' . $this->key_search . '%"',
        );
    }

    /**
     * @author Tu Nguyen Anh
     * 
     * search users who followed this ORG
     * @param string $keyword
     * @param interger $orgId
     */
    public function searchUsersFollowedOfOrg($orgId, $keyword) {
        $this->limit = 10;
        $this->offset = 0;
        $orgId = intval($orgId);
        $keyprocess = $this->processKeyword($keyword);
        if ($this->key_search == '') {
            return $this->renderData();
        }
        $sub_keyword = $keyprocess['sub_keyword'];
        $users = Yii::app()->db->createCommand('call searchUserFollowedByOrg(:keyword,:sub_key,:orgId,:limit,:offset)')
                ->bindParam(':keyword', $this->key_search, PDO::PARAM_STR)
                ->bindParam(':sub_key', $sub_keyword, PDO::PARAM_STR)
                ->bindParam(':orgId', $orgId, PDO::PARAM_INT)
                ->bindParam(':limit', $this->limit, PDO::PARAM_INT)
                ->bindParam(':offset', $this->offset, PDO::PARAM_INT)
                ->queryAll();
        return $this->renderData($users);
    }

    /**
     * @author Tu Nguyen Anh
     * 
     * search users and parners for sending inside message on member page
     * only search users in connection and followed parners
     * @param string $keyword
     * @param interger $userid
     */
    public function searchObject($userId, $keyword, $type = APP_TYPE_CANDIDATE, $employer_id = 0) {
        $this->limit = 10;
        $this->offset = 0;
        $userId = intval($userId);
        $keyprocess = $this->processKeyword($keyword);
        if ($this->key_search == '') {
            return $this->renderData();
        }
        $data = [];
        $sub_key = "%$keyword%";
        if($type == APP_TYPE_CANDIDATE) {
            $data = Yii::$app->db->createCommand('call searchMC()')
                ->queryAll();
        } else if($type == APP_TYPE_EMPLOYER) {
            $user_applied = Yii::$app->db->createCommand('call searchUserAppliedJob(:keyword,:sub_keyword,:employer_id,:limit,:offset)')
                ->bindParam(':keyword', $this->key_search, PDO::PARAM_STR)
                ->bindParam(':sub_keyword', $sub_key, PDO::PARAM_STR)
                ->bindParam(':employer_id', $employer_id, PDO::PARAM_INT)
                ->bindParam(':limit', $this->limit, PDO::PARAM_INT)
                ->bindParam(':offset', $this->offset, PDO::PARAM_INT)
                ->queryAll();
            $mc = Yii::$app->db->createCommand('call searchMC()')
                ->queryAll();
            $data = array_merge($mc, $user_applied);
         } else if($type == APP_TYPE_CUSTOMERS){
             $user = Yii::$app->db->createCommand('call searchUser(:keyword,:sub_keyword,:limit,:offset)')
                ->bindParam(':keyword', $this->key_search, PDO::PARAM_STR)
                ->bindParam(':sub_keyword', $sub_key, PDO::PARAM_STR)
                ->bindParam(':limit', $this->limit, PDO::PARAM_INT)
                ->bindParam(':offset', $this->offset, PDO::PARAM_INT)
                ->queryAll();
             $employer = Yii::$app->db->createCommand('call searchEmployer(:keyword,:sub_keyword,:limit,:offset)')
                ->bindParam(':keyword', $this->key_search, PDO::PARAM_STR)
                ->bindParam(':sub_keyword', $sub_key, PDO::PARAM_STR)
                ->bindParam(':limit', $this->limit, PDO::PARAM_INT)
                ->bindParam(':offset', $this->offset, PDO::PARAM_INT)
                ->queryAll();
             $data = array_merge($user, $employer);
        }
        
        # add lotsop admin to search result
        if (strpos(strtolower(self::LOTSOP_STAFF_LABEL), strtolower($this->key_search)) !== false) {
            $data[] = array(
                'object_id' => APP_TYPE_ADMIN,
                'object_type' => APP_TYPE_ADMIN,
                'name' => self::LOTSOP_STAFF_LABEL,
                'title' => self::LOTSOP_STAFF_LABEL,
            );
        }
        return $this->renderData($data);
    }

    /**
     * render data
     */
    public function renderData($data = array()) {
        $data = !$data ? $this->result : $data;
        $result = array();
        foreach ($data as $item) {
            $format = array(
                'id' => '',
                'value' => '',
                'label' => '',
                'name' => '',
                'avatar' => '',
                'type' => '',
                'description' => ''
            );
            $format['id'] = $item['object_id'];
            $format['value'] = $item['object_id'];
            $format['label'] = isset($item['title']) ? $item['title'] : null;
            $format['name'] = $format['label'];
            if (isset($item['avatar']) && isset($item['avatar_path'])) {
                if ($item['object_type'] == APP_TYPE_USER) {
                    $userComon = new \common\models\user\UserModel();
                    $userComon->avatar_thumb_path = $item['avatar'];
                    $userComon->avatar_path = $item['avatar_path'];
                    $format['avatar'] = $userComon->getAvatar();
                } elseif (intval($item['object_type']) == APP_TYPE_EMPLOYER) {
                    $orgProfile = new EmployerProfile();
                    $orgProfile->employer_thumb = $item['avatar'];
                    $orgProfile->employer_baseUrl = $item['avatar_path'];
                    $format['avatar'] = $orgProfile->renderImg();
                } elseif (intval($item['object_type']) == APP_TYPE_CUSTOMERS) {
                    $orgProfile = new EmployerProfile();
                    $orgProfile->employer_thumb = $item['avatar'];
                    $orgProfile->employer_baseUrl = $item['avatar_path'];
                    $format['avatar'] = $orgProfile->renderImg();
                }
            } else {
                $format['avatar'] = bu('/images/avatar_no.png');
            }

            $format['type'] = $item['object_type'];
            $result[] = $format;
        }
        unset($format);
        return $result;
    }

    /**
     * 
     * @param type $keyword
     * @return array
     */
    public function searchAllUsers($keyword) {
        $match_score1 = "(MATCH (t.key_search) AGAINST (:sc1 IN BOOLEAN MODE))";
        $match_score2 = "(MATCH (t.key_search) AGAINST (:sc2 IN BOOLEAN MODE))";
        $condition = "(MATCH (t.key_search) AGAINST (:sc1 IN BOOLEAN MODE))";
        $keyprocess = $this->processKeyword($keyword);
        $select = 't.user_id AS object_id,
		1 AS object_type,
		t.display_name AS title,
		t.avatar_path,
		t.avatar_name as avatar,"" AS description';
        $select .= ",$match_score1 as score1, $match_score2 as score2";
        $params[':sc1'] = $this->key_search;
        $params[':sc2'] = $keyprocess['sub_keyword'];

        $order = 'score1 DESC,score2 DESC ';
        $data = Yii::app()->db->createCommand()->select($select)->from('user as t')->where($condition, $params)
                        ->limit(3)->order($order);
        return $data->queryAll();
    }

    /**
     * 
     * @param type $keyword
     * @return array
     */
    public function searchAllOrgs($keyword) {
        $match_score1 = "(MATCH (t.org_name) AGAINST (:sc1 IN BOOLEAN MODE))";
        $match_score2 = "(MATCH (t.org_name) AGAINST (:sc2 IN BOOLEAN MODE))";

        $condition = "(MATCH (t.org_name) AGAINST (:sc1 IN BOOLEAN MODE))";
        $keyprocess = $this->processKeyword($keyword);
        $select = "t.org_id AS object_id,
        2 AS object_type,
        t.org_name AS title,
        t.org_baseUrl AS avatar_path,
        t.org_avatar AS avatar,
        '' AS description";
        $select .= ",$match_score1 as score1, $match_score2 as score2";
        $params[':sc1'] = $this->key_search;
        $params[':sc2'] = $keyprocess['sub_keyword'];

        $order = 'score1 DESC,score2 DESC ';
        $data = Yii::app()->db->createCommand()->select($select)->from('org_profile as t')->where($condition, $params)
                        ->limit(3)->order($order);
        return $data->queryAll();
    }

    /**
     * Remove all symbols, html tags...
     * @param string $text
     * @return string
     */
    public static function makePureTexts($text) {
        if ($text) {
            $text = Html::decode($text);
            $text = nl2br(Html::decode($text));
            $text = str_replace('<', ' <', $text);
            $text = str_replace('>', '> ', $text);


            $text = strip_tags($text);

            $text = preg_replace('/[^(\x20-\x7F)\x0A\x0D]*/', '', $text);

            $text = str_replace('nbsp', '', $text);
            $text = preg_replace('/[^A-Za-z0-9\s\-]/', ' ', $text);
            $text = trim(preg_replace('/\s\s+/', ' ', $text));
        }
        return $text;
    }

    /**
     * 
     */
    public static function miningText($text) {
        $keyword = self::makePureTexts($text);
        $param = ["term" => $keyword];
        $url = app()->params['RSearchService']['host_cleantext'] . '?' . http_build_query($param);

        // set curl GET options
        $curl = curl_init();
        curl_setopt_array($curl, array(
            // make sure we're returning the body
            CURLOPT_NOBODY => false,
            // make sure we're GET
            CURLOPT_HTTPGET => true,
            // set the URL
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1
        ));
        // make the request
        $responseBody = trim(curl_exec($curl));
        curl_close($curl);
        if ($responseBody) {
            $result = json_decode($responseBody, true);
            if ($result && $result["result"]) {
                return $result["result"];
            }
        }
        return [];
    }

    /**
     * search opps base on textmining
     * 
     * @param array $terms an array of terms (["travel","australia"])
     * @return array
     */
    public static function searchOppByTextMining($terms, $condition = []) {
//        $terms_query = self::miningText(self::makePureTexts($terms));
        if ($terms) {
            // tracking query
            rmvEmptyKeyValue($condition);
            if (app()->user->id) {
                $condition = ["user_id" => intval(app()->user->id)] + $condition;
            }
            $params = [
                "term"=> trim($terms),
                "condition"=>  json_encode($condition)
            ];
            
            
            $url = app()->params['RSearchService']['host_query_search_opp'] . '?'.  http_build_query($params);
            $curl = curl_init();
            curl_setopt_array($curl, array(
                // make sure we're returning the body
                CURLOPT_NOBODY => false,
                // make sure we're GET
                CURLOPT_HTTPGET => true,
                // set the URL
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1
            ));
            $responseBody = json_decode(trim(curl_exec($curl)),true);
            curl_close($curl);
            $listopp = [];
            if (isset($responseBody["result"]["result"])) {
                $data = $responseBody["result"]["result"];
                foreach ($data as $key => $item) {
                    $listopp[$key] = $item["_id"];
                }

                return ['result' => $listopp, 'totalcount' => $responseBody["result"]['count']];
            }
        }
        return false;
    }

}

