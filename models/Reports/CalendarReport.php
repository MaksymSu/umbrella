<?php
namespace frontend\models\Reports;

use yii\base\Model;

class CalendarReport extends Model
{
    public $resident_id;




    public function rules()
    {
        return [
            [['resident_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'resident_id' => 'Резидент',
        ];
    }


}