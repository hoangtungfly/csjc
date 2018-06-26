<?php
namespace common\models\booking;

use common\core\model\GlobalModel;
class BookingValidator extends GlobalModel{
    public $people;
    public $meal_id;
    public $day;
    public $title;
    public $firstname;
    public $lastname;
    public $email;
    public $phone;
    public $request;
    public $booking_code;


    public function rules() {
        return [
            [['title', 'firstname', 'lastname', 'email', 'phone'], 'required'],
            [['phone'], 'match', 'pattern' => '/^[\(+]?([0-9]{1,3})\)?[-. ]?([0-9]{1,3})\)?[-. ]?([0-9]{3,4})[-. ]?([0-9]{0,4})[-. ]?([0-9]{0,4})$/', 'message' => 'Please re-enter your phone number'],
            [['email'], 'email', 'message' => 'Please re-enter your email address'],
            [['firstname', 'lastname'], 'string', 'max' => 30],
            [['email', 'request'], 'string', 'max' => 255],
            [['people','meal_id', 'day', 'booking_code'], 'safe'],
        ];
    }
}
