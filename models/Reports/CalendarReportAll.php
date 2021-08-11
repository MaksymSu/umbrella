<?php
namespace frontend\models\Reports;

use frontend\models\Div;
use yii\base\Model;

class CalendarReportAll extends Model
{
    public $resident_id;
    public $sector_id;
    public $struct_id;
    public $div_id;
    public $month;
    public $year;
    public $day_left;
    public $IPN = '143123223010';
    public $types =['Повний','Вихідні'];
    public $type_selected;




    public function rules()
    {
        return [
            [['resident_id', 'sector_id', 'div_id', 'struct_id'], 'safe'],
            [['year', 'month', 'day_left', 'IPN'], 'string'],
            [['type_selected'],'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'resident_id' => 'Резидент',
            'sector_id' => 'Сектор',
            'div_id' => 'Відділ',
            'struct_id' => 'Структура',
            'month' => 'Місяць',
            'year' => 'Рік',
            'day_left' => 'До',
            'IPN' => 'ІПН',
            'type_selected' => 'Тип табелю'
        ];
    }


}