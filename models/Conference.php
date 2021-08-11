<?php
namespace frontend\models;

use tecnocen\yearcalendar\data\DataItem;
use tecnocen\yearcalendar\data\JsExpressionHelper;
use yii\db\ActiveRecord;

class Conference extends ActiveRecord implements DataItem
{
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['start_date', 'end_date'], 'safe'],
        ];
    }


    public function getName()
    {
        return $this->name;
    }

    public function getStartDate()
    {
       // var_dump($this->start_date);
       // exit();


        return JsExpressionHelper::parse($this->start_date);
    }

    public function getEndDate()
    {
        return JsExpressionHelper::parse($this->end_date);
    }

    // rest of the active record code.
}
