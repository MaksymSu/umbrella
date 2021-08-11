<?php
namespace frontend\models\Reports;

use frontend\models\Basic;
use yii\base\Model;

class Workers extends Model
{
    public $month_id;
    public $m_arr = ['01 Січень', '02 Лютий', '03 Березень', '04 Квітень', '05 Травень', '06 Червень', '07 Липень', '08 Серпень', '09 Вересень', '10 Жовтень', '11 Листопад', '12 Грудень'];

    public function rules()
    {
        return [
            [['month_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'month_id' => 'Місяць',
        ];
    }


}