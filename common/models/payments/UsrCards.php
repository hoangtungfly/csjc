<?php

namespace common\models\payments;

use common\core\dbConnection\GlobalActiveRecord;
use common\models\user\UserModel;
use common\models\employer\EmployerProfile;
use common\core\enums\StatusEnum;
use common\core\userIdentity\UserIdentity;
use common\core\payments\LoPayment;
use common\core\enums\jobs\JobsEnum;
use yii\db\Query;
use Yii;

/**
 * This is the model class for table "usr_cards".
 *
 * @property integer $user_card_id
 * @property string $card_name
 * @property string $user_id
 * @property integer $employer_id
 * @property string $customer_id
 * @property string $country_of_issuance
 * @property string $card_type
 * @property string $cron_date
 * @property integer $expired_time
 * @property integer $created_time
 * @property integer $modified_time
 * @property integer $cron_day
 * @property integer $payment_method_token
 * @property integer $subscription_id
 * @property integer $order_id
 */
class UsrCards extends GlobalActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'usr_cards';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id'], 'required'],
            [['user_id', 'employer_id', 'created_time', 'modified_time', 'cron_day', 'status', 'is_australia', 'expired_time', 'order_id'], 'integer'],
            [['cron_date'], 'safe'],
            [['card_name', 'customer_id', 'country_of_issuance', 'payment_method_token', 'subscription_id'], 'string', 'max' => 50],
            [['card_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'user_card_id' => 'User Card ID',
            'card_name' => 'Card Name',
            'user_id' => 'User ID',
            'employer_id' => 'Employer ID',
            'customer_id' => 'Customer ID',
            'country_of_issuance' => 'Country Of Issuance',
            'card_type' => 'Card Type',
            'cron_date' => 'Cron Date',
            'cron_day' => 'Cron Day',
            'expired_time' => 'Expired time',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'status' => 'Status',
            'is_australia' => 'In australia',
            'payment_method_token' => 'Payment method token',
            'subscription_id' => 'Subscription id',
            'order_id' => 'Order id',
        ];
    }
    
    /**
     * Insert infomation cart 
     * @param array $dataCard
     */
    public static function insertCustomer($dataCard) {
        $usrCards = self::find()->where(['user_id' => $dataCard['user_id']])->one();
        if($usrCards == null) {
            $usrCards = new UsrCards();
        }

        $timeCron = strtotime("+1 day");
        $timeExpired = date("Y-m-d", strtotime("+1 month", $timeCron));
        $usrCards->cron_date = date("Y-m-d", $timeCron);
        $usrCards->cron_day = (int)date('j', $timeCron);
        $usrCards->expired_time = strtotime($timeExpired);
        $usrCards->setAttributes($dataCard, false);
        $usrCards->status = StatusEnum::STATUS_ACTIVED;
        $usrCards->save(false);
    }
    
    
    public static function updateCustomer($dataCard) {
        $usrCards = self::find()->where(['user_id' => $dataCard['user_id']])->one();
        if($usrCards == null) {
            $usrCards = new UsrCards();
        }

        $usrCards->setAttributes($dataCard, false);
        $usrCards->status = StatusEnum::STATUS_ACTIVED;
        $usrCards->save();
    }
    
    /**
     * Check user or employer expired membership
     * @param int $id
     * @param bolean $isUser
     * @return bolean
     */
    public static function checkExpired($id, $isUser = true) {
        $time = time();
        $query = self::find();
        $query->select('user_card_id');
        $query->where('expired_time < :expired_time', [':expired_time' => $time]);
        if($isUser) {
            $query->andWhere(['user_id' => $id]);
            $query->andWhere('employer_id = 0');
        } else {
            $query->andWhere(['employer_id' => $id]);
        }
        
        return $query->scalar();
    }
    
    /**
     * 
     * Get user or employer expired membership
     * @param int $id
     * @param bolean $isUser
     * @return bolean
     */
    public static function getExpired($id, $isUser = true) {
        $time = time();
        $query = self::find();
        $query->select('user_card_id');
        $query->where('expired_time < :expired_time', [':expired_time' => $time]);
        if($isUser) {
            $query->andWhere(['user_id' => $id]);
            $query->andWhere('employer_id = 0');
        } else {
            $query->andWhere(['employer_id' => $id]);
        }
        
        return $query->scalar();
    }

    /**
     * 
     * @param type $condition
     * @param type $isUser
     * @param type $offset
     * @param type $limit
     * @return type
     */
    public static function getCustermer($condition, $isUser, $offset, $limit) {
        $day = (int) date('d', time());
        $query = new Query();
        $query->select('uc.*');
        $query->from(self::tableName() . ' uc');
        if($isUser) {
            $query->join('INNER JOIN', UserModel::tableName() . ' u', 'u.user_id = uc.user_id AND u.membership_level_id = :membership_level_id', [':membership_level_id' => UserEnum::ROLE_PERMIUM_CANDIDATE]);
            $colum = 'user_id';
        } else {
            $query->join('INNER JOIN', EmployerProfile::tableName() . ' u', 'u.employer_id = uc.employer_id AND u.membership_level_id = :membership_level_id', [':membership_level_id' => UserEnum::ROLE_PERMIUM_EMPLOYER]);
            $colum = 'employer_id';
        }
        
        $query->where($condition, [':day' => $day, ':status' => StatusEnum::STATUS_ACTIVED]);
        $query->groupBy($colum);
        $query->offset($offset);
        $query->limit($limit);
        
        return $query->all();
    }
    
    /**
     * 
     * @param type $user_id
     * @return boolean
     */
    public static function downgradeUser($user_id) {
        $model = UserModel::findOne($user_id);
        $model->membership_level_id = UserEnum::ROLE_BASIC_CANDIDATE;
        $check = true;
        if ($model->save(false)) {
            $modelLo = new LoPayment();
            if ($modelLo->paymentWith()) {
                $modelLo->getCustomerByUser($user_id);
                if ($modelLo->deleteSubscription()) {
                    $iden = new UserIdentity();
                    $iden->setRoleId(UserEnum::ROLE_BASIC_CANDIDATE);
                } else {
                    $check = false;
                }
            } else {
                $check = false;
            }

            if ($check == false) {
                $model->membership_level_id = UserEnum::ROLE_PERMIUM_CANDIDATE;
                $model->save(false);
            }
        } else {
            $check = false;
        }

        return $check;
    }

    /**
     * 
     * @param type $employer_id
     * @return boolean
     */
    public static function downgradeEmployer($employer_id) {
        $model = EmployerProfile::findOne($employer_id);
        $model->membership_level_id = UserEnum::ROLE_BASIC_EMPLOYER;
        $check = true;
        if ($model->save(false)) {
            $modelLo = new LoPayment();
            if ($modelLo->paymentWith()) {
                $modelLo->getCustomerByUser($employer_id, false);
                if ($modelLo->deleteSubscription()) {
                     Yii::$app->db->createCommand('CALL downgradeMembershipEmployer(:employerId, :topJob);')
                            ->bindValue(':employerId', $employer_id)
                            ->bindValue(':topJob', JobsEnum::POST_BASIC)
                            ->execute();
                    $iden = new UserIdentity();
                    $iden->setRoleId(UserEnum::ROLE_BASIC_EMPLOYER);
                } else {
                    $check = false;
                }
            } else {
                $check = false;
            }

            if ($check == false) {
                $model->membership_level_id = UserEnum::ROLE_PERMIUM_EMPLOYER;
                $model->save(false);
            }
        } else {
            $check = false;
        }

        return $check;
    }
    
    /**
     * 
     * @param type $condition
     * @param type $isUser
     * @param type $offset
     * @param type $limit
     * @return type
     */
    public static function getDayLeft($id, $isUser = true) {
        $query = UsrCards::find()->select('expired_time');
        if($isUser) {
            $query->where(['user_id' => $id, 'employer_id' => 0]);
        } else {
            $query->where(['employer_id' => $id]);
        }
        
        $expiredTime = $query->scalar();
        $dayLeft = 0;
        $time = time();
        if($expiredTime && $time) {
            $dayLeft = ($expiredTime - $time)/86400;
        }
        
        return (int)$dayLeft;
    }
    
    public static function checkPurchaseCard($user_id = false) {
        if(!$user_id) {
            $user_id = user()->id;
        }
        return self::findOne(['user_id' => $user_id]) ? true : false;
    }

}
