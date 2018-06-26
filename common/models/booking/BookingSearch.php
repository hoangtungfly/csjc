<?php

namespace common\models\booking;

use common\models\booking\Booking;
use common\models\meal\Meal;
use common\models\settings\SystemSetting;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\mssql\PDO;

/**
 * BookingSearch represents the model behind the search form about `common\models\booking\Booking`.
 */
class BookingSearch extends Booking
{
    public $day;
    public function search($query)
    {
        if(!$query)
            $query = Booking::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->andFilterWhere([
            'id' => $this->id,
            'people' => $this->people,
            'meal_id' => $this->meal_id,
            'time' => $this->time,
            'customer_id' => $this->customer_id,
            'process_status' => $this->process_status,
            'created_by' => $this->created_by,
            'created_time' => $this->created_time,
            'modified_by' => $this->modified_by,
            'modified_time' => $this->modified_time,
        ]);

        $query->andFilterWhere(['like', 'request', $this->request]);

        return $dataProvider;
    }
    
    public function disabledDays(){
        $result = [];
        if($this->people && $this->meal_id){
            $meal = Meal::findOne((int)$this->meal_id);
            $setting = SystemSetting::find()->one();
            if($meal){
                if(time() < strtotime($meal->time_to.' '.date('d-m-Y', time()))){
                    $result['min_date'] = '0d';
                }
                else{
                    $result['min_date'] = '+1d';
                }
            }
            if($setting->limit_booking && $this->people && $meal){
                if($this->people > $setting->total_booking){
                    $result['max_date'] = '-1d';
                }
                else{
                    $disabled_days = Yii::$app->db->createCommand('call getBookedDate(:PeopleNumber,:MealId,:PeopleTotal,:TimeDistance)')
                            ->bindValue(':PeopleNumber', $this->people, PDO::PARAM_INT)
                            ->bindValue(':MealId', $meal->id, PDO::PARAM_STR)
                            ->bindValue(':PeopleTotal', $setting->total_booking, PDO::PARAM_STR)
                            ->bindValue(':TimeDistance', $meal->time_distance, PDO::PARAM_INT)
                            ->queryAll();
                    if($disabled_days){
                        $result['disabled_days'] = $disabled_days;
                    }
                }
            }
        }
        return $result;
    }
    
    public function getTimes(){
        $result = [];
        if($this->people && $this->meal_id && $this->day){
            $times = Yii::$app->db->createCommand('select getAvailableTime(:v_People,:v_MealId,:v_DayText) AS available_times')
                    ->bindValue(':v_People', (int)$this->people, PDO::PARAM_INT)
                    ->bindValue(':v_MealId', (int)$this->meal_id, PDO::PARAM_INT)
                    ->bindValue(':v_DayText', date('Y/m/d', strtotime($this->day)), PDO::PARAM_STR)
                    ->queryAll();
            if($times && explode(',', $times[0]['available_times'])){
                $result = explode(',', $times[0]['available_times']);
            }
        }
        return $result;
    }
}
