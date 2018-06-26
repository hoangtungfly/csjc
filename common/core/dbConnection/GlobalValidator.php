<?php

/**
 * Extended ActiveRecord class for whole project
 * 
 * This class is extended from CActiveRecord
 * @author phonghongpham <phongbro1805@gmail.com>
 * @date 13/01/2015
 * @version 1.0
 */

namespace common\core\dbConnection;

use yii\validators\Validator;


class GlobalValidator extends Validator {
    public static $builtInValidators = [
        'boolean' => 'yii\validators\BooleanValidator',
        'captcha' => 'yii\captcha\CaptchaValidator',
        'compare' => 'common\core\validator\GlobalCompareValidator',
        'compare2' => 'common\core\validator\GlobalCompareValidator',
        'date' => 'yii\validators\DateValidator',
        'default' => 'yii\validators\DefaultValueValidator',
        'selectMultiple' => 'common\core\validator\GlobalSelectmultpleValidator',
        'double' => 'yii\validators\NumberValidator',
        'email' => 'yii\validators\EmailValidator',
        'exist' => 'yii\validators\ExistValidator',
        'file' => 'yii\validators\FileValidator',
        'filter' => 'yii\validators\FilterValidator',
        'image' => 'yii\validators\ImageValidator',
        'in' => 'yii\validators\RangeValidator',
        'integer' => [
            'class' => 'yii\validators\NumberValidator',
            'integerOnly' => true,
        ],
        'match' => 'yii\validators\RegularExpressionValidator',
        'number' => 'yii\validators\NumberValidator',
        'required' => 'common\core\validator\GlobalRequiredValidator',
        'safe' => 'yii\validators\SafeValidator',
        'string' => 'yii\validators\StringValidator',
        'trim' => [
            'class' => 'yii\validators\FilterValidator',
            'filter' => 'trim',
            'skipOnArray' => true,
        ],
        'unique' => 'yii\validators\UniqueValidator',
        'url' => 'yii\validators\UrlValidator',
    ];
}
