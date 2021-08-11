<?php
namespace frontend\models;

use yii\base\Model;

class Calendar2 extends Model
{
    public $resident_id;
    public $form;
    public $pattern_id;
    public $pattern_id2;
    public $use;
    public $time_table;



    public function rules()
    {
        return [
            [['resident_id', 'form', 'pattern_id', 'pattern_id2', 'use'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'resident_id' => 'Співробітник',
            'time_table' => 'Код для табелю',
        ];
    }


}