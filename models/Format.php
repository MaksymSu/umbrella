<?php
namespace frontend\models;

class Format {
    public static $name_arr = ['А4', 'А3','А2','А1','А0'];
    public static $k_arr = [
        'А4'=>['А4'=>1, 'А3'=>1.6, 'А2'=>3.2, 'А1'=>6.4, 'А0'=>12.8],
        'А3'=>['А4'=>0.64, 'А3'=>1, 'А2'=>1.6, 'А1'=>3.2, 'А0'=>6.4],
        'А2'=>['А4'=>0.4, 'А3'=>0.64, 'А2'=>1, 'А1'=>1.6, 'А0'=>3.2],
        'А1'=>['А4'=>null, 'А3'=>0.4, 'А2'=>0.64, 'А1'=>1.0, 'А0'=>1.6],
        'А0'=>['А4'=>null, 'А3'=>null, 'А2'=>0.4, 'А1'=>0.64, 'А0'=>1],
    ];
    public static function getArr(){
        return self::$k_arr;
    }

    public static function getFormats(){
        return self::$name_arr;
    }
}